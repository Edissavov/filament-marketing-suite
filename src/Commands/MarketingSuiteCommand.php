<?php

namespace VasilGerginski\MarketingSuite\Commands;

use Illuminate\Console\Command;

class MarketingSuiteCommand extends Command
{
    public $signature = 'marketing-suite';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
