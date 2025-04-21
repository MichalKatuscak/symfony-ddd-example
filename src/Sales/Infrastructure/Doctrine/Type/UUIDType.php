<?php

declare(strict_types=1);

namespace Sales\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Sales\Domain\ValueObject\UUID;

class UUIDType extends GuidType
{
    public const NAME = 'uuid';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof UUID) {
            throw new \InvalidArgumentException('Expected UUID instance');
        }

        return $value->getValue();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UUID
    {
        if ($value === null) {
            return null;
        }

        return UUID::fromString($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
