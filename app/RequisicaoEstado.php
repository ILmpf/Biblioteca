<?php

namespace App;

enum RequisicaoEstado: string
{
    case ACTIVE = "active";
    case COMPLETED = "completed";
    case CANCELLED = "cancelled";

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativa',
            self::COMPLETED => 'Terminada',
            self::CANCELLED => 'Cancelada',
        };
    }
}
