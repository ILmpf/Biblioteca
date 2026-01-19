<?php

use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;

test('it belongs to an autor', function () {
    $autor = Autor::factory()->create();
    $livro = Livro::factory()->create();

    $livro->autor()->attach($autor);

    expect($livro->autor)->toHaveCount(1);
    expect($livro->autor->first()->id)->toBe($autor->id);
});

test('it belongs to a editora', function () {
    $livro = Livro::factory()->create();

    expect($livro->editora)->tobeInstanceOf(Editora::class);
});

test('it belongs to an autor and editora', function () {
    $livro = Livro::factory()->create();

    $livro->autor()->attach(Autor::factory()->create());

    expect($livro->autor)->toHaveCount(1);
    expect($livro->editora)->tobeInstanceOf(Editora::class);
});

test('it belongs to several autores', function () {
    $livro = Livro::factory()
        ->hasAttached(
            Autor::factory()->count(2),
            [],
            'autor'
        )
        ->create();

    expect($livro->autor)->toHaveCount(2);
});
