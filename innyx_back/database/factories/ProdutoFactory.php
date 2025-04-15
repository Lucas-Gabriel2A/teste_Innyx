<?php

namespace Database\Factories;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(6),
            'preco' => $this->faker->randomFloat(2, 5, 100),
            'data_validade' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'imagem' => $this->faker->image('public/storage/produtos', 640, 480, null, false),
            'categoria_id' => Categoria::inRandomOrder()->first()->id ?? Categoria::factory(),
        ];
    }
}
