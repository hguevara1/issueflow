<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\Ticket;


interface TicketCatalogInterface
{
    
    public function all(): array;

    public function findByCode(string $code): ?Ticket;
}