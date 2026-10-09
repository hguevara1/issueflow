<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    
    // #[Template('home/index.html.twig')]
    public function index(): Response
    {
        return  $this->render('home/index.html.twig', 
        [
            'projectName' => 'IssueFlow',
            'message' => 'Symfony 8.1 ha resuelto la ruta y Twig ha construido la vista'
        ]);
    }

    #[Route('/health', name: 'app_health', methods: ['GET'])]
    public function health(): Response
    {
        return new Response(
            'IssueFlow Ok',
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain']
        );
    }

}
