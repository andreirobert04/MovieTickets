<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/services/AuthService.php';

final class AuthServiceTest extends TestCase
{
    public function testPasswordHashAndVerifyWorks(): void
    {
        $password = 'ParolaMea123!';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('altaParola', $hash));
    }

    public function testLoginMethodExists(): void
    {
        $this->assertTrue(
            method_exists(AuthService::class, 'login'),
            'Metoda login trebuie sa existe in AuthService.'
        );
    }
}
