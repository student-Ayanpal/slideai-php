<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SlideAI\PowerPointService;

session_start();

try {
    $slides = $_SESSION['generated_slides'] ?? null;
    $topic = $_SESSION['generation_topic'] ?? "Presentation";

    if (!$slides) {
        throw new Exception("No generated content found in session.");
    }

    $service = new PowerPointService();
    $filename = str_replace(' ', '_', $topic) . ".pptx";
    $filePath = $service->createPresentation($slides, $filename);

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        
        // Delete temporary file
        unlink($filePath);
        exit;
    } else {
        throw new Exception("Failed to create presentation file.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
