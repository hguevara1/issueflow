<?php

declare(strict_types=1);

namespace App\Contract;

use app\Model\Ticket;


interface TicketCatalogInterface
{
    
    public function all(): array;

    public function find(string $id): ?Ticket;
}