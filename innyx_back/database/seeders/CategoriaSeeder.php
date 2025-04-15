<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        Categoria::create(['nome' => 'Bebidas']);
        Categoria::create(['nome' => 'Alimentos']);
        Categoria::create(['nome' => 'Limpeza']);
    }
}
