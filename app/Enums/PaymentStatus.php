<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::SUCCESSFUL => 'successful',
            self::FAILED => 'failed',
        };
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
