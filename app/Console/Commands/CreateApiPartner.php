<?php

namespace App\Console\Commands;

use App\Models\ApiPartner;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateApiPartner extends Command
{
    protected $signature = 'api:partner:create
                            {name : Partner website name}
                            {--url= : Partner website URL}
                            {--notes= : Internal notes}';

    protected $description = 'Create a new API partner and generate their API key';

    public function handle(): int
    {
        $name = $this->argument('name');
        $key  = Str::random(64);

        $partner = ApiPartner::create([
            'name'        => $name,
            'website_url' => $this->option('url'),
            'api_key'     => $key,
            'notes'       => $this->option('notes'),
            'is_active'   => true,
        ]);

        $this->info("Partner created: {$partner->name} (ID: {$partner->id})");
        $this->newLine();
        $this->line('  <fg=yellow>API Key (share with partner — stored as plaintext):</>');
        $this->line("  <fg=green>{$key}</>");
        $this->newLine();
        $this->line('  Usage:');
        $this->line('    GET  /api/v1/categories      X-API-Key: ' . substr($key, 0, 16) . '...');
        $this->line('    POST /api/v1/news/submit      X-API-Key: ' . substr($key, 0, 16) . '...');
        $this->line('    POST /api/v1/news/generate    X-API-Key: ' . substr($key, 0, 16) . '...');

        return self::SUCCESS;
    }
}
