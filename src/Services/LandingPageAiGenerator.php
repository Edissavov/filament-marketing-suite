<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Services;

use Anthropic\Client;

class LandingPageAiGenerator
{
    public function generate(string $prompt): array
    {
        $client = new Client(apiKey: config('services.anthropic.api_key'));

        $systemPrompt = <<<'SYSTEM'
You are a landing page content generator.
Generate landing page sections as a JSON array. Each section has a "type" and "data" object.

Available section types and their data fields:
- hero_section: badge, title, subtitle, buttons[{text, link, style:"primary"|"outline"}], statistics[{value, description}]
- challenges_section: title, subtitle, challenges[{icon (leave empty), title, description}]
- solution_section: title, subtitle, steps[{number, title, description}], benefits[{text}]
- product_showcase: title, subtitle, products[{name, description, features[{text}]}]
- testimonials_section: title, subtitle, testimonials[{name, role, content, rating:1-5}]
- faq_section: title, subtitle, ctaText, ctaLink, questions[{question, answer}]
- cta_section: title, subtitle, buttonText, buttonLink, features[{text}]
- lead_form: title, subtitle, buttonText, successMessage, fields[{name, label, type:"text"|"email"|"phone"|"textarea", required:bool}]
- icon_list_section: title, subtitle, items[{icon (leave empty), title, description}]
- countdown_timer: title, subtitle, targetDate, buttonText, buttonLink
- newsletter_signup: title, subtitle, buttonText, successMessage, privacyText
- trust_indicators: title, indicators[{icon (leave empty), title, description}]
- event_registration: title, subtitle, buttonText, successMessage, fields[{name, label, type, required}]
- pricing_table: title, subtitle, plans[{name, price, period, isPopular:bool, buttonText, buttonLink, features[{text}]}]

Rules:
- For in-page anchor links use: #lead-form, #register, #newsletter, #cta
- CRITICAL: Every section MUST have a "type" and "data" object. The "data" object MUST include ALL fields listed above for that type — do not skip any.
- Return ONLY a valid JSON array of sections, no markdown, no explanation
- Choose appropriate section types based on the prompt
- Generate realistic, professional content
- CRITICAL: Today is ' . now()->format('Y-m-d (l)') . '. ALL dates in the output MUST be after today. Use YYYY-MM-DD format.
SYSTEM;

        $response = $client->messages->create(
            maxTokens: 8000,
            messages: [
                ['role' => 'user', 'content' => $prompt],
            ],
            model: 'claude-sonnet-4-6',
            system: $systemPrompt,
            temperature: 0.7,
        );

        $text = '';
        foreach ($response->content as $block) {
            if ($block->type === 'text') {
                $text .= $block->text;
            }
        }

        // Extract JSON from response (handle possible markdown wrapping)
        $text = trim($text);
        if (str_starts_with($text, '```')) {
            $text = preg_replace('/^```(?:json)?\s*/', '', $text);
            $text = preg_replace('/\s*```$/', '', $text);
        }

        $sections = json_decode($text, true);

        if (! is_array($sections)) {
            throw new \RuntimeException('Failed to parse AI response as JSON');
        }

        // Format sections with IDs and ensure required fields have defaults
        $formatted = [];
        foreach ($sections as $section) {
            $type = $section['type'] ?? null;
            $data = $section['data'] ?? [];

            if (! $type) {
                continue;
            }

            // Ensure required fields have defaults
            $data['title'] = $data['title'] ?? '';
            if (in_array($type, ['lead_form', 'event_registration', 'newsletter_signup'])) {
                $data['buttonText'] = $data['buttonText'] ?? __('Submit');
                $data['successMessage'] = $data['successMessage'] ?? __('Thank you!');
                $data['fields'] = $data['fields'] ?? [
                    ['name' => 'name', 'label' => __('marketing-suite::marketing-suite.fields.name'), 'type' => 'text', 'required' => true],
                    ['name' => 'email', 'label' => __('marketing-suite::marketing-suite.fields.email'), 'type' => 'email', 'required' => true],
                ];
            }
            if ($type === 'cta_section') {
                $data['buttonText'] = $data['buttonText'] ?? __('Get Started');
                $data['buttonLink'] = $data['buttonLink'] ?? '#register';
            }

            $id = bin2hex(random_bytes(16));
            $formatted[$id] = [
                'id' => $id,
                'type' => $type,
                'data' => $data,
            ];
        }

        return $formatted;
    }
}
