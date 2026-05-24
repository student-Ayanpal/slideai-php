<?php
session_start();

$slides = $_SESSION['generated_slides'] ?? [
    [
        "title" => "Introduction to Machine Learning",
        "bullets" => ["Understanding the fundamentals of ML", "Key concepts and terminology", "Real-world applications overview"],
        "imageKeyword" => "machine learning artificial intelligence",
        "layout" => "title",
        "chartData" => null,
        "theme" => "purple",
        "icon" => "🤖"
    ],
    [
        "title" => "Types of Machine Learning",
        "bullets" => ["Supervised learning approaches", "Unsupervised learning methods", "Reinforcement learning basics"],
        "imageKeyword" => "data science technology",
        "layout" => "image-right",
        "chartData" => null,
        "theme" => "blue",
        "icon" => "🧠"
    ],
    [
        "title" => "AI Adoption by Industry",
        "bullets" => ["Healthcare leads in AI investment", "Finance automates risk analysis", "Manufacturing uses predictive maintenance"],
        "imageKeyword" => "industry technology",
        "layout" => "chart",
        "chartData" => ["type" => "bar", "labels" => ["Healthcare","Finance","Manufacturing","Retail","Education"], "values" => [85,78,65,55,42], "title" => "AI Adoption Rate (%)"],
        "theme" => "teal",
        "icon" => "📊"
    ]
];

$topic    = $_SESSION['generation_topic'] ?? "AI Presentation";
$pageTitle = "Your Presentation is Ready";

$themeGradients = [
    'purple' => ['from' => '#1E1B4B', 'to' => '#312E81', 'accent' => '#7C3AED', 'light' => '#A78BFA'],
    'blue'   => ['from' => '#0F172A', 'to' => '#1E3A5F', 'accent' => '#3B82F6', 'light' => '#93C5FD'],
    'teal'   => ['from' => '#0D2B2E', 'to' => '#0F3D41', 'accent' => '#14B8A6', 'light' => '#5EEAD4'],
    'orange' => ['from' => '#1C100A', 'to' => '#3D1F0A', 'accent' => '#F97316', 'light' => '#FDBA74'],
];

include 'includes/header.php';
?>

<!-- Confetti -->
<div class="confetti-container" id="confetti">
    <?php for($i=0; $i<25; $i++): ?>
    <div class="confetti" style="left:<?php echo rand(2,98); ?>%;animation-delay:<?php echo ($i*0.18); ?>s;background:<?php echo ['#7C3AED','#3B82F6','#14B8A6','#F97316','#A78BFA','#93C5FD'][rand(0,5)]; ?>"></div>
    <?php endfor; ?>
</div>

<div class="results-page">

    <!-- Success Header -->
    <div class="results-header">
        <div class="success-pill">
            <svg class="check-svg" viewBox="0 0 20 20"><path d="M4 10l4 4L16 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
            Presentation Ready
        </div>
        <h1 class="results-title"><?php echo htmlspecialchars($topic); ?></h1>
        <p class="results-subtitle"><span class="slide-count-badge"><?php echo count($slides); ?> Slides</span> generated with Gemini AI · Images by picsum.photos</p>

        <!-- Action Buttons -->
        <div class="action-row">
            <button class="btn-download" id="downloadBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 16l-4-4h3V4h2v8h3l-4 4z"/><path d="M4 20h16"/></svg>
                Download .pptx
            </button>
            <button class="btn-ghost" onclick="window.location.href='generator.php'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M1 4v6h6M23 20v-6h-6"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                Regenerate
            </button>
            <button class="btn-present" id="presentBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                Present Mode
            </button>
        </div>
    </div><!-- /.results-header -->

    <!-- Slides Grid -->
    <div class="slides-grid" id="slidesGrid">
        <?php foreach($slides as $index => $slide):
            $theme   = $slide['theme']       ?? 'purple';
            $colors  = $themeGradients[$theme] ?? $themeGradients['purple'];
            $layout  = $slide['layout']      ?? 'bullets';
            $icon    = $slide['icon']        ?? '✦';
            $keyword   = $slide['imageKeyword'] ?? $slide['title'] ?? 'business';
            $imgSeed   = abs(crc32($keyword)) % 1000;
            $imgUrl    = "https://picsum.photos/seed/" . $imgSeed . "/800/500";
            $isTitle = ($layout === 'title' || $index === 0);
            $isChart = ($layout === 'chart' && !empty($slide['chartData']));
        ?>

        <?php if($isTitle): ?>
        <!-- ══ HERO / TITLE SLIDE ══ -->
        <div class="slide-card slide-card--hero"
             style="--accent:<?php echo $colors['accent']; ?>;--light:<?php echo $colors['light']; ?>">
            <!-- Full-bleed background image -->
            <img class="hero-bg-img" src="<?php echo $imgUrl; ?>" alt="" loading="lazy" onerror="this.style.opacity=0">
            <!-- Dark gradient overlay -->
            <div class="hero-overlay" style="background:linear-gradient(135deg,<?php echo $colors['from']; ?>ee 0%,<?php echo $colors['to']; ?>cc 100%)"></div>
            <!-- Top accent bar -->
            <div class="slide-accent-bar" style="background:<?php echo $colors['accent']; ?>"></div>
            <!-- Content -->
            <div class="hero-content">
                <div class="hero-slide-badge" style="background:<?php echo $colors['accent']; ?>22;border-color:<?php echo $colors['accent']; ?>55;color:<?php echo $colors['light']; ?>">Slide 1 · Title</div>
                <div class="hero-icon"><?php echo $icon; ?></div>
                <h2 class="hero-title"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></h2>
                <div class="hero-divider" style="background:<?php echo $colors['accent']; ?>"></div>
                <?php if(!empty($slide['bullets']) && is_array($slide['bullets']) && !empty($slide['bullets'][0])): ?>
                <p class="hero-tagline"><?php echo htmlspecialchars($slide['bullets'][0]); ?></p>
                <?php endif; ?>
                <?php if(!empty($slide['bullets']) && is_array($slide['bullets']) && count($slide['bullets']) > 1): ?>
                <div class="hero-tags">
                    <?php foreach(array_slice($slide['bullets'],1,3) as $b): ?>
                    <span class="hero-tag" style="border-color:<?php echo $colors['accent']; ?>44;color:<?php echo $colors['light']; ?>"><?php echo htmlspecialchars($b); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <!-- Watermark -->
            <div class="hero-watermark" style="color:<?php echo $colors['light']; ?>">SlideAI ✦</div>
        </div>

        <?php elseif($isChart): ?>
        <!-- ══ CHART SLIDE ══ -->
        <div class="slide-card"
             style="--accent:<?php echo $colors['accent']; ?>;--light:<?php echo $colors['light']; ?>;background:linear-gradient(145deg,<?php echo $colors['from']; ?> 0%,<?php echo $colors['to']; ?> 100%)">
            <div class="slide-accent-bar" style="background:<?php echo $colors['accent']; ?>"></div>
            <div class="slide-inner">
                <div class="slide-header">
                    <span class="slide-num" style="background:<?php echo $colors['accent']; ?>"><?php echo $index + 1; ?></span>
                    <span class="slide-icon"><?php echo $icon; ?></span>
                    <h3 class="slide-title-text"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></h3>
                </div>
                <div class="slide-divider" style="background:<?php echo $colors['accent']; ?>"></div>
                <div class="chart-preview-wrap">
                    <?php
                    $cd = $slide['chartData'];
                    $cdValues = $cd['values'] ?? [1];
                    $cdLabels = $cd['labels'] ?? ['Data'];
                    $maxVal = max($cdValues) ?: 1;
                    ?>
                    <div class="chart-preview-title"><?php echo htmlspecialchars($cd['title'] ?? ''); ?></div>
                    <?php if($cd['type'] === 'pie'): ?>
                    <div class="pie-preview">
                        <?php
                        $total = array_sum($cdValues) ?: 1;
                        $pieColors = ['#7C3AED','#3B82F6','#14B8A6','#F97316','#EC4899'];
                        $deg = 0; $gradParts = [];
                        foreach($cdValues as $vi => $val) {
                            $pct = ($val/$total)*360;
                            $gradParts[] = $pieColors[$vi%count($pieColors)].' '.round($deg).'deg '.round($deg+$pct).'deg';
                            $deg += $pct;
                        }
                        ?>
                        <div class="pie-chart" style="background:conic-gradient(<?php echo implode(',',$gradParts); ?>)"></div>
                        <div class="pie-legend">
                            <?php foreach($cdLabels as $li=>$label): ?>
                            <div class="pie-legend-item">
                                <span class="pie-dot" style="background:<?php echo $pieColors[$li%count($pieColors)]; ?>"></span>
                                <?php echo htmlspecialchars($label); ?> (<?php echo $cdValues[$li] ?? 0; ?>)
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bar-chart-preview">
                        <?php foreach($cdLabels as $bi=>$label): ?>
                        <div class="bar-item">
                            <div class="bar-track">
                                <div class="bar-fill" style="height:<?php echo round((($cdValues[$bi] ?? 0)/$maxVal)*100); ?>%;background:<?php echo $colors['accent']; ?>"></div>
                            </div>
                            <div class="bar-val"><?php echo $cdValues[$bi] ?? 0; ?></div>
                            <div class="bar-label"><?php echo htmlspecialchars($label); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <ul class="slide-bullets-mini">
                    <?php if(!empty($slide['bullets']) && is_array($slide['bullets'])): foreach(array_slice($slide['bullets'],0,2) as $b): ?>
                    <li style="--dot:<?php echo $colors['accent']; ?>"><?php echo htmlspecialchars($b); ?></li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>
        </div>

        <?php else: ?>
        <!-- ══ STANDARD BULLETS + IMAGE CARD ══ -->
        <div class="slide-card"
             style="--accent:<?php echo $colors['accent']; ?>;--light:<?php echo $colors['light']; ?>;background:linear-gradient(145deg,<?php echo $colors['from']; ?> 0%,<?php echo $colors['to']; ?> 100%)">
            <div class="slide-accent-bar" style="background:<?php echo $colors['accent']; ?>"></div>
            <div class="slide-inner slide-inner--split">
                <!-- Text column -->
                <div class="slide-text-col">
                    <div class="slide-header">
                        <span class="slide-num" style="background:<?php echo $colors['accent']; ?>"><?php echo $index + 1; ?></span>
                        <span class="slide-icon"><?php echo $icon; ?></span>
                        <h3 class="slide-title-text"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></h3>
                    </div>
                    <div class="slide-divider" style="background:<?php echo $colors['accent']; ?>"></div>
                    <ul class="slide-bullets">
                        <?php if(!empty($slide['bullets']) && is_array($slide['bullets'])): foreach($slide['bullets'] as $b): ?>
                        <li>
                            <span class="bullet-arrow" style="color:<?php echo $colors['accent']; ?>">▸</span>
                            <?php echo htmlspecialchars($b); ?>
                        </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
                <!-- Image column -->
                <div class="slide-img-col">
                    <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($slide['imageKeyword'] ?? ''); ?>" loading="lazy" onerror="this.parentElement.style.display='none'">
                    <!-- Gradient fade so image blends into card bg -->
                    <div class="slide-img-fade" style="background:linear-gradient(to right,<?php echo $colors['from']; ?> 0%,transparent 50%)"></div>
                    <!-- Accent border on left edge -->
                    <div class="slide-img-border" style="background:<?php echo $colors['accent']; ?>"></div>
                </div>
            </div><!-- /.slide-inner--split -->
        </div><!-- /.slide-card -->

        <?php endif; ?>

        <?php endforeach; ?>
    </div><!-- /.slides-grid -->

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-icon">🎨</div>
            <div class="stat-val"><?php echo count($slides); ?></div>
            <div class="stat-lbl">Total Slides</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🖼️</div>
            <div class="stat-val"><?php echo count(array_filter($slides, fn($s) => !empty($s['imageKeyword']))); ?></div>
            <div class="stat-lbl">With Images</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-val"><?php echo count(array_filter($slides, fn($s) => ($s['layout'] ?? '') === 'chart')); ?></div>
            <div class="stat-lbl">Charts</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⚡</div>
            <div class="stat-val">Gemini 2.5</div>
            <div class="stat-lbl">AI Engine</div>
        </div>
    </div>
</div><!-- /.results-page -->


<!-- Present Mode Overlay -->
<div class="present-overlay" id="presentOverlay">
    <div class="present-controls">
        <button class="present-nav" id="prevSlide">&#8592;</button>
        <span class="present-counter" id="presentCounter">1 / <?php echo count($slides); ?></span>
        <button class="present-nav" id="nextSlide">&#8594;</button>
        <button class="present-close" id="closePresent">✕ Exit</button>
    </div>
    <div class="present-slides" id="presentSlides">
        <?php foreach($slides as $index => $slide):
            $theme  = $slide['theme'] ?? 'purple';
            $colors = $themeGradients[$theme] ?? $themeGradients['purple'];
            $icon   = $slide['icon'] ?? '✦';
        ?>
        <div class="present-slide <?php echo $index === 0 ? 'active' : ''; ?>"
             style="background:linear-gradient(135deg,<?php echo $colors['from']; ?> 0%,<?php echo $colors['to']; ?> 100%);">
            <div class="ps-accent" style="background:<?php echo $colors['accent']; ?>"></div>
            <div class="ps-icon"><?php echo $icon; ?></div>
            <h2 class="ps-title"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></h2>
            <div class="ps-divider" style="background:<?php echo $colors['accent']; ?>"></div>
            <ul class="ps-bullets">
                <?php if(!empty($slide['bullets']) && is_array($slide['bullets'])): foreach($slide['bullets'] as $b): ?>
                <li style="--c:<?php echo $colors['accent']; ?>"><span>▸</span><?php echo htmlspecialchars($b); ?></li>
                <?php endforeach; endif; ?>
            </ul>
            <div class="ps-footer" style="color:<?php echo $colors['light']; ?>">SlideAI &nbsp;·&nbsp; Slide <?php echo $index+1; ?> of <?php echo count($slides); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* ── Results Page ─────────────────────────────── */
.results-page { max-width:1400px;margin:0 auto;padding:2rem 1.5rem 5rem; }

/* Confetti */
.confetti-container { position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;overflow:hidden; }
.confetti { position:absolute;top:-20px;width:8px;height:8px;border-radius:2px;animation:confFall 4s ease-out forwards;opacity:0; }
@keyframes confFall { 0%{top:-20px;opacity:1;transform:rotate(0)} 80%{opacity:.8} 100%{top:110vh;opacity:0;transform:rotate(720deg)} }

/* ── Page Header ── */
.results-header { text-align:center;padding:2.5rem 0 3rem; }
.success-pill { display:inline-flex;align-items:center;gap:.5rem;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.25);color:#34d399;padding:.5rem 1.25rem;border-radius:50px;font-size:.8125rem;font-weight:600;margin-bottom:1.25rem; }
.check-svg { width:18px;height:18px; }
.results-title { font-size:clamp(1.75rem,4vw,3rem);font-weight:900;letter-spacing:-.03em;margin-bottom:.75rem;background:linear-gradient(135deg,#fff 0%,#c4b5fd 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
.results-subtitle { color:var(--text-secondary);font-size:1rem;margin-bottom:2rem; }
.slide-count-badge { background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.25);color:#a78bfa;padding:.25rem .75rem;border-radius:20px;font-weight:700;font-size:.875rem; }

/* ── Action Buttons ── */
.action-row { display:flex;justify-content:center;gap:1rem;flex-wrap:wrap; }
.btn-download { background:linear-gradient(135deg,#7C3AED,#3B82F6);color:#fff;border:none;padding:.875rem 2rem;border-radius:12px;font-weight:700;font-size:1rem;display:inline-flex;align-items:center;gap:.625rem;cursor:pointer;box-shadow:0 4px 20px rgba(124,58,237,.35);transition:all .3s ease; }
.btn-download:hover { transform:translateY(-3px);box-shadow:0 8px 32px rgba(124,58,237,.5); }
.btn-ghost { background:rgba(255,255,255,.04);border:1px solid var(--border-subtle);color:var(--text-secondary);padding:.875rem 1.5rem;border-radius:12px;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;font-size:.9375rem;font-weight:600;transition:all .3s; }
.btn-ghost:hover { background:rgba(255,255,255,.08);color:#fff;transform:translateY(-2px); }
.btn-present { background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.25);color:#93c5fd;padding:.875rem 1.5rem;border-radius:12px;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;font-size:.9375rem;font-weight:600;transition:all .3s; }
.btn-present:hover { background:rgba(59,130,246,.22);transform:translateY(-2px); }

/* ══ SLIDE GRID ══ */
.slides-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(380px,1fr));
    gap:1.5rem;
    margin-bottom:3rem;
}

/* ── Base Card ── */
.slide-card {
    border-radius:20px;
    border:1px solid rgba(255,255,255,.08);
    overflow:hidden;
    position:relative;
    transition:transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s ease, border-color .35s ease;
    box-shadow:0 8px 32px rgba(0,0,0,.45);
    /* Fixed height keeps all regular cards uniform */
    height:340px;
    display:flex;
    flex-direction:column;
}
.slide-card:hover {
    transform:translateY(-8px) scale(1.012);
    border-color:var(--accent,rgba(124,58,237,.5));
    box-shadow:0 24px 64px rgba(0,0,0,.55),0 0 0 1px var(--accent,rgba(124,58,237,.3));
}
.slide-accent-bar { height:4px;width:100%;flex-shrink:0; }

/* ══ HERO TITLE SLIDE ══ */
.slide-card--hero {
    grid-column:1 / -1;
    height:420px;         /* Taller hero */
    position:relative;
    cursor:default;
}
.hero-bg-img {
    position:absolute;
    inset:0;
    width:100%; height:100%;
    object-fit:cover;
    object-position:center;
    transition:transform .7s ease;
    z-index:0;
}
.slide-card--hero:hover .hero-bg-img { transform:scale(1.04); }
.hero-overlay {
    position:absolute;
    inset:0;
    z-index:1;
}
.slide-card--hero .slide-accent-bar {
    position:absolute;
    top:0; left:0; right:0;
    z-index:3;
}
.hero-content {
    position:relative;
    z-index:2;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    height:100%;
    padding:2.5rem 3rem;
    margin-top:4px; /* offset accent bar */
}
.hero-slide-badge {
    display:inline-block;
    border:1px solid;
    border-radius:30px;
    padding:.3rem .9rem;
    font-size:.7rem;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    margin-bottom:1.25rem;
    backdrop-filter:blur(8px);
}
.hero-icon { font-size:3rem;margin-bottom:.75rem;filter:drop-shadow(0 2px 12px rgba(0,0,0,.4)); }
.hero-title {
    font-size:clamp(1.6rem,3.5vw,2.75rem);
    font-weight:900;
    color:#fff;
    letter-spacing:-.03em;
    margin:0 0 1rem;
    text-shadow:0 2px 20px rgba(0,0,0,.5);
    line-height:1.15;
}
.hero-divider { height:3px;width:80px;border-radius:2px;margin:0 auto 1rem; }
.hero-tagline {
    color:rgba(255,255,255,.8);
    font-size:1rem;
    font-style:italic;
    margin:0 0 1.25rem;
    max-width:600px;
}
.hero-tags { display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center; }
.hero-tag {
    border:1px solid;
    border-radius:20px;
    padding:.25rem .75rem;
    font-size:.75rem;
    font-weight:600;
    backdrop-filter:blur(6px);
    background:rgba(0,0,0,.2);
}
.hero-watermark {
    position:absolute;
    bottom:1rem; right:1.5rem;
    font-size:.75rem;
    font-weight:700;
    letter-spacing:.08em;
    opacity:.45;
    z-index:2;
}

/* ══ STANDARD SLIDE INNER ══ */
.slide-inner {
    flex:1;
    padding:1.25rem 1.5rem 1.25rem;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}
.slide-header { display:flex;align-items:flex-start;gap:.625rem;margin-bottom:.5rem; }
.slide-num {
    min-width:26px; height:26px;
    border-radius:7px;
    display:flex;align-items:center;justify-content:center;
    font-size:.7rem;font-weight:800;color:#fff;
    flex-shrink:0;
    margin-top:2px;
}
.slide-icon { font-size:1.2rem;flex-shrink:0;margin-top:1px; }
.slide-title-text { font-weight:700;font-size:1rem;color:#fff;line-height:1.3;flex:1;margin:0; }
.slide-divider { height:2px;border-radius:2px;margin:.625rem 0;width:55%;flex-shrink:0; }

/* Bullets */
.slide-bullets { list-style:none;padding:0;margin:0;flex:1;overflow:hidden; }
.slide-bullets li {
    display:flex;
    align-items:baseline;
    gap:.5rem;
    color:rgba(255,255,255,.82);
    font-size:.825rem;
    padding:.35rem 0;
    border-bottom:1px solid rgba(255,255,255,.05);
    line-height:1.4;
}
.slide-bullets li:last-child { border-bottom:none; }
.bullet-arrow { font-size:.7rem;flex-shrink:0;margin-top:1px; }

/* ══ SPLIT LAYOUT (text + image side-by-side) ══ */
.slide-inner--split {
    flex-direction:row;
    padding:0;
    gap:0;
    position:relative;
}
.slide-text-col {
    flex:1;
    padding:1.25rem 1rem 1.25rem 1.5rem;
    display:flex;
    flex-direction:column;
    overflow:hidden;
    /* Ensure text column doesn't overlap image */
    z-index:1;
}
.slide-img-col {
    /* Fixed width, absolutely-ish constrained via flex */
    width:42%;
    flex-shrink:0;
    position:relative;
    overflow:hidden;
}
.slide-img-col img {
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    display:block;
    transition:transform .6s ease;
}
.slide-card:hover .slide-img-col img { transform:scale(1.07); }
/* Gradient fade on left edge of image so it blends into card bg */
.slide-img-fade {
    position:absolute;
    inset:0;
    z-index:1;
    pointer-events:none;
}
/* Thin accent line on left edge of image */
.slide-img-border {
    position:absolute;
    top:0; left:0;
    width:2px;
    height:100%;
    z-index:2;
    opacity:.8;
}

/* ══ CHART SLIDE ══ */
.chart-preview-wrap { margin:.25rem 0 .5rem;flex:1; }
.chart-preview-title { font-size:.7rem;font-weight:600;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem; }
.bar-chart-preview { display:flex;align-items:flex-end;gap:.3rem;height:80px; }
.bar-item { display:flex;flex-direction:column;align-items:center;flex:1;gap:.2rem; }
.bar-track { width:100%;flex:1;display:flex;align-items:flex-end;background:rgba(255,255,255,.06);border-radius:4px 4px 0 0;overflow:hidden; }
.bar-fill { width:100%;border-radius:4px 4px 0 0;transition:height .7s cubic-bezier(.4,0,.2,1);min-height:3px; }
.bar-val { font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65); }
.bar-label { font-size:.5rem;color:rgba(255,255,255,.4);text-align:center;line-height:1.2;max-width:36px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }

/* Pie */
.pie-preview { display:flex;align-items:center;gap:1rem; }
.pie-chart { width:72px;height:72px;border-radius:50%;flex-shrink:0;box-shadow:0 2px 12px rgba(0,0,0,.3); }
.pie-legend { display:flex;flex-direction:column;gap:.2rem;overflow:hidden; }
.pie-legend-item { display:flex;align-items:center;gap:.35rem;font-size:.67rem;color:rgba(255,255,255,.72);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.pie-dot { width:7px;height:7px;border-radius:50%;flex-shrink:0; }

/* Mini bullets */
.slide-bullets-mini { list-style:none;padding:0;margin:.5rem 0 0; }
.slide-bullets-mini li { font-size:.78rem;color:rgba(255,255,255,.6);padding:.2rem 0 .2rem 1rem;position:relative;line-height:1.4; }
.slide-bullets-mini li::before { content:'●';position:absolute;left:0;color:var(--dot,#7C3AED);font-size:.45rem;top:.42rem; }

/* ── Stats Row ── */
.stats-row { display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:20px;padding:2rem; }
.stat-item { text-align:center;min-width:120px; }
.stat-icon { font-size:1.75rem;margin-bottom:.5rem; }
.stat-val { font-size:1.5rem;font-weight:900;background:linear-gradient(135deg,#a78bfa,#3b82f6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
.stat-lbl { font-size:.8125rem;color:var(--text-muted);margin-top:.25rem; }

/* ── Present Mode ── */
.present-overlay { position:fixed;inset:0;background:#000;z-index:99999;display:none;flex-direction:column; }
.present-overlay.active { display:flex; }
.present-controls { display:flex;align-items:center;gap:1rem;padding:.75rem 1.5rem;background:rgba(0,0,0,.8);border-bottom:1px solid rgba(255,255,255,.08); }
.present-nav { background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:#fff;width:40px;height:40px;border-radius:10px;font-size:1.25rem;cursor:pointer;transition:all .2s; }
.present-nav:hover { background:rgba(255,255,255,.2); }
.present-counter { color:rgba(255,255,255,.6);font-size:.9375rem;font-weight:600;margin:0 auto; }
.present-close { background:rgba(255,80,80,.15);border:1px solid rgba(255,80,80,.25);color:#fca5a5;padding:.5rem 1rem;border-radius:10px;cursor:pointer;font-weight:600;font-size:.875rem; }
.present-slides { flex:1;position:relative;overflow:hidden; }
.present-slide { position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem;opacity:0;transform:translateX(60px);transition:all .4s cubic-bezier(.4,0,.2,1); }
.present-slide.active { opacity:1;transform:translateX(0); }
.ps-accent { position:absolute;top:0;left:0;right:0;height:6px; }
.ps-icon { font-size:3.5rem;margin-bottom:1rem; }
.ps-title { font-size:clamp(2rem,5vw,4rem);font-weight:900;color:#fff;text-align:center;letter-spacing:-.03em;margin-bottom:1.5rem; }
.ps-divider { height:4px;width:120px;border-radius:2px;margin-bottom:2rem; }
.ps-bullets { list-style:none;padding:0;max-width:800px;width:100%; }
.ps-bullets li { display:flex;align-items:baseline;gap:.75rem;color:rgba(255,255,255,.85);font-size:clamp(.875rem,2vw,1.25rem);padding:.625rem 0;border-bottom:1px solid rgba(255,255,255,.06); }
.ps-bullets li span { color:var(--c,#7C3AED);flex-shrink:0; }
.ps-footer { position:absolute;bottom:1.5rem;font-size:.8125rem;opacity:.4; }
</style>

<?php
ob_start();
?>
<script src="assets/js/results.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Present Mode
    var overlay = document.getElementById("presentOverlay");
    if (overlay) {
        var pSlides = document.querySelectorAll(".present-slide");
        var counter = document.getElementById("presentCounter");
        var cur = 0;

        function showSlide(n) {
            if (!pSlides.length) return;
            pSlides[cur].classList.remove("active");
            cur = (n + pSlides.length) % pSlides.length;
            pSlides[cur].classList.add("active");
            if (counter) counter.textContent = (cur + 1) + " / " + pSlides.length;
        }

        var presentBtn = document.getElementById("presentBtn");
        if (presentBtn) {
            presentBtn.addEventListener("click", function() {
                overlay.classList.add("active");
                document.body.style.overflow = "hidden";
            });
        }

        var closePresent = document.getElementById("closePresent");
        if (closePresent) {
            closePresent.addEventListener("click", function() {
                overlay.classList.remove("active");
                document.body.style.overflow = "";
            });
        }

        var prevSlide = document.getElementById("prevSlide");
        if (prevSlide) {
            prevSlide.addEventListener("click", function() {
                showSlide(cur - 1);
            });
        }

        var nextSlide = document.getElementById("nextSlide");
        if (nextSlide) {
            nextSlide.addEventListener("click", function() {
                showSlide(cur + 1);
            });
        }

        document.addEventListener("keydown", function(e) {
            if (!overlay || !overlay.classList.contains("active")) return;
            if (e.key === "ArrowRight" || e.key === "ArrowDown") showSlide(cur + 1);
            if (e.key === "ArrowLeft"  || e.key === "ArrowUp")   showSlide(cur - 1);
            if (e.key === "Escape") {
                overlay.classList.remove("active");
                document.body.style.overflow = "";
            }
        });
    }

    var bars = document.querySelectorAll(".bar-fill");
    if (bars.length > 0 && window.IntersectionObserver) {
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting && e.target.dataset.h) {
                    e.target.style.height = e.target.dataset.h;
                }
            });
        }, { threshold: 0.3 });

        bars.forEach(function(b) {
            b.dataset.h = b.style.height;
            b.style.height = "0";
            io.observe(b);
        });
    }
});
</script>
<?php
$extraScripts = ob_get_clean();
include 'includes/footer.php';
?>
