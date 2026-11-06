<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\Ticket;
use App\Form\Model\TicketSearchCriteria;
use Doctrine\ORM\Tools\Pagination\WindowPage;


interface TicketCatalogInterface
{
    
    public function all(): array;

    public function findByCode(string $code): ?Ticket;

    public function search(TicketSearchCriteria $criteria, int $page, int $pageSize): WindowPage;
}