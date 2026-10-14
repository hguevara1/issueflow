<?php

declare(strict_types=1);

namespace App\Model;




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
        private string $id,
        private string $title,
        private string $priority
    ) {

    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getPriority(): string
    {
        return $this->priority;
    }
    
    
    public function isUrgent(): bool
    {
        return $this->priority === 'urgent';
    }

    public static function getAllTickets(): array
    {
        return [
            new Ticket(
                id: 'INC-1001',
                title: 'No puedo iniciar sesión',
                priority: 'urgent',
            ),
            new Ticket(
                id: 'INC-1002',
                title: 'Error en la factura',
                priority: 'high',
            ),
            new Ticket(
                id: 'INC-1003',
                title: 'Actualizar datos de contacto',
                priority: 'normal',
            ),
        ];
    }
}