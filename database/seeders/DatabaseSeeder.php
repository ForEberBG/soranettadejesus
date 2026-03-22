<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Configuracion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PSpell\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([RoleSeeder::class]);

        User::create([
            'name'=>'Freddy Hilari',
            'email'=>'admin@admin.com',
            'password'=>Hash::make('12345678')
        ])->assignRole('ADMINISTRADOR');

        Configuracion::create([
            'nombre'=>'Sistema Hilari Web',
            'descripcion'=>'Sistema Hilari Web',
            'direccion'=>'Zona Alto Lima 3ra Sección',
            'telefono'=>'59175657007',
            'email'=>'hilariweb@gmail.com',
            'web'=>'https://www.hilariweb.com',
            'moneda'=>'Bs',
            'logo'=>'logos/logo.jpg'
        ]); 

     }
}
