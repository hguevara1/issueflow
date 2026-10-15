<?php

declare(strict_types=1);

namespace App\Model;

enum TicketStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Closed = 'closed';

    public function isOpen(): bool
    {
        return $this === self::Open;
    }
}