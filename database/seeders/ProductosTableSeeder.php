<?php

namespace Database\Seeders;

use App\Helpers\JwtAuth;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla.
        Producto::truncate();
        $faker = \Faker\Factory::create();

        // Crear productos ficticios en la tabla
        for ($i=0; $i < 5; $i++) { 
            Producto::create([
                'descripcion'    => $faker->sentence(4),
                'precio'        => $faker->numberBetween(10, 500),      
                'stock'         => $faker->numberBetween(10, 500),
            ]);
        }
    }
}
