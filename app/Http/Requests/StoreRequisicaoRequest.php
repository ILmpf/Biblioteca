<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequisicaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'livros' => ['required', 'array', 'min:1', 'max:3'],
            'livros.*' => ['required', 'exists:livros,id'],
        ];
    }
}
