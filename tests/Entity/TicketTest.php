<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Entity\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

final class TicketTest extends TestCase
{
    #[Test]
    public function it_normalizes_title_when_the_ticket_is_created(): void
    {
        $ticket = new Ticket(
            code: 'INC-1001',
            title: '   This is a sample ticket title   ',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );


        self::assertSame('This is a sample ticket title', $ticket->getTitle());
        
        
    }
    public static function invalidId(): iterable
    {
        yield 'non_inc_prefix' => ['Ticket0001'];          
        yield 'too_few_digits' => ['INC-101'];             
        yield 'too_many_digits' => ['INC-12345'];          
        yield 'invalid_characters' => ['INC_1001'];        
        yield 'lowercase_prefix' => ['inc-2345'];          
        yield 'empty_string' => [''];        
    }

    #[Test]
    #[DataProvider('invalidId')]
    public function it_rejects_invalid_ticket_ids(
        string $invalidId
        ): void
    {
        $this->expectException(InvalidArgumentException::class);
        

        new Ticket(
            code: $invalidId,
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
            code: 'INC-1001',
            title: '',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );
    }

    #[Test]
    public function it_reports_when_a_ticket_is_urgent(): void
    {
              

        $ticket = new Ticket(
            code: 'INC-1003',
            title: 'Servidor no disponible',
            priority: TicketPriority::Urgent,
            status: TicketStatus::Open
            );
        self::assertTrue($ticket->isUrgent());
    }

}