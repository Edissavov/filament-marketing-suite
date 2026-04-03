<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    |
    | Enable or disable individual marketing features. These can also be
    | toggled when registering the plugin in your panel provider:
    |
    | MarketingSuitePlugin::make()
    |     ->blog()
    |     ->landingPages()
    |     ->events()
    |     ->faq(false)
    |     ->helpCenter(false)
    |     ->definitions()
    |     ->newsletter()
    |     ->shortUrls()
    |     ->siteSettings()
    |
    */
    'features' => [
        'blog' => true,
        'landing_pages' => true,
        'events' => true,
        'faq' => true,
        'help_center' => true,
        'definitions' => true,
        'newsletter' => true,
        'short_urls' => true,
        'site_settings' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Translatable Content
    |--------------------------------------------------------------------------
    */
    'locales' => ['bg', 'en'],

    /*
    |--------------------------------------------------------------------------
    | Blog Settings
    |--------------------------------------------------------------------------
    */
    'blog' => [
        'per_page' => 9,
        'categories' => ['financial', 'other', 'global', 'authorial'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Landing Pages
    |--------------------------------------------------------------------------
    */
    'landing_pages' => [
        'goal_types' => [
            'lead_generation',
            'investor_education',
            'product_launch',
            'event',
            'newsletter',
            'custom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    'events' => [
        'types' => [
            'lead_generation',
            'event_registration',
            'newsletter',
            'consultation',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Short URLs
    |--------------------------------------------------------------------------
    */
    'short_urls' => [
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Newsletter Integration
    |--------------------------------------------------------------------------
    */
    'newsletter' => [
        'provider' => 'mailerlite',
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Generation
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'enabled' => true,
        'provider' => 'anthropic',
    ],
];
