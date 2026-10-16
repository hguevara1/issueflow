<?php

declare(strict_types=1);

namespace App\Model;

use invalidArgumentException;


final class Ticket
{
    /* 
    Constructor Property Promotion: Esta característica de PHP permite declarar y asignar 
    propiedades de clase directamente en la firma del constructor, eliminando la necesidad 
    de escribir código adicional para inicializar las propiedades.
    En este caso, las propiedades $id, $title y $priority se declaran y se inicializan directamente 
    en el constructor de la clase Ticket. Esto hace que el código sea más conciso y legible.
    */

    public function __construct(
        private readonly string $id,
        private string $title,
        private TicketPriority $priority,
        private TicketStatus $status
    ) {
        if (preg_match('/^INC-\d{4}$/', $this->id) !== 1) {
            throw new InvalidArgumentException(
                'El ID del ticket debe tener el formato INC-XXXX, donde XXXX son cuatro dígitos.'
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
    public function getId(): string
    {
        return $this->id;
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
    
    
    public function isUrgent(): bool
    {
        return $this->priority->isUrgent();
    }

    public function isOpen(): bool
    {
        return $this->status ->isOpen();
    }
   
}