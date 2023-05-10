<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $userId = \App\Models\User::all()->first()->id;

        \App\Models\Node::factory()->create([
            'type' => 'folder',
            'user_id' => $userId,
        ]);

        $folderId = \App\Models\Node::all()->first()->id;

        \App\Models\Node::factory(5)->create([
            'parent_id' => $folderId,
            'user_id' => $userId,
        ]);

        \App\Models\Node::factory(5)->create([
            'user_id' => $userId,
        ]);
    }
}
