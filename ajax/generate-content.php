<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use SlideAI\GeminiClient;
use Dotenv\Dotenv;

header('Content-Type: application/json');

try {
    // Load environment variables
    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;
    if (!$apiKey || $apiKey === 'your_api_key_here') {
        throw new \Exception("Gemini API Key not configured in .env file.");
    }

    // Get input
    $topic = $_POST['topic'] ?? '';
    $details = $_POST['details'] ?? '';
    $slideCount = (int)($_POST['slideCount'] ?? 6);
    $tone = $_POST['tone'] ?? 'professional';

    if (empty($topic)) {
        throw new \Exception("Topic is required.");
    }

    $client = new GeminiClient($apiKey);
    $slides = $client->generateSlides($topic, $details, $slideCount, $tone);

    // Store in session for results page
    $_SESSION['generated_slides'] = $slides;
    $_SESSION['generation_topic'] = $topic;

    echo json_encode([
        'success' => true,
        'slides' => $slides
    ]);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
