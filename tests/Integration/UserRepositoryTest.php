<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\UserRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private PDO $pdo;
    private UserRepository $repository;

    protected function setUp(): void
    {
        // Conectar a base de datos de prueba SQLite (no requiere servidor)
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear tabla de usuarios
        $this->pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL
            )
        ');

        $this->repository = new UserRepository($this->pdo);
    }

    /**
     * @test
     */
    public function it_creates_a_user(): void
    {
        $user = $this->repository->create('john@example.com', 'password123');

        $this->assertNotNull($user->id);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertNotEmpty($user->password);
    }

    /**
     * @test
     */
    public function it_throws_exception_on_invalid_email(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->repository->create('invalid-email', 'password123');
    }

    /**
     * @test
     */
    public function it_throws_exception_on_short_password(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 6 characters');

        $this->repository->create('john@example.com', 'short');
    }

    /**
     * @test
     */
    public function it_finds_user_by_id(): void
    {
        $created = $this->repository->create('john@example.com', 'password123');
        $found = $this->repository->findById($created->id);

        $this->assertNotNull($found);
        $this->assertEquals('john@example.com', $found->email);
    }

    /**
     * @test
     */
    public function it_returns_null_when_user_not_found(): void
    {
        $found = $this->repository->findById(999);
        $this->assertNull($found);
    }

    /**
     * @test
     */
    public function it_finds_user_by_email(): void
    {
        $this->repository->create('john@example.com', 'password123');
        $found = $this->repository->findByEmail('john@example.com');

        $this->assertNotNull($found);
        $this->assertEquals('john@example.com', $found->email);
    }

    /**
     * @test
     */
    public function it_returns_all_users(): void
    {
        $this->repository->create('user1@example.com', 'password123');
        $this->repository->create('user2@example.com', 'password123');
        $this->repository->create('user3@example.com', 'password123');

        $users = $this->repository->findAll();

        $this->assertCount(3, $users);
    }

    /**
     * @test
     */
    public function it_counts_users(): void
    {
        $this->repository->create('user1@example.com', 'password123');
        $this->repository->create('user2@example.com', 'password123');

        $count = $this->repository->count();

        $this->assertEquals(2, $count);
    }

    /**
     * @test
     */
    public function it_updates_user_email(): void
    {
        $user = $this->repository->create('john@example.com', 'password123');
        $user->email = 'newemail@example.com';
        $this->repository->update($user);

        $updated = $this->repository->findById($user->id);
        $this->assertNotNull($updated);
        $this->assertEquals('newemail@example.com', $updated->email);
    }

    /**
     * @test
     */
    public function it_deletes_user(): void
    {
        $user = $this->repository->create('john@example.com', 'password123');
        $deleted = $this->repository->delete($user->id);

        $this->assertTrue($deleted);
        $this->assertNull($this->repository->findById($user->id));
    }

    /**
     * @test
     */
    public function it_validates_correct_credentials(): void
    {
        $this->repository->create('john@example.com', 'password123');
        $isValid = $this->repository->validateCredentials('john@example.com', 'password123');

        $this->assertTrue($isValid);
    }

    /**
     * @test
     */
    public function it_rejects_incorrect_credentials(): void
    {
        $this->repository->create('john@example.com', 'password123');
        $isValid = $this->repository->validateCredentials('john@example.com', 'wrongpassword');

        $this->assertFalse($isValid);
    }

    /**
     * @test
     */
    public function it_rejects_login_for_nonexistent_user(): void
    {
        $isValid = $this->repository->validateCredentials('nonexistent@example.com', 'password123');

        $this->assertFalse($isValid);
    }
}
