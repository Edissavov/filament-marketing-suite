<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|min:2', as: 'име')]
    public string $name = '';

    #[Validate('required|email', as: 'имейл')]
    public string $email = '';

    #[Validate('required', as: 'категория')]
    public string $category = '';

    #[Validate('required|min:10', as: 'съобщение')]
    public string $message = '';

    public bool $submitted = false;

    public function submit(): void
    {
        $this->validate();

        $this->submitted = true;
        $this->reset(['name', 'email', 'category', 'message']);
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.contact-form');
    }
}
