<?php

use App\Http\Controllers\LivroController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/livros');
//Route::get('/', fn () => view('welcome'));

Route::get('/livros', [LivroController::class, 'index'])->name('livro.index')->middleware('auth');
Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livro.show')->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionsController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [SessionsController::class, 'store'])->middleware('guest');

Route::post('/logout', [SessionsController::class, 'destroy'])->middleware('auth');
