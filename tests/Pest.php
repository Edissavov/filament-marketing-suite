<?php

use VasilGerginski\MarketingSuite\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Run the shipped migration stubs in the same order the installer publishes them.
 */
function runPackageMigrations(): void
{
    $migrations = [
        __DIR__ . '/../vendor/spatie/laravel-settings/database/migrations/create_settings_table.php.stub',
        __DIR__ . '/../database/migrations/create_authors_table.php.stub',
        __DIR__ . '/../database/migrations/create_blog_posts_table.php.stub',
        __DIR__ . '/../database/migrations/create_definitions_table.php.stub',
        __DIR__ . '/../database/migrations/create_faq_items_table.php.stub',
        __DIR__ . '/../database/migrations/create_help_categories_table.php.stub',
        __DIR__ . '/../database/migrations/create_help_articles_table.php.stub',
        __DIR__ . '/../database/migrations/create_help_article_feedback_table.php.stub',
        __DIR__ . '/../database/migrations/create_newsletter_subscribers_table.php.stub',
        __DIR__ . '/../database/migrations/create_events_table.php.stub',
        __DIR__ . '/../database/migrations/create_event_slots_table.php.stub',
        __DIR__ . '/../database/migrations/create_landing_pages_table.php.stub',
        __DIR__ . '/../database/migrations/create_event_submissions_table.php.stub',
        __DIR__ . '/../database/migrations/create_marketing_suite_settings.php.stub',
    ];

    foreach ($migrations as $migration) {
        (require $migration)->up();
    }
}
