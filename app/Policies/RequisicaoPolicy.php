<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Requisicao;
use App\Models\User;
use App\RequisicaoEstado;

class RequisicaoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Requisicao $requisicao): bool
    {
        return $user->role === 'admin' || $requisicao->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $requestedBooks): bool
    {
        // Admins bypass limits
        if ($user->role === 'admin') {
            return true;
        }

        return ($user->activeRentedBooksCount() + $requestedBooks) <= 3;
    }

    public function cancel(User $user, Requisicao $requisicao): bool
    {
        if ($requisicao->estado === RequisicaoEstado::CANCELLED) {
            return false;
        }

        return $user->role === 'admin'
            || $requisicao->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Requisicao $requisicao): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Requisicao $requisicao): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Requisicao $requisicao): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Requisicao $requisicao): bool
    {
        return false;
    }
}
