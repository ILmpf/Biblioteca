<?php

use App\Http\Controllers\EditoraController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\SessionsController;
use App\Models\Livro;
use App\Models\User;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PUBLICO
Route::get('/', function () {
    $featuredBooks = Livro::with(['autor', 'editora'])
        ->disponivel()
        ->latest()
        ->take(6)
        ->get();
    
    $stats = [
        'total_books' => Livro::count(),
        'available_books' => Livro::disponivel()->count(),
        'total_authors' => \App\Models\Autor::count(),
    ];
    
    return view('welcome', [
        'featuredBooks' => $featuredBooks,
        'stats' => $stats,
    ]);
})->name('home');

// REGISTAR USERS e CONTROLAR SESSÕES
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::get('/email/verify', fn () => view('auth.verify-email'))->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
        
        $user->notify(new \App\Notifications\EmailVerified());
    }

    return redirect('/login')->with('success', 'Conta confirmada com sucesso! Já podes iniciar sessão.');
})->middleware(['signed'])->name('verification.verify');

//CONFIRMAR CONTA
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email de verificação reenviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin/users/create', [RegisteredUserController::class, 'createAdmin'])->name('admin.users.create');
    Route::post('/admin/users', [RegisteredUserController::class, 'store'])->name('admin.users.store');
});

// PERFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionsController::class, 'destroy']);
    // LIVROS
    Route::post('/livros/import-google', [LivroController::class, 'importGoogle'])->name('livro.import-google');
    Route::resource('livros', LivroController::class)
        ->except(['create', 'edit'])
        ->names([
            'index' => 'livro.index',
            'show' => 'livro.show',
            'store' => 'livro.store',
            'update' => 'livro.update',
            'destroy' => 'livro.destroy',
        ]);
    // EDITORAS
    Route::resource('editoras', EditoraController::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'editora.index',
            'show' => 'editora.show',
        ]);
});

// REQUISIÇÕES
Route::resource('requisicoes', RequisicaoController::class)
    ->middleware(['auth', 'verified'])
    ->parameters([
        'requisicoes' => 'requisicao'
    ])
    ->only(['index', 'create', 'store', 'show'])
    ->names([
        'index' => 'requisicao.index',
        'create' => 'requisicao.create',
        'store' => 'requisicao.store',
        'show' => 'requisicao.show',
    ]);
Route::patch('/requisicoes/{requisicao}/cancel', [RequisicaoController::class, 'cancel'])
    ->name('requisicao.cancel');
