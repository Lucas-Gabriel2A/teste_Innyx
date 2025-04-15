<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index()
    {
        return response()->json(Produto::with('categoria')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:50',
            'descricao' => 'required|max:200',
            'preco' => 'required|numeric|min:0',
            'data_validade' => 'required|date|after_or_equal:today',
            'imagem' => 'required|image',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('produtos', 'public');
            $validated['imagem'] = $path;
        }

        $produto = Produto::create($validated);
        return response()->json($produto, 201);
    }

    public function show($id)
    {
        $produto = Produto::with('categoria')->findOrFail($id);
        return response()->json($produto);
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'sometimes|required|max:50',
            'descricao' => 'sometimes|required|max:200',
            'preco' => 'sometimes|required|numeric|min:0',
            'data_validade' => 'sometimes|required|date|after_or_equal:today',
            'imagem' => 'sometimes|image',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
        ]);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $path = $request->file('imagem')->store('produtos', 'public');
            $validated['imagem'] = $path;
        }

        $produto->update($validated);
        return response()->json($produto);
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }
        $produto->delete();
        return response()->json(['message' => 'Produto deletado com sucesso.']);
    }
}
