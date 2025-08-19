<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\MetaData;
use Illuminate\Console\Command;

class SeedMetaData extends Command
{
    protected $signature = 'meta:seed';
    protected $description = 'Seed some example metadata to test the meta system';

    public function handle()
    {
        // Get the first user
        $user = User::first();
        if (!$user) {
            $this->error('No users found in database. Cannot seed metadata.');
            return 1;
        }

        $this->info("Adding metadata to user: {$user->name} (ID: {$user->id})");

        // Create some example metadata
        MetaData::updateOrCreate(
            [
                'parent_type' => User::class,
                'parent_id' => $user->id,
                'key' => 'preferred_language',
            ],
            [
                'value' => 'no',
                'module' => 'system'
            ]
        );

        MetaData::updateOrCreate(
            [
                'parent_type' => User::class,
                'parent_id' => $user->id,
                'key' => 'theme',
            ],
            [
                'value' => 'dark',
                'module' => 'system'
            ]
        );

        $this->info('Metadata added successfully!');
        return 0;
    }
}
