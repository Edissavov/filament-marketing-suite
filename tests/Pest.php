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
        __DIR__ . '/../database/migrations/make_event_submissions_event_id_nullable.php.stub',
    ];

    foreach ($migrations as $migration) {
        (require $migration)->up();
    }
}

/**
 * Run the short-url vendor migrations (classic named-class migrations).
 */
function runShortUrlMigrations(): void
{
    $base = __DIR__ . '/../vendor/ashallendesign/short-url/database/migrations';

    $classMigrations = [
        '2019_12_22_015115_create_short_urls_table.php' => 'CreateShortUrlsTable',
        '2019_12_22_015214_create_short_url_visits_table.php' => 'CreateShortUrlVisitsTable',
        '2020_02_11_224848_update_short_url_table_for_version_two_zero_zero.php' => 'UpdateShortURLTableForVersionTwoZeroZero',
        '2020_02_12_008432_update_short_url_visits_table_for_version_two_zero_zero.php' => 'UpdateShortURLVisitsTableForVersionTwoZeroZero',
        '2020_04_10_224546_update_short_url_table_for_version_three_zero_zero.php' => 'UpdateShortURLTableForVersionThreeZeroZero',
        '2020_04_20_009283_update_short_url_table_add_option_to_forward_query_params.php' => 'UpdateShortUrlTableAddOptionToForwardQueryParams',
    ];

    foreach ($classMigrations as $file => $class) {
        require_once "{$base}/{$file}";
        (new $class)->up();
    }

    (require __DIR__ . '/../vendor/vasilgerginski/filament-short-url/database/migrations/2025_12_12_000000_add_marketing_fields_to_short_urls_table.php')->up();
}
