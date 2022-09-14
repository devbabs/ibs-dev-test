<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = "ibs-dev@email.com";

        if (User::whereEmail($email)->count() == 0) {
            User::factory()->create([
                'email' => $email
            ]);
        }
    }
}
