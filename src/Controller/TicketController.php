<?php

declare(strict_types=1);

namespace App\Controller;


use App\Contract\TicketCatalogInterface;
use App\Contract\TicketSlaCalculatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


// Mi clase TicketController hereda de AbstractController de Symfony
final class TicketController extends AbstractController
{
    public function __construct(
        private readonly TicketCatalogInterface $ticketCatalog,
        private readonly TicketSlaCalculatorInterface $ticketSlaCalculator,
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/tickets', name: 'app_ticket_index', methods: ['GET'])]
    // El método que devuelve la lista de tickets por convención REST es index().
    public function index(): Response {
        return $this->render('ticket/index.html.twig', 
        [
            'title' => 'Incidencias',
            'tickets' => $this->ticketCatalog->all()
        ]);
        
    }

    // Ruta dinámica para mostrar un ticket específico por su ID.
    #[Route('/tickets/{id}', name:"app_ticket_show", methods: ['GET'])]
    // El método que devuelve un ticket en particular por convención REST es show().
    // El parámetro $id identifica la ruta del ticket particular
    public function show(string $id) {

        // Se inicializa la variable $ticket como null, que contendrá el ticket encontrado.    
        $ticket = null;

        // Se recorre el array de tickets para buscar el ticket con el ID proporcionado.
        $ticket = $this->ticketCatalog->find($id);
        
        if ($ticket === null) {
            // Si no se encuentra el ticket, se lanza una excepción NotFoundHttpException.
            
            $this->logger->warning('Se solicitó una incidencia que no existe: ', [
                'ticket_id' => $id,
                'route' => 'app_ticket_show'
                ]);
            throw $this->createNotFoundException(
                'No existe la incidencia ' . $id
            );
        }
        
        return $this->render('ticket/show.html.twig', 
        [
            'ticket' => $ticket,
            'responseTargetHours' => $this->ticketSlaCalculator->getResponseTargetHours($ticket)
        ]);

        
    }
}