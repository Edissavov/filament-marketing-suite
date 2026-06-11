<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Services;

use Anthropic\Client;
use Anthropic\Messages\RawContentBlockDeltaEvent;
use Anthropic\Messages\RawMessageDeltaEvent;
use Anthropic\Messages\TextDelta;
use Illuminate\Support\Facades\Log;

class LandingPageAiGenerator
{
    public function generate(string $prompt): array
    {
        if (! class_exists(Client::class)) {
            throw new \RuntimeException('The Anthropic SDK is not installed, run: composer require anthropic-ai/sdk');
        }

        // Generating a full landing page can take a few minutes — don't let
        // PHP's execution limit kill the request halfway through.
        set_time_limit(600);

        $client = new Client(apiKey: config('services.anthropic.api_key'));

        $systemPrompt = <<<'SYSTEM'
You are a landing page content generator.
Generate landing page sections as JSON. Each section has a "type" and "data" object.

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
- Return the sections in the required JSON structure, nothing else
- Choose appropriate section types based on the prompt
- Generate realistic, professional content
SYSTEM;

        $systemPrompt .= "\n- CRITICAL: Today is " . now()->format('Y-m-d (l)') . '. ALL dates in the output MUST be after today. Use YYYY-MM-DD format.';

        // Stream the response: a single blocking request of this size would
        // sit on one long read and risk HTTP timeouts, and detailed briefs
        // need far more output headroom than a non-streaming call allows.
        // The JSON schema output format makes the API guarantee parseable,
        // correctly shaped JSON — long responses in non-Latin scripts were
        // prone to invalid JSON when only prompted for it.
        $stream = $client->messages->createStream(
            maxTokens: 64000,
            messages: [
                ['role' => 'user', 'content' => $prompt],
            ],
            model: 'claude-sonnet-4-6',
            outputConfig: [
                'format' => [
                    'type' => 'json_schema',
                    'schema' => $this->sectionsSchema(),
                ],
            ],
            system: $systemPrompt,
            temperature: 0.7,
        );

        $text = '';
        $stopReason = null;

        foreach ($stream as $event) {
            if ($event instanceof RawContentBlockDeltaEvent && $event->delta instanceof TextDelta) {
                $text .= $event->delta->text;
            }

            if ($event instanceof RawMessageDeltaEvent) {
                $stopReason = $event->delta->stopReason;
            }
        }

        if ($stopReason === 'max_tokens') {
            throw new \RuntimeException('The AI response was cut off before completing — try a shorter brief.');
        }

        $decoded = json_decode(trim($text), true);

        // The schema wraps the list in {"sections": [...]}.
        $sections = is_array($decoded) ? ($decoded['sections'] ?? $decoded) : null;

        if (! is_array($sections)) {
            Log::warning('AI landing page response could not be parsed as JSON', [
                'stop_reason' => $stopReason,
                'preview' => mb_substr($text, 0, 500),
            ]);

            throw new \RuntimeException('Failed to parse AI response as JSON');
        }

        return $this->formatSections($sections);
    }

    /**
     * Normalize decoded sections and apply defaults.
     *
     * Returns a list — never an associative array — matching the shape the
     * form Builder stores, so generated pages stay editable in the panel.
     *
     * @param  array<int, mixed>  $sections
     * @return list<array{type: string, data: array<string, mixed>}>
     */
    public function formatSections(array $sections): array
    {
        $formatted = [];

        foreach ($sections as $section) {
            $type = is_array($section) ? ($section['type'] ?? null) : null;
            $data = is_array($section) ? ($section['data'] ?? []) : [];

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

            $formatted[] = [
                'type' => $type,
                'data' => $data,
            ];
        }

        return $formatted;
    }

    /**
     * JSON schema enforced on the model output — every section variant the
     * page builder understands, with all of its data fields.
     *
     * @return array<string, mixed>
     */
    public function sectionsSchema(): array
    {
        $text = ['type' => 'string'];

        $objectList = static fn (array $properties): array => [
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'properties' => $properties,
                'required' => array_keys($properties),
                'additionalProperties' => false,
            ],
        ];

        $section = static fn (string $type, array $properties): array => [
            'type' => 'object',
            'properties' => [
                'type' => ['type' => 'string', 'const' => $type],
                'data' => [
                    'type' => 'object',
                    'properties' => $properties,
                    'required' => array_keys($properties),
                    'additionalProperties' => false,
                ],
            ],
            'required' => ['type', 'data'],
            'additionalProperties' => false,
        ];

        $features = $objectList(['text' => $text]);

        $iconItems = $objectList([
            'icon' => $text,
            'title' => $text,
            'description' => $text,
        ]);

        $formFields = $objectList([
            'name' => $text,
            'label' => $text,
            'type' => ['type' => 'string', 'enum' => ['text', 'email', 'phone', 'textarea']],
            'required' => ['type' => 'boolean'],
        ]);

        return [
            'type' => 'object',
            'properties' => [
                'sections' => [
                    'type' => 'array',
                    'items' => [
                        'anyOf' => [
                            $section('hero_section', [
                                'badge' => $text,
                                'title' => $text,
                                'subtitle' => $text,
                                'buttons' => $objectList([
                                    'text' => $text,
                                    'link' => $text,
                                    'style' => ['type' => 'string', 'enum' => ['primary', 'outline']],
                                ]),
                                'statistics' => $objectList(['value' => $text, 'description' => $text]),
                            ]),
                            $section('challenges_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'challenges' => $iconItems,
                            ]),
                            $section('solution_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'steps' => $objectList(['number' => $text, 'title' => $text, 'description' => $text]),
                                'benefits' => $features,
                            ]),
                            $section('product_showcase', [
                                'title' => $text,
                                'subtitle' => $text,
                                'products' => $objectList([
                                    'name' => $text,
                                    'description' => $text,
                                    'features' => $features,
                                ]),
                            ]),
                            $section('testimonials_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'testimonials' => $objectList([
                                    'name' => $text,
                                    'role' => $text,
                                    'content' => $text,
                                    'rating' => ['type' => 'integer'],
                                ]),
                            ]),
                            $section('faq_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'ctaText' => $text,
                                'ctaLink' => $text,
                                'questions' => $objectList(['question' => $text, 'answer' => $text]),
                            ]),
                            $section('cta_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'buttonText' => $text,
                                'buttonLink' => $text,
                                'features' => $features,
                            ]),
                            $section('lead_form', [
                                'title' => $text,
                                'subtitle' => $text,
                                'buttonText' => $text,
                                'successMessage' => $text,
                                'fields' => $formFields,
                            ]),
                            $section('icon_list_section', [
                                'title' => $text,
                                'subtitle' => $text,
                                'items' => $iconItems,
                            ]),
                            $section('countdown_timer', [
                                'title' => $text,
                                'subtitle' => $text,
                                'targetDate' => $text,
                                'buttonText' => $text,
                                'buttonLink' => $text,
                            ]),
                            $section('newsletter_signup', [
                                'title' => $text,
                                'subtitle' => $text,
                                'buttonText' => $text,
                                'successMessage' => $text,
                                'privacyText' => $text,
                            ]),
                            $section('trust_indicators', [
                                'title' => $text,
                                'indicators' => $iconItems,
                            ]),
                            $section('event_registration', [
                                'title' => $text,
                                'subtitle' => $text,
                                'buttonText' => $text,
                                'successMessage' => $text,
                                'fields' => $formFields,
                            ]),
                            $section('pricing_table', [
                                'title' => $text,
                                'subtitle' => $text,
                                'plans' => $objectList([
                                    'name' => $text,
                                    'price' => $text,
                                    'period' => $text,
                                    'isPopular' => ['type' => 'boolean'],
                                    'buttonText' => $text,
                                    'buttonLink' => $text,
                                    'features' => $features,
                                ]),
                            ]),
                        ],
                    ],
                ],
            ],
            'required' => ['sections'],
            'additionalProperties' => false,
        ];
    }
}
