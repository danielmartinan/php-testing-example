<?php

declare(strict_types=1);

namespace App;

/**
 * Calculadora simple para operaciones matemáticas
 */
class Calculator
{
    /**
     * Suma dos números
     */
    public function sum(int $a, int $b): int
    {
        return $a + $b;
    }

    /**
     * Resta dos números
     */
    public function subtract(int $a, int $b): int
    {
        return $a - $b;
    }

    /**
     * Multiplica dos números
     */
    public function multiply(int $a, int $b): int
    {
        return $a * $b;
    }

    /**
     * Divide dos números
     *
     * @throws \DivisionByZeroError Si el divisor es 0
     */
    public function divide(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new \DivisionByZeroError('Division by zero');
        }

        return $a / $b;
    }

    /**
     * Calcula el factorial de un número
     */
    public function factorial(int $n): int
    {
        if ($n < 0) {
            throw new \InvalidArgumentException('Factorial of negative number');
        }

        if ($n === 0 || $n === 1) {
            return 1;
        }

        return $n * $this->factorial($n - 1);
    }
}
