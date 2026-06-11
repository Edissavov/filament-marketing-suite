<?php

use VasilGerginski\MarketingSuite\Models\Author;
use VasilGerginski\MarketingSuite\Models\BlogPost;
use VasilGerginski\MarketingSuite\Models\Definition;
use VasilGerginski\MarketingSuite\Models\HelpArticle;
use VasilGerginski\MarketingSuite\Models\HelpCategory;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Settings\SiteSettings;

beforeEach(function () {
    runPackageMigrations();
});

it('resolves the package settings class without host configuration', function () {
    $settings = app(SiteSettings::class);

    expect($settings->site_name)->toBe('');
});

it('renders the blog index out of the box', function () {
    $author = Author::create(['name' => 'Jane Doe', 'slug' => 'jane-doe', 'is_active' => true, 'sort_order' => 1]);

    BlogPost::create([
        'title' => ['en' => 'Hello World', 'bg' => 'Здравей свят'],
        'slug' => 'hello-world',
        'content' => ['en' => 'Some content here.', 'bg' => 'Съдържание.'],
        'category' => 'financial',
        'author_id' => $author->id,
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->get('/blog')->assertOk()->assertSee('Hello World');
});

it('renders a blog post with the package layout and blog cards', function () {
    BlogPost::create([
        'title' => ['en' => 'Post A', 'bg' => 'Пост А'],
        'slug' => 'post-a',
        'content' => ['en' => 'Body of post A.', 'bg' => 'Тяло.'],
        'is_published' => true,
        'published_at' => now(),
    ]);

    BlogPost::create([
        'title' => ['en' => 'Post B', 'bg' => 'Пост Б'],
        'slug' => 'post-b',
        'content' => ['en' => 'Body of post B.', 'bg' => 'Тяло.'],
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->get('/blog/post-a')
        ->assertOk()
        ->assertSee('Post A')
        ->assertSee('Post B'); // related post rendered through <x-blog-card>
});

it('renders the help center', function () {
    $category = HelpCategory::create([
        'name' => ['en' => 'Getting Started', 'bg' => 'Начало'],
        'slug' => 'getting-started',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    HelpArticle::create([
        'help_category_id' => $category->id,
        'title' => ['en' => 'First Steps', 'bg' => 'Първи стъпки'],
        'slug' => 'first-steps',
        'content' => ['en' => 'Article body.', 'bg' => 'Текст.'],
        'is_published' => true,
        'sort_order' => 1,
    ]);

    $this->get('/help')->assertOk()->assertSee('Getting Started');
    $this->get('/help/getting-started')->assertOk()->assertSee('First Steps');
    $this->get('/help/search?q=first')->assertOk();
});

it('renders the definitions and authors pages', function () {
    Definition::create([
        'term' => ['en' => 'P2P', 'bg' => 'P2P'],
        'description' => ['en' => 'Peer to peer lending.', 'bg' => 'Описание.'],
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Author::create(['name' => 'Jane Doe', 'slug' => 'jane-doe', 'is_active' => true, 'sort_order' => 1]);

    $this->get('/definitions')->assertOk()->assertSee('P2P');
    $this->get('/our-authors')->assertOk()->assertSee('Jane Doe');
});

it('renders an active landing page with sections', function () {
    LandingPage::create([
        'title' => ['en' => 'Launch', 'bg' => 'Старт'],
        'slug' => 'launch',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
        'sections' => [
            [
                'type' => 'hero_section',
                'data' => [
                    'title' => 'Big Launch',
                    'subtitle' => 'Sub',
                    'buttons' => [['text' => 'Go', 'link' => '#cta', 'style' => 'primary']],
                ],
            ],
        ],
    ]);

    $this->get('/landing/launch')->assertOk()->assertSee('Big Launch');
});
