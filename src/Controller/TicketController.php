<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketController
{
    #[Route('/tickets', name: 'app_ticket_index', methods: ['GET'])]
    public function index(): Response
    {
        $tickets = [
            ['id' => 'INC-1001', 'title' => 'No puedo iniciar sesión', 'priority' => 'urgent'],
            ['id' => 'INC-1002', 'title' => 'Error en la factura', 'priority' => 'high'],
            ['id' => 'INC-1003', 'title' => 'Actualizar datos de contacto', 'priority' => 'normal'],
        ];

        $items = '';

        foreach ($tickets as $ticket) {
            // TODO 1: construir un <li> por cada incidencia.
            $items .= "<li>{$ticket['id']}: {$ticket['title']} ({$ticket['priority']})</li>";
        }

        // TODO 2: construir el HTML final con un H1, total, UL y enlace a /.
        $html = "
            <h1>Listado de incidencias</h1>
            <p>Total: " . count($tickets) . "</p>
            <ul>
                $items
            </ul>
            <a href='/'>Volver al inicio</a>";


        // TODO 3: devolver una Response HTTP 200.
        return new Response($html, Response::HTTP_OK);
    }
}
