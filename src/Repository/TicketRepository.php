<?php

declare(strict_types=1);

namespace AppXRepository;

use App\Contract\TicketCataloginterface;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TicketRepository extends ServiceEntityRepository implements TicketCataloginterface
{
    
    public function  construct(ManagerRegistry $registry) 
    {
        // Usamos el constructor del padre para registrar la entidad Ticket 
        parent::__construct($registry, Ticket::class);
    }

    public function all(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    // Método que devuelve una incidencia dado su code, si no encuentra devuelve null
    public function findByCode(string $code): ?Ticket
    {
        return $this->findOneBy(['code' => $code]);
    }

}