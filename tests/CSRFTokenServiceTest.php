<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/services/CSRFTokenService.php';

final class CSRFTokenServiceTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function testGenerateTokenCreatesValue(): void
    {
        $token = CSRFTokenService::generateToken();
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token)); // 32 bytes hex
    }

    public function testValidateTokenWorks(): void
    {
        $token = CSRFTokenService::generateToken();
        $this->assertTrue(CSRFTokenService::validateToken($token));
        $this->assertFalse(CSRFTokenService::validateToken('gresit'));
    }
}
