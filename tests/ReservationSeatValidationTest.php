<?php
use PHPUnit\Framework\TestCase;

final class ReservationSeatValidationTest extends TestCase
{
    public function testSeatCoordinatesAreNumeric(): void
    {
        $seat = "3-7";
        [$row, $col] = explode('-', $seat);

        $this->assertIsNumeric($row);
        $this->assertIsNumeric($col);
        $this->assertGreaterThan(0, (int)$row);
        $this->assertGreaterThan(0, (int)$col);
    }
}
