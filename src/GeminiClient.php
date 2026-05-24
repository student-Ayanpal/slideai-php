<?php
namespace SlideAI;

class GeminiClient {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generateSlides($topic, $details = "", $slideCount = 6, $tone = "professional") {
        $prompt = $this->buildPrompt($topic, $details, $slideCount, $tone);
        
        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "response_mime_type" => "application/json"
            ]
        ];

        $ch = curl_init($this->apiUrl . "?key=" . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        
        // Fix for Wamp/Localhost SSL issues
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_error($ch));
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("API Error (HTTP $httpCode): " . $response);
        }

        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $rawText = $result['candidates'][0]['content']['parts'][0]['text'];
            
            // Clean Markdown if AI included it
            $cleanJson = preg_replace('/^```json\s*|\s*```$/i', '', trim($rawText));
            $decoded = json_decode($cleanJson, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON from AI: " . json_last_error_msg());
            }
            
            return $decoded;
        }

        throw new \Exception('AI failed to return valid content parts.');
    }

    private function buildPrompt($topic, $details, $slideCount, $tone) {
        return "You are an expert presentation designer and data analyst. Create a rich, visually structured presentation about '{$topic}'.
Additional Details: {$details}
Slide Count: {$slideCount}
Tone: {$tone}

Return ONLY a valid JSON array of slide objects. Each slide must have ALL of the following fields:

1. \"title\" (string): A concise, impactful slide heading.
2. \"bullets\" (array of 3-5 strings): Key content points for that slide.
3. \"imageKeyword\" (string): A specific, descriptive 2-4 word search phrase for a relevant image (e.g. \"machine learning neural network\", \"team collaboration office\", \"global business growth\"). Be specific and visual.
4. \"layout\" (string): One of: \"title\", \"bullets\", \"image-left\", \"image-right\", \"chart\", \"two-column\". The FIRST slide must always be \"title\". Use \"chart\" only when numeric/statistical data is naturally present for that slide topic.
5. \"chartData\" (object or null): REQUIRED if layout is \"chart\", else null. If chart, provide:
   {
     \"type\": \"bar\" or \"pie\" or \"line\",
     \"labels\": [\"Label1\", \"Label2\", \"Label3\", \"Label4\", \"Label5\"],
     \"values\": [45, 30, 65, 80, 55],
     \"title\": \"Chart Title\"
   }
6. \"theme\" (string): One of: \"purple\", \"blue\", \"teal\", \"orange\". Vary the themes across slides for visual richness.
7. \"icon\" (string): A single relevant emoji for the slide topic (e.g. \"🚀\", \"💡\", \"📊\", \"🌍\").

Example Format:
[
    {
        \"title\": \"The Future of AI\",
        \"bullets\": [\"Transforming industries globally\", \"Enabling smarter decisions\", \"Accelerating innovation\"],
        \"imageKeyword\": \"artificial intelligence technology future\",
        \"layout\": \"title\",
        \"chartData\": null,
        \"theme\": \"purple\",
        \"icon\": \"🤖\"
    },
    {
        \"title\": \"AI Adoption by Industry\",
        \"bullets\": [\"Healthcare leads in AI investment\", \"Finance automates risk analysis\", \"Manufacturing uses predictive maintenance\"],
        \"imageKeyword\": \"industry technology data center\",
        \"layout\": \"chart\",
        \"chartData\": {
            \"type\": \"bar\",
            \"labels\": [\"Healthcare\", \"Finance\", \"Manufacturing\", \"Retail\", \"Education\"],
            \"values\": [85, 78, 65, 55, 42],
            \"title\": \"AI Adoption Rate (%)\"
        },
        \"theme\": \"blue\",
        \"icon\": \"📊\"
    }
]

Return ONLY the JSON array. No extra text.";
    }
}
