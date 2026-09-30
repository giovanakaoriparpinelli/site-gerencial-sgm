<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $senhaTemporaria = 'sgm-trocar-2026';

        $contas = [
            ['name' => 'Mauro', 'email' => 'maurojrparpinelli.adv@gmail.com'],
            ['name' => 'Moacir', 'email' => 'moacirparpinelli@gmail.com'],
            // E-mail confirmado em 29/09/2026.
            ['name' => 'Giovana', 'email' => 'giovanaparpinelli@sgmempresarial.com.br'],
        ];

        foreach ($contas as $conta) {
            User::updateOrCreate(
                ['email' => $conta['email']],
                ['name' => $conta['name'], 'password' => Hash::make($senhaTemporaria)]
            );
        }

        $this->call(DocumentImportSeeder::class);
    }
}
