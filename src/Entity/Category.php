<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;


#[ORM\Entity]
#[ORM\Table(name: 'categories')]
final class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 120, unique: true)]
        private string $name,

                
    ) {
        $normalizedName = trim($name);
        

        if (empty($normalizedName)) {
            throw new InvalidArgumentException(
                'El nombre del categoria no puede estar vacío.'
            );
        }

        $this->name = $normalizedName;
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

}