<?php

declare(strict_types=1);

namespace Sales\Domain\ValueObject;

use InvalidArgumentException;

final readonly class VATId
{
    private function __construct(
        private string $value
    ) {
        if (!preg_match('/^[A-Z]{2}[0-9A-Z]{2,12}$/', $value)) {
            throw new InvalidArgumentException('Invalid VAT ID format');
        }
    }

    public static function fromString(string $vatId): self
    {
        return new self($vatId);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCountryCode(): string
    {
        return substr($this->value, 0, 2);
    }

    public function equals(self $vatId): bool
    {
        return $this->value === $vatId->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
