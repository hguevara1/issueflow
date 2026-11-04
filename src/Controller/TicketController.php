<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Ticket;
use App\Contract\TicketCatalogInterface;
use App\Contract\TicketSlaCalculatorInterface;
use App\Form\Model\TicketCreateData;
use App\Form\TicketType;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


// Mi clase TicketController hereda de AbstractController de Symfony
final class TicketController extends AbstractController
{
    public function __construct(
        private readonly TicketCatalogInterface $ticketCatalog,
        private readonly TicketSlaCalculatorInterface $ticketSlaCalculator,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/tickets', name: 'app_ticket_index', methods: ['GET'])]
    // El método que devuelve la lista de tickets por convención REST es index().
    public function index(Request $request): Response {
        $lastTicketCode = null;
        if ($request->hasPreviousSession()) {
            $lastTicketCode = $request->getSession()->get('last_ticket_code');
        }

        return $this->render('ticket/index.html.twig', 
        [
            'title' => 'Incidencias',
            'tickets' => $this->ticketCatalog->all(),
            'lastTicketCode' => $lastTicketCode
        ]);
        
    }

    #[Route('/tickets/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    #[IsGranted(
        'ROLE_AGENT',
        message: 'Necesitas permisos de agente para crear una incidencia.'
    )]
    public function new(Request $request): Response
    {
        $data = new TicketCreateData();
        /*
        Creamos un formutario con el metodo createFormO heredado de Abstractcontroller 
        ler parametro corresponde a la clase que define el formulario
        2do par^metro corresponde a el objeto (DTO) que recibe la informacion del formulario
        */

        $form = $this->createForm(TicketType::class, $data);

        
        /*
            handleRequest() hace lo siguiente:
            - Detectar el envío del formulario
            - Extraer los datos correspondientes del request
            - Transformar los vlaores al tipo esperado
            - Asigna los datos al objeto TicketCreateData
        */
        
        $form->handleRequest($request);

        // Chequeo si el formulario fue enviado y además si el formulario es válido
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $ticket = $data->mapToTicket();

            $this->entityManager->persist($ticket);
            $this->entityManager->flush();
            /*
                * Un Flash Message vive en la sesion y esta pensado para consumirse una sola vez.
                * Lo podemos usar para confirmar una creacion de una incidencia despues del redirect
            */
            $this->addFlash(
                'success',
                sprintf('Se ha creado la incidencia con código: %s', $ticket->getCode())
            );

                  

            // Luego de guardar el ticket, redirigimos a la pagina de detalle de ticket
            return $this->redirectToRoute('app_ticket_show', [
                'code' => $ticket->getCode()
            ]);
        }
        // Si el formulario no fue enviado o no es vdlido, es un GET, mostramos el formulario 
        return $this->render('ticket/new.html.twig', [
        'form' => $form,
        ]);    
    }

    // Ruta dinámica para mostrar un ticket específico por su ID.
    #[Route('/tickets/{code}', name:"app_ticket_show", methods: ['GET'])]
    // El método que devuelve un ticket en particular por convención REST es show().
    // El parámetro $code identifica la ruta del ticket particular
    public function show(string $code, Request $request): Response {

        // Se inicializa la variable $ticket como null, que contendrá el ticket encontrado.    
        $ticket = null;

        // Se recorre el array de tickets para buscar el ticket con el ID proporcionado.
        $ticket = $this->ticketCatalog->findByCode($code);
        
        if ($ticket === null) {
            // Si no se encuentra el ticket, se lanza una excepción NotFoundHttpException.
            
            $this->logger->warning('Se solicitó una incidencia que no existe: ', [
                'ticket_code' => $code,
                'route' => 'app_ticket_show'
                ]);
            throw $this->createNotFoundException(
                'No existe la incidencia ' . $code
            );
        }
        
        $request->getSession()->set('last_ticket_code', $ticket->getCode());
        
        return $this->render('ticket/show.html.twig', 
        [
            'ticket' => $ticket,
            'responseTargetHours' => $this->ticketSlaCalculator->getResponseTargetHours($ticket)
        ]);

        
    }
}