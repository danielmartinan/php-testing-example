<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    /**
     * @test
     */
    public function it_sums_two_numbers(): void
    {
        $result = $this->calculator->sum(2, 3);
        $this->assertEquals(5, $result);
    }

    /**
     * @test
     */
    public function it_subtracts_two_numbers(): void
    {
        $result = $this->calculator->subtract(10, 3);
        $this->assertEquals(7, $result);
    }

    /**
     * @test
     */
    public function it_multiplies_two_numbers(): void
    {
        $result = $this->calculator->multiply(4, 5);
        $this->assertEquals(20, $result);
    }

    /**
     * @test
     */
    public function it_divides_two_numbers(): void
    {
        $result = $this->calculator->divide(10, 2);
        $this->assertEquals(5.0, $result);
    }

    /**
     * @test
     */
    public function it_throws_exception_on_division_by_zero(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        $this->expectExceptionMessage('Division by zero');

        $this->calculator->divide(10, 0);
    }

    /**
     * @test
     */
    public function it_calculates_factorial(): void
    {
        $this->assertEquals(1, $this->calculator->factorial(0));
        $this->assertEquals(1, $this->calculator->factorial(1));
        $this->assertEquals(6, $this->calculator->factorial(3));
        $this->assertEquals(120, $this->calculator->factorial(5));
    }

    /**
     * @test
     */
    public function it_throws_exception_on_negative_factorial(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Factorial of negative number');

        $this->calculator->factorial(-1);
    }

    /**
     * @test
     * @dataProvider additionProvider
     */
    public function it_adds_with_data_provider(int $a, int $b, int $expected): void
    {
        $result = $this->calculator->sum($a, $b);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array<string, array<int, int>>
     */
    public static function additionProvider(): array
    {
        return [
            'positive numbers' => [2, 3, 5],
            'negative numbers' => [-2, -3, -5],
            'mixed numbers' => [10, -5, 5],
            'with zero' => [5, 0, 5],
        ];
    }
}
