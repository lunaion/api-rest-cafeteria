<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Vaciar la tabla
        User::truncate();

        $faker = \Faker\Factory::create();

        /**
         * Crear la misma clave para todos los usuarios
         * conviene hacerlo antes del for para que el seeder
         * no se vuelva lento.
         */

        $password = Hash('sha256','123456');

        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => $password,
        ]);
        
        // Generar algunos usuarios para nuestra aplicacion
        /* for ($i=0; $i < 5 ; $i++) { 
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
                ]);
        } */
    }
}
