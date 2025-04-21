<?php

declare(strict_types=1);

namespace Sales\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final readonly class UUID
{
    private function __construct(
        private string $value
    ) {
        if (!RamseyUuid::isValid($value)) {
            throw new InvalidArgumentException('Invalid UUID');
        }
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $uuid): bool
    {
        return $this->value === $uuid->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
