<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\Categoria;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        
        if (Categoria::count() === 0) {
            $this->call(CategoriaSeeder::class);
        }

       
        Produto::factory()->count(20)->create();
    }
}
