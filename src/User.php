<?php

declare(strict_types=1);

namespace App;

/**
 * Modelo de datos para un usuario
 */
class User
{
    public function __construct(
        public int $id,
        public string $email,
        public string $password,
        public \DateTime $createdAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
