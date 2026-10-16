<?php

declare(strict_types=1);

namespace App\Model;

enum TicketPriority: string
{
    case Urgent = 'urgent';
    case High = 'high';
    case Normal = 'normal';
    case Low = 'low';

    public function getLabel(): string
    {
        return match ($this) {
            self::Urgent => 'Urgente',
            self::High => 'Alta',
            self::Normal => 'Normal',
            self::Low => 'Baja',
        };
    }

    public function isUrgent(): bool
    {
        return $this === self::Urgent;
    }
}