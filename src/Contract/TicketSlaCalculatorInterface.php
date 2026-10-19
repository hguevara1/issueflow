<?php

declare(strict_types=1);

namespace App\Contract;

use app\Model\Ticket;


interface TicketSlaCalculatorInterface
{
    public function getResponseTargetHours(Ticket $ticket): int;
}