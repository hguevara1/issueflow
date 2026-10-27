<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;


final class TicketCommands 
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TicketRepository $ticketRepository,
    )
    {   
    }
    #[AsCommand(name: 'app:ticket:seed', description: 'Carga de tickets iniciales en BD')]
    public function seed(OutputInterface $output): int
    {
        $tickets = [
            ['INC-1001', 'No puedo iniciar sesión', TicketPriority::Urgent, TicketStatus::Open],
            ['INC-1002', 'Error en la factura', TicketPriority::High, TicketStatus::InProgress],
            ['INC-1003', 'Actualizar datos de contacto', TicketPriority::Normal, TicketStatus::Closed],
        ];

        $createdTickets = [];

        
        foreach ($tickets as [$code, $title, $priority, $status]) {
            if ($this->ticketRepository->findByCode($code) !== null )
                {
                    $output->writeln(sprintf('Incidencia %s ya existe, se omite', $code));
                    continue;
                }
            $ticket = new Ticket(
                code: $code,
                title: $title,
                priority: $priority,
                status: $status,
            );
            
            $this->entityManager->persist($ticket);
            $createdTickets[] = $ticket;
            $output->writeln(sprintf('Incidencia %s preparada para persisitir', $code));
        }
        $this->entityManager->flush();

        foreach ($createdTickets as $ticket)
            {
                $output->writeln(sprintf(
                    'Incidencia %s persitida con id %d',
                    $ticket->getCode(),
                    $ticket->getId()
                ));
            }
            return Command::SUCCESS;
    }
}