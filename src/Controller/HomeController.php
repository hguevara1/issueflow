<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $projectName = 'IssueFlow';
        $message = 'Symfony ha recibido la petición y ha devuelto una Response.';
        $html = <<<HTML
        <!DOCTYPE html>
            <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>{$projectName}</title>
                </head>
                <body>
                    <main>
                        <h1>{$projectName}</h1>
                        
                    </main>
                </body>
            </html>
        HTML;
        
        return new Response(
            $html,
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }
}

