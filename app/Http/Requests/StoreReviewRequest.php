<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Livro;
use App\RequisicaoEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'cidadão';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'livro_id' => [
                'required',
                'exists:livros,id',
                Rule::unique('reviews')
                    ->where('user_id', $this->user()->id)
                    ->where('requisicao_id', $this->route('requisicao')->id),
            ],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'livro_id.unique' => 'Já submeteste uma review para este livro nesta requisição.',
            'rating.required' => 'A avaliação é obrigatória.',
            'rating.min' => 'A avaliação deve ser entre 1 e 5 estrelas.',
            'rating.max' => 'A avaliação deve ser entre 1 e 5 estrelas.',
            'comentario.required' => 'O comentário é obrigatório.',
            'comentario.min' => 'O comentário deve ter pelo menos 10 caracteres.',
            'comentario.max' => 'O comentário não pode ter mais de 1000 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $requisicao = $this->route('requisicao');

            if ($requisicao->estado !== RequisicaoEstado::COMPLETED) {
                $validator->errors()->add('requisicao', 'Só podes fazer reviews de requisições concluídas.');
            }

            if ($requisicao->user_id !== $this->user()->id) {
                $validator->errors()->add('requisicao', 'Não tens permissão para fazer review desta requisição.');
            }

            $livro = Livro::find($this->livro_id);
            if ($livro) {
                $pivot = $requisicao->livros()->where('livro_id', $livro->id)->first();
                if (! $pivot || ! $pivot->pivot->entregue) {
                    $validator->errors()->add('livro_id', 'Este livro não foi entregue nesta requisição.');
                }
            }
        });
    }
}
