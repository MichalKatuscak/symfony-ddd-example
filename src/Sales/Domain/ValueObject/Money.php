<?php

declare(strict_types=1);

namespace Sales\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Money
{
    private function __construct(
        private int $amount,
        private string $currency
    ) {
        if ($currency === '') {
            throw new InvalidArgumentException('Currency cannot be empty');
        }

        if (strlen($currency) !== 3) {
            throw new InvalidArgumentException('Currency must be a 3-letter ISO code');
        }
    }

    public static function fromFloat(float $amount, string $currency): self
    {
        return new self((int)($amount * 100), $currency);
    }

    public static function fromCents(int $amount, string $currency): self
    {
        return new self($amount, $currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAmountAsFloat(): float
    {
        return $this->amount / 100;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(self $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException('Cannot add money with different currencies');
        }

        return new self($this->amount + $money->amount, $this->currency);
    }

    public function subtract(self $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException('Cannot subtract money with different currencies');
        }

        return new self($this->amount - $money->amount, $this->currency);
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function equals(self $money): bool
    {
        return $this->amount === $money->amount && $this->currency === $money->currency;
    }

    public function isGreaterThan(self $money): bool
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException('Cannot compare money with different currencies');
        }

        return $this->amount > $money->amount;
    }

    public function isLessThan(self $money): bool
    {
        if ($this->currency !== $money->currency) {
            throw new InvalidArgumentException('Cannot compare money with different currencies');
        }

        return $this->amount < $money->amount;
    }

    public function __toString(): string
    {
        return sprintf('%s %0.2f', $this->currency, $this->getAmountAsFloat());
    }
}
