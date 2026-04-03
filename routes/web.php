<?php

use Illuminate\Support\Facades\Route;
use VasilGerginski\MarketingSuite\Livewire;

Route::middleware('web')->group(function () {
    // Blog
    Route::get('blog', Livewire\Pages\Blog::class)->name('marketing-suite.blog');
    Route::get('blog/{post:slug}', Livewire\Blog\Show::class)->name('marketing-suite.blog.show');

    // Help Center
    Route::get('help', Livewire\HelpCenter::class)->name('marketing-suite.help');
    Route::get('help/search', Livewire\HelpCenter\Search::class)->name('marketing-suite.help.search');
    Route::get('help/{category:slug}', Livewire\HelpCenter::class)->name('marketing-suite.help.category');
    Route::get('help/{category:slug}/{article:slug}', Livewire\HelpCenter::class)->name('marketing-suite.help.article');

    // Landing Pages
    Route::get('landing/{slug}', Livewire\LandingPage::class)->name('marketing-suite.landing');

    // Content Pages
    Route::get('our-authors', Livewire\Pages\OurAuthors::class)->name('marketing-suite.authors');
    Route::get('definitions', Livewire\Pages\Definicii::class)->name('marketing-suite.definitions');
});
