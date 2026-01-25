<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use Illuminate\Http\Request;

class EditoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editoras = Editora::orderBy('nome')->paginate(9);

        return view('editora.index',[
            'editoras' => $editoras,
        ]);
    }

    public function show(Editora $editora)
    {
        return view('editora.show',[
            'editora' => $editora,
        ]);
    }
}
