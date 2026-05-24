<?php
namespace SlideAI;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Slide\Background\Color as BackgroundColor;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Bar;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Pie;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Line;
use PhpOffice\PhpPresentation\Shape\Chart\Series;
use PhpOffice\PhpPresentation\Shape\Drawing\File as DrawingFile;

class PowerPointService {

    // ─── Unit constants ───────────────────────────────────────────────────────
    //
    // PhpPresentation has TWO different unit systems depending on the shape type:
    //
    //   RichTextShape / shape primitives
    //     setOffsetX / setOffsetY / setWidth / setHeight  →  PIXELS at screen DPI
    //     1 px = 9 525 EMU  (96 DPI screen)
    //     So multiply inches by 96 to get the value to pass.
    //
    //   DrawingFile (embedded images)
    //     setWidth / setHeight  →  POINTS (desktop DPI)
    //     1 pt = 12 700 EMU  (72 pt/inch)
    //     So multiply inches by 72 to get the value to pass.
    //
    //   DrawingFile setOffsetX / setOffsetY  →  still PIXELS like shapes.
    //
    // The slide layout (setCX / setCY) uses raw EMU.
    // ─────────────────────────────────────────────────────────────────────────

    // Slide logical dimensions in inches
    private const W_IN  = 10.0;
    private const H_IN  = 5.625;

    // EMU values for layout only
    private const SLIDE_W_EMU = 9_144_000;   // 10 inches
    private const SLIDE_H_EMU = 5_143_500;   // 5.625 inches

    // Pixel DPI for shapes / positions (RichTextShape, offsets for images)
    private const PX = 96;

    // Point DPI for image dimensions (DrawingFile width/height only)
    private const PT = 72;

    // Derived pixel slide dimensions (used for overflow checks / width math)
    private const SLIDE_W_PX = self::W_IN  * self::PX;   // 960 px
    private const SLIDE_H_PX = self::H_IN  * self::PX;   // 540 px

    /** Convert inches → pixels (use for ALL positions AND RichTextShape sizes) */
    private function px(float $inches): int {
        return (int) round($inches * self::PX);
    }

    /** Convert inches → points (use ONLY for DrawingFile setWidth / setHeight) */
    private function pt(float $inches): int {
        return (int) round($inches * self::PT);
    }

    /** Dynamically reduce font size if text is too long */
    private function getDynamicFontSize(string $text, int $baseSize, int $maxChars, int $minSize): int {
        $len = strlen($text);
        if ($len > $maxChars) {
            $ratio = $maxChars / $len;
            return max($minSize, (int) round($baseSize * $ratio));
        }
        return $baseSize;
    }

    // ─── Colour themes ────────────────────────────────────────────────────────

    private array $themes = [
        'purple' => ['bg' => '1E1B4B', 'accent' => '7C3AED', 'light' => 'A78BFA', 'text' => 'FFFFFF', 'subtext' => 'C4B5FD'],
        'blue'   => ['bg' => '0F172A', 'accent' => '3B82F6', 'light' => '93C5FD', 'text' => 'FFFFFF', 'subtext' => 'BFDBFE'],
        'teal'   => ['bg' => '0D2B2E', 'accent' => '14B8A6', 'light' => '5EEAD4', 'text' => 'FFFFFF', 'subtext' => '99F6E4'],
        'orange' => ['bg' => '1C100A', 'accent' => 'F97316', 'light' => 'FDBA74', 'text' => 'FFFFFF', 'subtext' => 'FED7AA'],
    ];

    // =========================================================================
    // PUBLIC ENTRY POINT
    // =========================================================================

    public function createPresentation(array $slidesData, string $filename = "presentation.pptx"): string {
        $prs = new PhpPresentation();
        $prs->getLayout()->setCX(self::SLIDE_W_EMU)->setCY(self::SLIDE_H_EMU);
        $prs->removeSlideByIndex(0);

        foreach ($slidesData as $index => $slide) {
            $layout    = $slide['layout']      ?? 'bullets';
            $theme     = $slide['theme']       ?? 'purple';
            $colors    = $this->themes[$theme] ?? $this->themes['purple'];
            $keyword   = $slide['imageKeyword'] ?? ($slide['title'] ?? 'business');
            $chartData = $slide['chartData']   ?? null;

            $oSlide = $prs->createSlide();
            $this->setSlideBackground($oSlide, $colors['bg']);

            if ($layout === 'title' || $index === 0) {
                $this->buildTitleSlide($oSlide, $slide, $colors, $keyword);
            } elseif ($layout === 'chart' && !empty($chartData)) {
                $this->buildChartSlide($oSlide, $slide, $colors, $chartData, $index + 1);
            } elseif ($layout === 'image-left' || $layout === 'image-right') {
                $this->buildImageSlide($oSlide, $slide, $colors, $keyword, $layout, $index + 1);
            } else {
                $this->buildBulletsSlide($oSlide, $slide, $colors, $keyword, $index + 1);
            }
        }

        $writer   = IOFactory::createWriter($prs, 'PowerPoint2007');
        $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        $writer->save($filePath);
        $this->patchChartRels($filePath);

        return $filePath;
    }

    // =========================================================================
    // CHART RELS PATCHER
    // =========================================================================

    private function patchChartRels(string $pptxPath): void {
        $emptyRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                   . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                   . '</Relationships>';

        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($pptxPath) !== true) {
                error_log('[SlideAI] patchChartRels: ZipArchive could not open ' . $pptxPath);
            } else {
                $patched = 0;
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $name = $zip->getNameIndex($i);
                    if (!preg_match('#^ppt/charts/(chart\d+\.xml)$#', $name, $m)) continue;
                    $relsPath = 'ppt/charts/_rels/' . $m[1] . '.rels';
                    if ($zip->locateName($relsPath) === false) {
                        $zip->addFromString($relsPath, $emptyRels);
                        $patched++;
                    }
                }
                $zip->close();
                if ($patched > 0) error_log("[SlideAI] ZipArchive patched $patched chart rels.");
                return;
            }
        }

        // Python fallback
        $pyScript = tempnam(sys_get_temp_dir(), 'slideai_patch_') . '.py';
        $escapedRels = addslashes($emptyRels);
        $pyCode = <<<PYTHON
import sys, zipfile, re, os, shutil
path = sys.argv[1]
empty = b'{$escapedRels}'
tmp = path + '.bak'
shutil.copy2(path, tmp)
with zipfile.ZipFile(tmp, 'r') as zin:
    names = set(zin.namelist())
    charts = [n for n in names if re.match(r'ppt/charts/chart\d+\\.xml\$', n)]
    missing = [(c, 'ppt/charts/_rels/' + os.path.basename(c) + '.rels')
               for c in charts if 'ppt/charts/_rels/' + os.path.basename(c) + '.rels' not in names]
    if not missing:
        os.unlink(tmp); sys.exit(0)
    out = path + '.new'
    with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as zout:
        for item in zin.infolist():
            zout.writestr(item, zin.read(item.filename))
        for (_, rp) in missing:
            zout.writestr(rp, empty)
os.replace(out, path)
os.unlink(tmp)
PYTHON;
        file_put_contents($pyScript, $pyCode);
        $output = shell_exec(escapeshellarg('python3') . ' ' . escapeshellarg($pyScript) . ' ' . escapeshellarg($pptxPath) . ' 2>&1');
        unlink($pyScript);
        if ($output) error_log('[SlideAI] Python patch output: ' . $output);
    }

    // =========================================================================
    // SLIDE BUILDERS
    // =========================================================================

    private function buildTitleSlide($oSlide, array $data, array $colors, string $keyword): void {
        // Background image (full bleed)
        $imgPath = $this->downloadImage($keyword, 1280, 720);
        if ($imgPath) {
            $this->addImage($oSlide, $imgPath, 0, 0, self::W_IN, self::H_IN);
        }

        // Dark overlay — FIX: alpha 40 (60% opaque) so text stays legible
        $this->addRect($oSlide, 0, 0, self::W_IN, self::H_IN, $colors['bg'], 40);

        // Top accent bar
        $this->addRect($oSlide, 0, 0, self::W_IN, 0.07, $colors['accent']);

        // Main title
        $this->addText(
            $oSlide, $this->cleanText($data['title'] ?? ''),
            1.0, 1.6, 8.0, 1.6,
            $colors['text'], 48, true, Alignment::HORIZONTAL_CENTER
        );

        // Thin separator line
        $this->addRect($oSlide, 3.5, 3.3, 3.0, 0.05, $colors['accent']);

        // Sub-tagline (first bullet)
        if (!empty($data['bullets'][0])) {
            $this->addText(
                $oSlide, $this->cleanText($data['bullets'][0]),
                1.5, 3.45, 7.0, 0.65,
                $colors['subtext'], 18, false, Alignment::HORIZONTAL_CENTER
            );
        }

        // Sub-taglines (remaining bullets stacked cleanly)
        if (!empty($data['bullets']) && count($data['bullets']) > 1) {
            $tags = array_slice($data['bullets'], 1, 2);
            $y = 4.2;
            foreach ($tags as $tag) {
                $this->addText(
                    $oSlide, $this->cleanText($tag),
                    1.0, $y, 8.0, 0.4,
                    $colors['light'], 14, false, Alignment::HORIZONTAL_CENTER
                );
                $y += 0.45;
            }
        }

        // Watermark
        $this->addText(
            $oSlide, 'Generated by SlideAI',
            0, 5.3, self::W_IN, 0.28,
            $colors['subtext'], 9, false, Alignment::HORIZONTAL_CENTER
        );

        // Bottom accent bar
        $this->addRect($oSlide, 0, self::H_IN - 0.06, self::W_IN, 0.06, $colors['accent']);
    }

    private function buildBulletsSlide($oSlide, array $data, array $colors, string $keyword, int $num): void {
        // Top accent bar
        $this->addRect($oSlide, 0, 0, self::W_IN, 0.07, $colors['accent']);

        // Attempt image download
        $imgPath = $this->downloadImage($keyword, 800, 600);
        $hasImage = ($imgPath !== null);

        // ── FIX: when no image, text expands to full width ──────────────────
        if ($hasImage) {
            $imgX_in  = 5.70;
            $imgW_in  = self::W_IN - $imgX_in;    // 4.30"
            $textW_in = $imgX_in - 0.25;           // 5.45"

            // Image fills right portion — FIX: pass POINTS for DrawingFile size
            $this->addImage($oSlide, $imgPath, $imgX_in, 0.07, $imgW_in, self::H_IN - 0.07);

            // Divider line between text and image
            $this->addRect($oSlide, $imgX_in - 0.05, 0.07, 0.05, self::H_IN - 0.07, $colors['bg']);
        } else {
            $textW_in = self::W_IN - 0.70;   // nearly full width
        }

        // Slide number badge
        $this->addRect($oSlide, 0.35, 0.18, 0.38, 0.38, $colors['accent']);
        $this->addText(
            $oSlide, (string)$num,
            0.35, 0.18, 0.38, 0.38,
            $colors['text'], 13, true, Alignment::HORIZONTAL_CENTER
        );

        // Slide title
        $titleSize = $this->getDynamicFontSize($data['title'] ?? '', 32, 45, 22);
        $this->addText(
            $oSlide, $this->cleanText($data['title'] ?? ''),
            0.85, 0.12, $textW_in - 0.85, 1.0,
            $colors['text'], $titleSize, true, Alignment::HORIZONTAL_LEFT
        );

        // Bullet points
        $this->addBullets(
            $oSlide, $data['bullets'] ?? [],
            0.35, 1.3, $textW_in - 0.1, self::H_IN - 1.55,
            $colors['accent'], $colors['text'], 16
        );

        // Slide number (bottom-right)
        $this->addText(
            $oSlide, (string)$num,
            self::W_IN - 0.6, 5.35, 0.5, 0.2,
            $colors['subtext'], 9, false, Alignment::HORIZONTAL_RIGHT
        );
    }

    private function buildChartSlide($oSlide, array $data, array $colors, array $chartData, int $num): void {
        // Top accent bar
        $this->addRect($oSlide, 0, 0, self::W_IN, 0.07, $colors['accent']);

        // Slide title
        $titleSize = $this->getDynamicFontSize($data['title'] ?? '', 32, 45, 22);
        $this->addText(
            $oSlide, $this->cleanText($data['title'] ?? ''),
            0.4, 0.12, 9.2, 1.0,
            $colors['text'], $titleSize, true, Alignment::HORIZONTAL_LEFT
        );

        $labels = array_map([$this, 'cleanText'], $chartData['labels'] ?? []);
        $values = array_map('floatval', $chartData['values'] ?? []);

        if (!empty($labels) && !empty($values)) {
            $seriesData = array_combine($labels, $values);
            $series     = new Series($this->cleanText($chartData['title'] ?? 'Data'), $seriesData);
            $series->setShowSeriesName(false)
                   ->setShowValue(true)
                   ->setShowLeaderLines(false);

            // FIX: White data labels so they are visible on dark-coloured bars
            $series->getFont()
                   ->setSize(10)
                   ->setColor(new Color('FFFFFFFF'))
                   ->setName('Segoe UI');

            $type = strtolower($chartData['type'] ?? 'bar');
            $chartType = match($type) {
                'pie'  => new Pie(),
                'line' => new Line(),
                default => new Bar(),
            };
            $chartType->addSeries($series);

            $chartShape = $oSlide->createChartShape();
            $chartShape->setName($this->cleanText($chartData['title'] ?? 'Chart'))
                       ->setWidth($this->px(5.8))
                       ->setHeight($this->px(3.9))
                       ->setOffsetX($this->px(0.25))
                       ->setOffsetY($this->px(1.3));
            $chartShape->getShadow()->setVisible(false);
            $chartShape->getPlotArea()->setType($chartType);
            
            // Fix invisible axes on dark backgrounds and remove overlapping 'Axis Title'
            if ($chartShape->getPlotArea()->getAxisX()) {
                $chartShape->getPlotArea()->getAxisX()->setTitle('');
                $chartShape->getPlotArea()->getAxisX()->getTickLabelFont()
                           ->setColor(new Color('FFFFFFFF'));
            }
            if ($chartShape->getPlotArea()->getAxisY()) {
                $chartShape->getPlotArea()->getAxisY()->setTitle('');
                $chartShape->getPlotArea()->getAxisY()->getTickLabelFont()
                           ->setColor(new Color('FFFFFFFF'));
            }

            if ($type === 'pie') {
                $chartShape->getLegend()->setVisible(true);
                $chartShape->getLegend()->getFont()
                           ->setSize(10)
                           ->setColor(new Color('FFFFFFFF')); // Omit setName() to avoid PhpPresentation XML corruption bug
            } else {
                $chartShape->getLegend()->setVisible(false);
            }
            $chartShape->getTitle()->setText('');
        }

        // Bullets on right side
        $this->addBullets(
            $oSlide, $data['bullets'] ?? [],
            6.3, 1.4, 3.4, 3.9,
            $colors['accent'], $colors['text'], 16
        );

        // Slide number
        $this->addText(
            $oSlide, (string)$num,
            self::W_IN - 0.6, 5.35, 0.5, 0.2,
            $colors['subtext'], 9, false, Alignment::HORIZONTAL_RIGHT
        );
    }

    private function buildImageSlide($oSlide, array $data, array $colors, string $keyword, string $layout, int $num): void {
        // Top accent bar
        $this->addRect($oSlide, 0, 0, self::W_IN, 0.07, $colors['accent']);

        $imgW_in  = 4.3;
        $imgX_in  = ($layout === 'image-left') ? 0 : self::W_IN - $imgW_in;
        $textX_in = ($layout === 'image-left') ? $imgW_in + 0.35 : 0.35;
        $textW_in = self::W_IN - $imgW_in - 0.7;

        $imgPath = $this->downloadImage($keyword, 800, 720);
        if ($imgPath) {
            // FIX: clamp image width/height correctly (DrawingFile uses pt internally)
            $this->addImage($oSlide, $imgPath, $imgX_in, 0, $imgW_in, self::H_IN);
        }

        // Divider line
        $divX = ($layout === 'image-left') ? $imgW_in : $imgX_in - 0.05;
        $this->addRect($oSlide, $divX, 0.07, 0.05, self::H_IN - 0.07, $colors['accent']);

        // Title
        $titleSize = $this->getDynamicFontSize($data['title'] ?? '', 42, 35, 24);
        $this->addText(
            $oSlide, $this->cleanText($data['title'] ?? ''),
            $textX_in, 0.15, $textW_in, 1.5,
            $colors['text'], $titleSize, true, Alignment::HORIZONTAL_LEFT
        );

        // Bullets
        $this->addBullets(
            $oSlide, $data['bullets'] ?? [],
            $textX_in, 1.9, $textW_in, 3.5,
            $colors['accent'], $colors['text'], 16
        );

        // Slide number
        $this->addText(
            $oSlide, (string)$num,
            self::W_IN - 0.6, 5.35, 0.5, 0.2,
            $colors['subtext'], 9, false, Alignment::HORIZONTAL_RIGHT
        );
    }

    // =========================================================================
    // SHAPE PRIMITIVES  (all positions/sizes in INCHES — converted internally)
    // =========================================================================

    /**
     * Solid-fill rectangle. x, y, w, h in inches. transparency 0–100.
     */
    private function addRect(
        $oSlide,
        float $x, float $y, float $w, float $h,
        string $hex6,
        int $transparency = 0
    ): void {
        $alpha     = (int) round((1 - max(0, min(100, $transparency)) / 100) * 255);
        $colorCode = strtoupper(str_pad(dechex($alpha), 2, '0', STR_PAD_LEFT))
                   . strtoupper(ltrim($hex6, '#'));

        $shape = $oSlide->createRichTextShape()
            ->setWidth($this->px($w))
            ->setHeight($this->px($h))
            ->setOffsetX($this->px($x))
            ->setOffsetY($this->px($y));
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($colorCode));
        $shape->getBorder()->setLineStyle(Border::LINE_NONE);
        $shape->getActiveParagraph()->createTextRun('');
    }

    /**
     * Text box. x, y, w, h in inches.
     */
    private function addText(
        $oSlide,
        string $text,
        float $x, float $y, float $w, float $h,
        string $hex6,
        int $size = 12,
        bool $bold = false,
        string $hAlign = Alignment::HORIZONTAL_LEFT
    ): void {
        $shape = $oSlide->createRichTextShape()
            ->setWidth($this->px($w))
            ->setHeight($this->px($h))
            ->setOffsetX($this->px($x))
            ->setOffsetY($this->px($y));
        $shape->setAutoFit(\PhpOffice\PhpPresentation\Shape\RichText::AUTOFIT_NORMAL);
        $shape->getFill()->setFillType(Fill::FILL_NONE);
        $shape->getBorder()->setLineStyle(Border::LINE_NONE);
        $shape->getActiveParagraph()->getAlignment()
              ->setHorizontal($hAlign)
              ->setVertical(Alignment::VERTICAL_CENTER);

        $run = $shape->createTextRun($text);
        $run->getFont()
            ->setName('Segoe UI')
            ->setSize($size)
            ->setBold($bold)
            ->setColor(new Color('FF' . strtoupper(ltrim($hex6, '#'))));
    }

    /**
     * Bullet list. x, y, w, h in inches. Arrow marker uses accent colour.
     */
    private function addBullets(
        $oSlide,
        array $bullets,
        float $x, float $y, float $w, float $h,
        string $accentHex,
        string $textHex,
        int $fontSize = 15
    ): void {
        if (empty($bullets)) return;

        // Dynamically shrink font size if there are too many bullets/characters
        $totalChars = array_reduce($bullets, fn($c, $b) => $c + strlen($b), 0);
        if ($totalChars > 160) {
            $ratio = 160 / $totalChars;
            $fontSize = max(11, (int) round($fontSize * $ratio));
        }

        $shape = $oSlide->createRichTextShape()
            ->setWidth($this->px($w))
            ->setHeight($this->px($h))
            ->setOffsetX($this->px($x))
            ->setOffsetY($this->px($y));
        $shape->setAutoFit(\PhpOffice\PhpPresentation\Shape\RichText::AUTOFIT_NORMAL);
        $shape->getFill()->setFillType(Fill::FILL_NONE);
        $shape->getBorder()->setLineStyle(Border::LINE_NONE);

        $accentColor = new Color('FF' . strtoupper(ltrim($accentHex, '#')));
        $textColor   = new Color('FF' . strtoupper(ltrim($textHex,   '#')));

        foreach ($bullets as $i => $bullet) {
            $para = ($i === 0)
                ? $shape->getActiveParagraph()
                : $shape->createParagraph();
            $para->setSpacingBefore(30);
            $para->setSpacingAfter(0);
            $para->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

            $arrow = $para->createTextRun('• ');
            $arrow->getFont()
                  ->setName('Segoe UI')
                  ->setSize($fontSize)
                  ->setBold(true)
                  ->setColor($accentColor);

            $run = $para->createTextRun($this->cleanText($bullet));
            $run->getFont()
                ->setName('Segoe UI')
                ->setSize($fontSize)
                ->setColor($textColor);
        }
    }

    /**
     * Embed an image. x, y in inches (converted to pixels for offset).
     * w, h in inches (converted to POINTS for DrawingFile — this was the main bug).
     *
     * PhpPresentation DrawingFile::setWidth/setHeight expect POINTS (72 pt/inch),
     * not screen pixels (96 px/inch).  Passing pixel values caused images to be
     * 33 % (96/72) too large, overflowing the right edge of the slide.
     */
    private function addImage($oSlide, string $path, float $x, float $y, float $w, float $h): void {
        if (!file_exists($path) || filesize($path) < 1000) return;
        try {
            $shape = new DrawingFile();
            $shape->setPath($path, true);

            // Disable proportional resize so width/height don't mutually overwrite
            $shape->setResizeProportional(false);

            // Positions use PIXELS (same as all other shapes)
            $shape->setOffsetX($this->px($x));
            $shape->setOffsetY($this->px($y));

            // Dimensions use PIXELS as well (PhpPresentation expects pixels and multiplies by 9525 for EMU)
            $shape->setWidth($this->px($w));
            $shape->setHeight($this->px($h));

            $shape->getShadow()->setVisible(false);
            $oSlide->addShape($shape);
        } catch (\Exception $e) {
            error_log('[SlideAI] Image embed failed: ' . $e->getMessage());
        }
    }

    private function setSlideBackground($oSlide, string $hex6): void {
        $bg = new BackgroundColor();
        $bg->setColor(new Color('FF' . strtoupper(ltrim($hex6, '#'))));
        $oSlide->setBackground($bg);
    }

    // =========================================================================
    // IMAGE DOWNLOADER
    // =========================================================================

    private function downloadImage(string $keyword, int $width = 800, int $height = 500): ?string {
        $seed = abs(crc32(trim(strtolower($keyword)))) % 1000;
        $path = sys_get_temp_dir() . '/slideai_' . $seed . '_' . $width . 'x' . $height . '.jpg';

        if (file_exists($path) && filesize($path) > 5000) return $path;

        $url = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT      => 'SlideAI/2.0',
            CURLOPT_ENCODING       => '',
        ]);
        $data        = curl_exec($ch);
        $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($httpCode !== 200 || !is_string($data) || strlen($data) < 5000
            || strpos($contentType, 'image/') !== 0) {
            error_log("[SlideAI] Image download failed: HTTP $httpCode url=$url");
            return null;
        }

        file_put_contents($path, $data);
        return $path;
    }

    // =========================================================================
    // TEXT SANITISER
    // =========================================================================

    private function cleanText($value): string {
        if (!is_string($value)) return '';
        $clean = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $value);
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $clean ?? '');
        return trim($clean ?? '');
    }
}