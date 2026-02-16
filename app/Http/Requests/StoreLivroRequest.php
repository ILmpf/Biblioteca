<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'autores' => ['required', 'array'],
            'autores.*' => ['integer', 'exists:autores,id'],
            'editora_id' => ['required', 'integer', 'exists:editoras,id'],
            'bibliografia' => ['required', 'string'],
            'isbn' => ['required', 'string', 'max:20'],
            'preco' => ['required', 'numeric', 'min:0'],
            'imagem' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
