<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected string $model = 'gpt-4o';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
    }

    /**
     * Rewrite property description using OpenAI
     *
     * @param string $originalDescription
     * @param array $propertyData Additional context (title, location, price, type)
     * @return array ['success' => bool, 'description' => string, 'error' => string|null]
     */
    public function rewriteDescription(string $originalDescription, array $propertyData = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'description' => '',
                'error' => 'OpenAI API key ไม่ได้ตั้งค่าไว้ กรุณาตั้งค่า OPENAI_API_KEY ใน .env'
            ];
        }

        if (empty($originalDescription)) {
            return [
                'success' => false,
                'description' => '',
                'error' => 'ไม่มีคำอธิบายให้ Rewrite'
            ];
        }

        try {
            $prompt = $this->buildPrompt($originalDescription, $propertyData);

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'คุณเป็นนักเขียนเนื้อหาอสังหาริมทรัพย์มืออาชีพที่เชี่ยวชาญในการเขียนคำอธิบายที่ดึงดูดใจและ SEO-friendly'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $rewrittenDescription = $data['choices'][0]['message']['content'] ?? '';

                if (empty($rewrittenDescription)) {
                    return [
                        'success' => false,
                        'description' => '',
                        'error' => 'ไม่ได้รับคำอธิบายจาก AI'
                    ];
                }

                return [
                    'success' => true,
                    'description' => trim($rewrittenDescription),
                    'error' => null
                ];
            } else {
                $errorMessage = $response->json()['error']['message'] ?? 'เกิดข้อผิดพลาดในการเรียก OpenAI API';
                
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);

                return [
                    'success' => false,
                    'description' => '',
                    'error' => $errorMessage
                ];
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'description' => '',
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Build prompt for OpenAI
     */
    protected function buildPrompt(string $originalDescription, array $propertyData): string
    {
        $prompt = "กรุณาเขียนคำอธิบายโครงการอสังหาริมทรัพย์ใหม่ให้ดึงดูดใจ SEO-friendly และน่าอ่าน โดย:\n\n";
        
        $prompt .= "1. ใช้ภาษาไทยที่สละสลวยและเป็นมืออาชีพ\n";
        $prompt .= "2. เน้นจุดเด่นและประโยชน์ที่ผู้ซื้อจะได้รับ\n";
        $prompt .= "3. ใช้คำสำคัญ (Keywords) ที่เกี่ยวข้องกับการค้นหา\n";
        $prompt .= "4. ทำให้เนื้อหาอ่านง่ายและน่าสนใจ\n";
        $prompt .= "5. เก็บข้อมูลสำคัญไว้ครบถ้วน (ราคา, ทำเล, คุณสมบัติ)\n\n";

        if (!empty($propertyData['title'])) {
            $prompt .= "ชื่อโครงการ: {$propertyData['title']}\n";
        }
        if (!empty($propertyData['location'])) {
            $prompt .= "ที่ตั้ง: {$propertyData['location']}\n";
        }
        if (!empty($propertyData['price'])) {
            $prompt .= "ราคา: ฿" . number_format($propertyData['price']) . "\n";
        }
        if (!empty($propertyData['type'])) {
            $typeMap = ['condo' => 'คอนโดมิเนียม', 'house' => 'บ้านเดี่ยว', 'land' => 'ที่ดิน'];
            $prompt .= "ประเภท: " . ($typeMap[$propertyData['type']] ?? $propertyData['type']) . "\n";
        }

        $prompt .= "\nคำอธิบายเดิม:\n{$originalDescription}\n\n";
        $prompt .= "กรุณาเขียนคำอธิบายใหม่:";

        return $prompt;
    }
}

