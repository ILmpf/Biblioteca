<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'password' => [
                'nullable',
                'string',
                'max:255',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'image_path' => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'image_path.image' => 'O ficheiro deve ser uma imagem válida.',
            'image_path.max' => 'A imagem não pode ser maior que 10MB.',
        ];
    }
}