<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VasilGerginski\MarketingSuite\Services\MailerLiteService;

class SyncSubscriberToMailerLiteJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $fields
     */
    public function __construct(
        public string $groupId,
        public string $email,
        public array $fields = [],
    ) {}

    public function handle(MailerLiteService $mailerLite): void
    {
        $mailerLite->addSubscriberToGroup($this->groupId, $this->email, $this->fields);
    }
}
