<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Model\TicketSearchCriteria;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use App\Entity\Ticket;
use App\Entity\Category;
use App\Repository\TicketRepository;
use App\Service\TicketSlaCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


final class ApiTicketController extends abstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TicketRepository $ticketRepository,
        private readonly TicketSlaCalculator $ticketSlaCalculator
    ) {}
    
    #[Route('/api/tickets', name: 'app_api_ticket_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $criteria = new TicketSearchCriteria();
        $criteria->query = trim($request->query->getstring('query'));

        $priorityValue = trim($request->query->getstring('priority'));
        if ($priorityValue !== '') {
            $priority = TicketPriority::tryFrom($priorityValue);

            if ($priority === null) {
                return $this->json(
                    [
                    'error' => [
                    'code¡' => 'invalid_priority',
                    'message' => 'La prioridad proporcionada no es válida.',
                    ],
                ], Response::HTTP_BAD_REQUEST);
            }
            $criteria->priority = $priority;
        }

        $statusValue = trim($request->query->getstring('status'));
        if ($statusValue !== '') {
            $status = TicketStatus::tryFrom($statusValue);

            if ($status === null) {
                return $this->json(
                    [
                    'error' => [
                    'code¡' => 'invalid_status',
                    'message' => 'El estado proporcionado no es válido.',
                    ],
                ], Response::HTTP_BAD_REQUEST);
            }
            $criteria->status = $status;
        }

        $categoryValue = trim($request->query->getstring('category'));
        
        if ($categoryValue !== '') 
        {
            if (filter_var($categoryValue, FILTER_VALIDATE_INT) === false || (int)$categoryValue < 1){
                return $this->json(
                    [
                    'error' => [
                    'code¡' => 'invalid_category',
                    'message' => 'La categoría proporcionada no es válida.',
                    ],
                ], Response::HTTP_BAD_REQUEST);
            }
            $category = $this->entityManager
                ->getRepository(Category::class)
                ->find((int) $categoryValue);

            if ($category === null) {
                return $this->json(
                    [
                    'error' => [
                    'code¡' => 'invalid_category',
                    'message' => 'La categoría proporcionada no existe.',
                    ],
                ], Response::HTTP_BAD_REQUEST);
            }
        
            $criteria->category = $category;
        }

        $page = max(1, (int)$request->query->getInt('page', 1));

        $ticketPage = $this->ticketRepository->search(
            criteria: $criteria,
            page: $page,
            pageSize: 10
        );

        $items = array_map(
            fn (Ticket $ticket): array => $this->ticketToArray($ticket),
            $ticketPage->getItems() 
            );

        return $this->json([
            'tickets' => $items]);
            
    }

    #[Route('/api/tickets/{code}', name: 'app_api_ticket_show', methods: ['GET'])]
    public function show(string $code): JsonResponse
    {
        if (preg_match('/^INC-\d{4}$/', $code) !== 1) {
            return $this->json(
                [
                'error' => [
                'code¡' => 'invalid_ticket_code',
                'message' => 'El código del ticket debe tener el formato INC-XXXX.',
                ],
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $ticket = $this->ticketRepository->findByCode($code);

        if ($ticket === null) {
            return $this->json(
                [
                'error' => [
                'code¡' => 'ticket_not_found',
                'message' => sprintf('No se encontró ningún ticket con el código %s.', $code)
                ],
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $this->ticketToArray($ticket),
            'meta' => [
                'response_target' => $this->ticketSlaCalculator->getResponseTargetHours($ticket),
                ],
            ]);
    }

    private function ticketToArray(Ticket $ticket): array
    {
        return [
            'code' => $ticket->getCode(),
            'title' => $ticket->getTitle(),
            'priority' => $ticket->getPriority()->value,
            'status' => $ticket->getStatus()->value,
            'category' => $ticket->getCategory() === null ? null : $ticket->getCategory()->getName(),
            'assignee' => $ticket->getAssignee() === null ? null : [
                
                'id' => $ticket->getAssignee()->getId(),
                'email' => $ticket->getAssignee()->getEmail(),
            ],
        ];
    }
}