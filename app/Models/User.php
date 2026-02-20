<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\RequisicaoEstado;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Requisicao[] $requisicoes
 * @property-read \Illuminate\Database\Eloquent\Collection|Review[] $reviews
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Requisicoes relationship.
     */
    public function requisicoes(): HasMany
    {
        return $this->hasMany(Requisicao::class);
    }

    /**
     * Reviews relationship.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Availability alerts relationship.
     */
    public function availabilityAlerts(): HasMany
    {
        return $this->hasMany(AlertaDisponibilidadeLivro::class);
    }

    // HELPERS
    public function activeRentedBooksCount(): int
    {
        return $this->requisicoes()
            ->where('estado', RequisicaoEstado::ACTIVE->value)
            ->get()
            ->sum(fn ($requisicao) => $requisicao->livros->where('pivot.entregue', 0)->count());
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
    }
}
