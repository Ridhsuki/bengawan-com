<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum FeedbackStatus: string implements HasLabel, HasColor
{
    case NEW = 'new';
    case REVIEWED = 'reviewed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW => 'New',
            self::REVIEWED => 'Reviewed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'danger',
            self::REVIEWED => 'success',
        };
    }
}
