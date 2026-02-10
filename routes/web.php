<?php

use App\Http\Controllers\EditoraController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\SessionsController;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// PUBLICO
Route::redirect('/', '/livros');
// Route::get('/', fn () => view('welcome'));

// REGISTAR USERS e CONTROLAR SESSÕES
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect('/login')->with('success', 'Conta confirmada com sucesso! Já podes iniciar sessão.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Email de verificação reenviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin/users/create', [RegisteredUserController::class, 'createAdmin'])->name('admin.users.create');
    Route::post('/admin/users', [RegisteredUserController::class, 'store'])->name('register')->name('admin.users.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionsController::class, 'destroy']);
});

// LIVROS
Route::middleware('auth')->group(function () {
    Route::get('/livros', [LivroController::class, 'index'])->name('livro.index');
    Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livro.show');
    Route::post('/livros/import-google', [LivroController::class, 'importGoogle'])->name('livro.import-google');
});

Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::post('/livros', [LivroController::class, 'store'])->name('livro.store');
    Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livro.destroy');
});

// EDITORAS
Route::middleware('auth')->group(function () {
    Route::get('/editoras', [EditoraController::class, 'index'])->name('editora.index');
    Route::get('/editoras/{editora}', [EditoraController::class, 'show'])->name('editora.show');
});

// REQUISIÇÕES
Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicao.index');
Route::get('/requisicoes/create', [RequisicaoController::class, 'create'])->name('requisicao.create');
Route::post('/requisicoes', [RequisicaoController::class, 'store'])->name('requisicao.store');
Route::get('/requisicoes/{requisicao}', [RequisicaoController::class, 'show'])->name('requisicao.show');
Route::patch('/requisicoes/{requisicao}/cancel', [RequisicaoController::class, 'cancel'])
    ->name('requisicao.cancel');
