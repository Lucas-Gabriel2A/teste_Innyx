<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'data_validade',
        'imagem',
        'categoria_id',
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

 
public function getImagemUrlAttribute()
{
    return $this->imagem ? asset('storage/' . $this->imagem) : null;
}

}
