<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use InvalidArgumentException;

final class TicketTest extends TestCase
{
    #[Test]
    public function it_normalizes_title_when_the_ticket_is_created(): void
    {
        $ticket = new Ticket(
            id: 'INC-1001',
            title: '   This is a sample ticket title   ',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );


        self::assertSame('This is a sample ticket title', $ticket->getTitle());
        
        
    }

    #[Test]
    public function it_rejects_invalid_ticket_ids(): void
    {
        $this->expectException(InvalidArgumentException::class);
        

        new Ticket(
            id: 'Ticket001',
            title: 'Incidencia válida',
            priority: TicketPriority::Low,
            status: TicketStatus::InProgress
        );
    }

    #[Test]
    public function it_rejects_an_empty_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        

        new Ticket(
            id: 'INC-1001',
            title: '',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );
    }

    #[Test]
    public function it_reports_when_a_ticket_is_urgent(): void
    {
              

        $ticket = new Ticket(
            id: 'INC-1003',
            title: 'Servidor no disponible',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );
        self::assertTrue($ticket->isUrgent());
    }

}