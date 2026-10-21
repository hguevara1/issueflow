<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Model\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use App\Service\InMemoryTicketCatalog;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryTicketCatalogTest extends TestCase
{
    public static function findIdProviderExist(): iterable
    {
        yield 'INC-1001' => ['INC-1001'];
        yield 'INC-1002' => ['INC-1002'];
        yield 'INC-1003' => ['INC-1003'];
        
    }

    
    #[Test]
    #[DataProvider('findIdProviderExist')]
    public function it_finds_tickets_by_id_yes_exist(string $id): void
    {
        $catalog = new InMemoryTicketCatalog();
        $ticket = $catalog->find($id);

        self::assertSame($id, $ticket->getId());
        
    }

    public static function findIdProviderNotExist(): iterable
    {
        yield 'INC-1004' => ['INC-1004'];
        yield 'INC-1005' => ['INC-1005'];
        yield 'INC-1006' => ['INC-1006'];
        
    }

    
    #[Test]
    #[DataProvider('findIdProviderNotExist')]
    public function it_finds_tickets_by_id_not_exist  (string $id): void
    {
        $catalog = new InMemoryTicketCatalog();
        $ticket = $catalog->find($id);

        self::assertNull($ticket);
        
    }

    #[Test]
    public function it_returns_all_tickets(): void
    {
        $catalog = new InMemoryTicketCatalog();
        $tickets = $catalog->all();

        self::assertCount(3, $tickets);
        self::assertContainsOnlyInstancesOf(Ticket::class, $tickets);
    }
}
