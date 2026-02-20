<?php

declare(strict_types=1);

namespace App;

enum ReviewEstado: string
{
    case IN_APPROVAL = 'in_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::IN_APPROVAL => 'Aguarda aprovação',
            self::APPROVED => 'Aprovada',
            self::REJECTED => 'Recusada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN_APPROVAL => 'yellow',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
        };
    }
}
