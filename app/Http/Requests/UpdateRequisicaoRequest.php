<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\RequisicaoEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequisicaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('requisicao'));
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
                Rule::enum(RequisicaoEstado::class),
            ],
            'data_entrega' => ['nullable', 'date', 'after_or_equal:data_requisicao'],
            'data_entrega_prevista' => ['nullable', 'date', 'after_or_equal:data_requisicao'],
            'livros_entregue' => ['nullable', 'array'],
            'livros_entregue.*' => ['exists:livros,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'data_entrega.after_or_equal' => 'A data de entrega não pode ser anterior à data da requisição.',
            'data_entrega_prevista.after_or_equal' => 'A data de entrega prevista não pode ser anterior à data da requisição.',
        ];
    }
}
