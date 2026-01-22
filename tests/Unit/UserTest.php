<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_to_array_without_exposing_password(): void
    {
        $createdAt = new \DateTime('2024-01-01 10:00:00');
        $user = new User(id: 1, email: 'john@example.com', password: 'secret', createdAt: $createdAt);

        $data = $user->toArray();

        $this->assertSame(
            [
                'id' => 1,
                'email' => 'john@example.com',
                'created_at' => '2024-01-01 10:00:00',
            ],
            $data
        );
        $this->assertArrayNotHasKey('password', $data);
    }
}
