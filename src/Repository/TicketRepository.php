<?php

declare(strict_types=1);

namespace App\Repository;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use App\Contract\TicketCatalogInterface;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\Model\TicketSearchCriteria;
use Doctrine\ORM\Tools\Pagination\Window;
use Doctrine\ORM\Tools\Pagination\WindowPage;
use Doctrine\ORM\Tools\Pagination\OffsetPaginator;

#[AsAlias(TicketCatalogInterface::class)]
final class TicketRepository extends ServiceEntityRepository implements TicketCatalogInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function all(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    public function findByCode(string $code): ?Ticket
    {
        return $this->findOneBy(['code' => $code]);
    }

    public function search(TicketSearchCriteria $criteria, int $page = 1, int $pageSize): WindowPage
    {
        $queryBuilder = $this->createQueryBuilder('ticket')
            ->leftJoin('ticket.category', 'category')
            ->addSelect('category')
            ->leftJoin('ticket.assignee', 'assignee')
            ->addSelect('assignee')
            ->orderBy('ticket.id', 'DESC');

        $term = $criteria->query ? trim($criteria->query) : '';
        
        if ($term !== '') {
            $queryBuilder
                ->andWhere(
                    '(LOWER(ticket.code) LIKE :term OR LOWER(ticket.title) LIKE :term)'
                )
                ->setParameter('term', '%'.strtolower($term).'%');
        }

        if ($criteria->priority !== null) {
            $queryBuilder
                ->andWhere('ticket.priority = :priority')
                ->setParameter('priority', $criteria->priority->value);
        }

        if ($criteria->status !== null) {
            $queryBuilder
                ->andWhere('ticket.status = :status')
                ->setParameter('status', $criteria->status->value);
        }

        if ($criteria->category !== null) {
            $queryBuilder
                ->andWhere('ticket.category = :category')
                ->setParameter('category', $criteria->category);
        }

        $page = max(1, $page);

        return (new OffsetPaginator(fetchJoinCollection: false))->paginate(
            $queryBuilder,
            Window::fromPageNumberAndSize($page, $pageSize)
        );
    }
}