<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\TicketPriority;
use App\Model\TicketStatus;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use invalidArgumentException;

/**
 * Entity Doctrine de una incidencia de IssueFlow.
 *
 * Sigue siendo un objeto PHP con reglas de dominio. Los atributos #[ORM\...]
 * añaden el mapping necesario para que Doctrine sepa cómo persistirlo.
 */

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name: 'tickets')]
final class Ticket
{
    /**
     * Identificador técnico de base de datos.
     *
     * Empieza en null porque una Entity recién creada todavía no ha sido
     * insertada. Doctrine/la base asignarán el valor al persistirla.
     */

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $assignee = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

    public function __construct(
        #[ORM\Column(length: 8, unique: true)]
        private readonly string $code,

        #[ORM\Column(length: 180)]
        private string $title,

        #[ORM\Column(length: 20, enumType: TicketPriority::class)]
        private TicketPriority $priority,

        #[ORM\Column(length: 20, enumType: TicketStatus::class)]
        private TicketStatus $status = TicketStatus::Open


        
    ) {
        if (preg_match('/^INC-\d{4}$/', $this->code) !== 1) {
            throw new InvalidArgumentException(
                'El code del ticket debe tener el formato INC-XXXX, donde XXXX son cuatro dígitos.'
            );
        }

        $normalizedTitle = trim($title);
        if (empty($normalizedTitle)) {
            throw new InvalidArgumentException(
                'El título del ticket no puede estar vacío.'
            );
        }
        $this->title = $normalizedTitle;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCode(): string
    {
        return $this->code;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getPriority(): TicketPriority
    {
        return $this->priority;
    }
    public function getStatus(): TicketStatus
    {
        return $this->status;
    }
    
    public function getAssignee(): ?User
    {
        return $this->assignee;
    }
    
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function assignTo(?User $assignee): void
    {
        $this->assignee = $assignee;
    }
    
    public function isUrgent(): bool
    {
        return $this->priority->isUrgent();
    }

    public function isOpen(): bool
    {
        return $this->status ->isOpen();
    }


   
}