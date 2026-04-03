<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\Concerns;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

trait HasSectionBlocks
{
    /**
     * @return array<int, Block>
     */
    protected static function sectionBlocks(): array
    {
        return [
            static::heroSectionBlock(),
            static::challengesSectionBlock(),
            static::solutionSectionBlock(),
            static::productShowcaseBlock(),
            static::testimonialsSectionBlock(),
            static::faqSectionBlock(),
            static::ctaSectionBlock(),
            static::leadFormBlock(),
            static::pricingTableBlock(),
            static::iconListSectionBlock(),
            static::countdownTimerBlock(),
            static::eventRegistrationBlock(),
            static::newsletterSignupBlock(),
            static::trustIndicatorsBlock(),
        ];
    }

    protected static function heroSectionBlock(): Block
    {
        return Block::make('hero_section')
            ->preview('filament.content.block-previews.hero-section')
            ->label(__('Hero Section'))
            ->icon('heroicon-o-photo')
            ->schema([
                TextInput::make('badge')->label(__('Badge Text')),
                TextInput::make('title')->label(__('Title'))->required(),
                Textarea::make('subtitle')->label(__('Subtitle')),
                TextInput::make('backgroundImage')->label(__('Background Image URL')),
                Repeater::make('buttons')
                    ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                    ->schema([
                        TextInput::make('text')->required()->live(onBlur: true),
                        TextInput::make('link')->required(),
                        Select::make('style')
                            ->options([
                                'primary' => __('Primary'),
                                'secondary' => __('Secondary'),
                                'outline' => __('Outline'),
                            ])
                            ->default('primary'),
                    ])
                    ->defaultItems(1)
                    ->maxItems(3)
                    ->collapsible(),
                Repeater::make('statistics')
                    ->itemLabel(fn (array $state): ?string => $state['value'] ?? null)
                    ->schema([
                        TextInput::make('value')->required(),
                        TextInput::make('description')->required(),
                    ])
                    ->maxItems(4)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected static function challengesSectionBlock(): Block
    {
        return Block::make('challenges_section')
            ->preview('filament.content.block-previews.challenges-section')
            ->label(__('Challenges Section'))
            ->icon('heroicon-o-exclamation-triangle')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('challenges')
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        TextInput::make('icon')->label(__('Icon (Heroicon name)')),
                        TextInput::make('title')->required(),
                        Textarea::make('description')->required(),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
            ]);
    }

    protected static function solutionSectionBlock(): Block
    {
        return Block::make('solution_section')
            ->preview('filament.content.block-previews.solution-section')
            ->label(__('Solution Section'))
            ->icon('heroicon-o-light-bulb')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('steps')
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        TextInput::make('number')->numeric(),
                        TextInput::make('title')->required(),
                        Textarea::make('description')->required(),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
                Repeater::make('benefits')
                    ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                    ->schema([
                        TextInput::make('text')->required(),
                    ])
                    ->defaultItems(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected static function productShowcaseBlock(): Block
    {
        return Block::make('product_showcase')
            ->preview('filament.content.block-previews.product-showcase')
            ->label(__('Product Showcase'))
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('products')
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->schema([
                        TextInput::make('name')->required(),
                        Textarea::make('description'),
                        TextInput::make('image')->label(__('Image URL')),
                        TextInput::make('link')->label(__('Link URL')),
                        Repeater::make('features')
                            ->schema([
                                TextInput::make('text')->required(),
                            ])
                            ->defaultItems(3)
                            ->collapsible(),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
            ]);
    }

    protected static function testimonialsSectionBlock(): Block
    {
        return Block::make('testimonials_section')
            ->preview('filament.content.block-previews.testimonials-section')
            ->label(__('Testimonials'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('testimonials')
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('role'),
                        Textarea::make('content')->required(),
                        Select::make('rating')
                            ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                            ->default(5),
                        TextInput::make('avatar')->label(__('Avatar URL')),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
            ]);
    }

    protected static function faqSectionBlock(): Block
    {
        return Block::make('faq_section')
            ->preview('filament.content.block-previews.faq-section')
            ->label(__('FAQ Section'))
            ->icon('heroicon-o-question-mark-circle')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('questions')
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                    ->schema([
                        TextInput::make('question')->required(),
                        Textarea::make('answer')->required(),
                    ])
                    ->defaultItems(5)
                    ->collapsible(),
                TextInput::make('ctaText')->label(__('CTA Text')),
                TextInput::make('ctaLink')->label(__('CTA Link')),
            ]);
    }

    protected static function ctaSectionBlock(): Block
    {
        return Block::make('cta_section')
            ->preview('filament.content.block-previews.cta-section')
            ->label(__('CTA Section'))
            ->icon('heroicon-o-megaphone')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                TextInput::make('buttonText')->required()->default(__('Get Started')),
                TextInput::make('buttonLink')->required(),
                Repeater::make('features')
                    ->schema([
                        TextInput::make('text')->required(),
                    ])
                    ->defaultItems(3)
                    ->collapsible()
                    ->collapsed(),
                Section::make(__('Testimonial'))
                    ->schema([
                        Textarea::make('testimonial.content'),
                        TextInput::make('testimonial.name'),
                        TextInput::make('testimonial.role'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected static function leadFormBlock(): Block
    {
        return Block::make('lead_form')
            ->preview('filament.content.block-previews.lead-form')
            ->label(__('Lead Form'))
            ->icon('heroicon-o-envelope')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                TextInput::make('buttonText')->default(__('Submit')),
                TextInput::make('successMessage')->default(__('Thank you! We will get back to you soon.')),
                Repeater::make('fields')
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['name'] ?? null)
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('label')->required()->live(onBlur: true),
                        Select::make('type')
                            ->options([
                                'text' => __('Text'),
                                'email' => __('Email'),
                                'phone' => __('Phone'),
                                'textarea' => __('Textarea'),
                            ])
                            ->default('text'),
                        Toggle::make('required')->default(true),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
            ]);
    }

    protected static function pricingTableBlock(): Block
    {
        return Block::make('pricing_table')
            ->preview('filament.content.block-previews.pricing-table')
            ->label(__('Pricing Table'))
            ->icon('heroicon-o-currency-euro')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('plans')
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('price')->required(),
                        TextInput::make('period')->default(__('/ month')),
                        Toggle::make('isPopular')->label(__('Popular'))->default(false),
                        TextInput::make('buttonText')->default(__('Choose Plan')),
                        TextInput::make('buttonLink'),
                        Repeater::make('features')
                            ->schema([
                                TextInput::make('text')->required(),
                            ])
                            ->defaultItems(4)
                            ->collapsible(),
                    ])
                    ->defaultItems(3)
                    ->collapsible(),
            ]);
    }

    protected static function iconListSectionBlock(): Block
    {
        return Block::make('icon_list_section')
            ->preview('filament.content.block-previews.icon-list-section')
            ->label(__('Icon List'))
            ->icon('heroicon-o-list-bullet')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                Repeater::make('items')
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        TextInput::make('icon')->label(__('Icon (Heroicon name)')),
                        TextInput::make('title')->required(),
                        Textarea::make('description'),
                    ])
                    ->defaultItems(4)
                    ->collapsible(),
            ]);
    }

    protected static function countdownTimerBlock(): Block
    {
        return Block::make('countdown_timer')
            ->preview('filament.content.block-previews.countdown-timer')
            ->label(__('Countdown Timer'))
            ->icon('heroicon-o-clock')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                DateTimePicker::make('targetDate')->required(),
                TextInput::make('buttonText'),
                TextInput::make('buttonLink'),
            ]);
    }

    protected static function eventRegistrationBlock(): Block
    {
        return Block::make('event_registration')
            ->preview('filament.content.block-previews.event-registration')
            ->label(__('Event Registration'))
            ->icon('heroicon-o-calendar-days')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                DateTimePicker::make('eventDate'),
                TextInput::make('eventTime'),
                TextInput::make('eventLocation'),
                TextInput::make('buttonText')->default(__('Register')),
                TextInput::make('successMessage')->default(__('You have been registered successfully!')),
                Repeater::make('fields')
                    ->label(__('Form Fields'))
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['name'] ?? null)
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('label')->required()->live(onBlur: true),
                        Select::make('type')
                            ->options([
                                'text' => __('Text'),
                                'email' => __('Email'),
                                'phone' => __('Phone'),
                            ])
                            ->default('text'),
                        Toggle::make('required')->default(true),
                    ])
                    ->defaultItems(2)
                    ->collapsible(),
            ]);
    }

    protected static function newsletterSignupBlock(): Block
    {
        return Block::make('newsletter_signup')
            ->preview('filament.content.block-previews.newsletter-signup')
            ->label(__('Newsletter Signup'))
            ->icon('heroicon-o-newspaper')
            ->schema([
                TextInput::make('title')->required(),
                Textarea::make('subtitle'),
                TextInput::make('buttonText')->default(__('Subscribe')),
                TextInput::make('successMessage')->default(__('You have been subscribed!')),
                TextInput::make('privacyText'),
            ]);
    }

    protected static function trustIndicatorsBlock(): Block
    {
        return Block::make('trust_indicators')
            ->preview('filament.content.block-previews.trust-indicators')
            ->label(__('Trust Indicators'))
            ->icon('heroicon-o-shield-check')
            ->schema([
                TextInput::make('title'),
                Repeater::make('indicators')
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->schema([
                        TextInput::make('icon')->label(__('Icon (Heroicon name)')),
                        TextInput::make('title')->required(),
                        Textarea::make('description'),
                    ])
                    ->defaultItems(4)
                    ->collapsible(),
            ]);
    }
}
