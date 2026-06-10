<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Services;

use Illuminate\Support\Facades\Log;
use MailerLite\MailerLite;
use VasilGerginski\MarketingSuite\Settings\SiteSettings;

class MailerLiteService
{
    private ?MailerLite $client = null;

    public function __construct(
        #[\SensitiveParameter]
        private readonly ?string $apiKey = null,
    ) {}

    private function client(): ?MailerLite
    {
        if ($this->client !== null) {
            return $this->client;
        }

        if (! class_exists(MailerLite::class)) {
            Log::warning('MailerLite SDK is not installed, run: composer require mailerlite/mailerlite-php');

            return null;
        }

        $key = $this->apiKey
            ?? config('services.mailerlite.api_key')
            ?: app(SiteSettings::class)->mailerlite_api_key;

        if (empty($key)) {
            return null;
        }

        $this->client = new MailerLite(['api_key' => $key]);

        return $this->client;
    }

    /**
     * Add a subscriber to a MailerLite group.
     *
     * @param  array<string, mixed>  $fields
     */
    public function addSubscriberToGroup(string $groupId, string $email, array $fields = []): bool
    {
        $client = $this->client();

        if (! $client) {
            Log::warning('MailerLite API key not configured, skipping subscriber sync');

            return false;
        }

        try {
            $subscriberData = [
                'email' => $email,
                'fields' => $fields,
                'groups' => [$groupId],
            ];

            $response = $client->subscribers->create($subscriberData);

            if (isset($response['body']['errors'])) {
                Log::error('MailerLite subscriber creation failed', [
                    'email' => $email,
                    'group_id' => $groupId,
                    'errors' => $response['body']['errors'],
                ]);

                return false;
            }

            Log::info('MailerLite subscriber synced', [
                'email' => $email,
                'group_id' => $groupId,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('MailerLite API error', [
                'email' => $email,
                'group_id' => $groupId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Create a new group in MailerLite.
     */
    public function createGroup(string $name): ?string
    {
        $client = $this->client();

        if (! $client) {
            Log::warning('MailerLite API key not configured, skipping group creation');

            return null;
        }

        try {
            $response = $client->groups->create(['name' => $name]);

            if (isset($response['body']['data']['id'])) {
                $groupId = (string) $response['body']['data']['id'];

                Log::info('MailerLite group created', [
                    'name' => $name,
                    'group_id' => $groupId,
                ]);

                return $groupId;
            }

            Log::error('MailerLite group creation failed', [
                'name' => $name,
                'response' => $response['body'] ?? null,
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('MailerLite API error creating group', [
                'name' => $name,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * List all groups from MailerLite.
     *
     * @return array<int, array{id: string, name: string}>
     */
    public function listGroups(): array
    {
        $client = $this->client();

        if (! $client) {
            return [];
        }

        try {
            $response = $client->groups->get();

            if (! isset($response['body']['data'])) {
                return [];
            }

            return collect($response['body']['data'])
                ->map(static fn (array $group) => [
                    'id' => (string) $group['id'],
                    'name' => $group['name'],
                ])
                ->toArray();
        } catch (\Throwable $e) {
            Log::error('MailerLite API error listing groups', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
