<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Editora;

class EditoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editoras = Editora::orderBy('nome')->paginate(9);

        return view('editora.index', [
            'editoras' => $editoras,
        ]);
    }

    public function show(Editora $editora)
    {
        return view('editora.show', [
            'editora' => $editora,
        ]);
    }
}
