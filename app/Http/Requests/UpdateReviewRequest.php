<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\ReviewEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estado' => [
                'required',
                Rule::in([ReviewEstado::APPROVED->value, ReviewEstado::REJECTED->value]),
            ],
            'justificacao' => [
                'required_if:estado,'.ReviewEstado::REJECTED->value,
                'nullable',
                'string',
                'min:10',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'O estado é obrigatório.',
            'estado.in' => 'Estado inválido.',
            'justificacao.required_if' => 'A justificação é obrigatória para reviews rejeitadas.',
            'justificacao.min' => 'A justificação deve ter pelo menos 10 caracteres.',
            'justificacao.max' => 'A justificação não pode ter mais de 500 caracteres.',
        ];
    }
}
