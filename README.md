# Filament Marketing Suite

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vasilgerginski/filament-marketing-suite.svg?style=flat-square)](https://packagist.org/packages/vasilgerginski/filament-marketing-suite)
[![Total Downloads](https://img.shields.io/packagist/dt/vasilgerginski/filament-marketing-suite.svg?style=flat-square)](https://packagist.org/packages/vasilgerginski/filament-marketing-suite)

A complete marketing panel plugin for Filament v5: blog, landing pages with AI generator, events with calendar, FAQ, help center, definitions, newsletter (MailerLite), short URL analytics, and site settings. All features toggleable.

## Features

- **Blog** - Posts, authors, categories, SEO fields
- **Landing Pages** - Builder with 14 section types, AI generation (Anthropic), templates
- **Events** - Calendar widget, consultation slots, capacity management, submission tracking
- **FAQ** - Sortable FAQ items
- **Help Center** - Categories, articles, search, article feedback
- **Definitions** - Glossary/terminology management
- **Newsletter** - Subscriber management, MailerLite integration
- **Short URLs** - Analytics dashboards, UTM tracking, conversion metrics
- **Site Settings** - Social media, contact info, tracking pixels
- **Translations** - BG/EN multi-language support via Spatie Translatable

## Installation

Install via Composer:

```bash
composer require vasilgerginski/filament-marketing-suite
```

Publish and run the migrations:

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan vendor:publish --tag="marketing-suite-migrations"
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --tag="marketing-suite-config"
```

Add the plugin's views to your theme CSS:

```css
@source '../../../../vendor/vasilgerginski/filament-marketing-suite/resources/**/*.blade.php';
```

## Usage

Register the plugin in your panel provider:

```php
use VasilGerginski\MarketingSuite\MarketingSuitePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            MarketingSuitePlugin::make(),
        ]);
}
```

### Feature Toggles

All features are enabled by default. Disable any you don't need:

```php
MarketingSuitePlugin::make()
    ->blog()
    ->landingPages()
    ->events()
    ->faq(false)
    ->helpCenter(false)
    ->definitions()
    ->newsletter()
    ->shortUrls()
    ->siteSettings()
```

### Optional Dependencies

```bash
# AI landing page generation
composer require anthropic-ai/sdk

# MailerLite newsletter integration
composer require mailerlite/mailerlite-php

# Short URL tracking
composer require ashallendesign/short-url

# Sitemap generation
composer require spatie/laravel-sitemap
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Vasil Gerginski](https://github.com/vasilGerginski)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
