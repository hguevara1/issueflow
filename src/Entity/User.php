<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;


#[ORM\Entity]
#[ORM\Table(name: 'user')]
final class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 120)]
        private string $name,

        #[ORM\Column(length: 120, unique: true)]
        private string $email,

        #[ORM\Column(type: Types::DATE_IMMUTABLE)]
        private DateTimeImmutable $createdAt = new DateTimeImmutable()
    ){
        $normalizedName = trim($name);
        $normalizedEmail = strtolower(trim($email));

        if (empty($normalizedName)) {
            throw new InvalidArgumentException(
                'El nombre del usuario no puede estar vacío.'
            );
        }

        if (filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(
                'El email no es válido'
            );
        }
        $this->name = $normalizedName;
        $this->email = $normalizedEmail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}