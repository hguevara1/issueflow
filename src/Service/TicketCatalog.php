<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;

final class TicketCatalog
{
    private array $tickets;

    public function __construct()
    {
        $this->tickets = [
            new Ticket(
                id: 'INC-1001',
                title: 'No puedo iniciar sesión',
                priority: TicketPriority::Urgent,
                status: TicketStatus::Open
            ),
            new Ticket(
                id: 'INC-1002',
                title: 'Error en la factura',
                priority: TicketPriority::High,
                status: TicketStatus::InProgress
            ),
            new Ticket(
                id: 'INC-1003',
                title: 'Actualizar datos de contacto',
                priority: TicketPriority::Normal,
                status: TicketStatus::Closed
            ),
        ];
    }

    public function all(): array
    {
        return $this->tickets;
    }

    public function find(string $id): ?Ticket
    {
        return array_find(
            $this->tickets,
            fn(Ticket $ticket): bool => $ticket->getId() === $id
        );
    }
}