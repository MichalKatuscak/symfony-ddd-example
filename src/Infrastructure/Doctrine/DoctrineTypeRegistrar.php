<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine;

use Doctrine\DBAL\Types\Type;
use Sales\Infrastructure\Doctrine\Type\EmailType;
use Sales\Infrastructure\Doctrine\Type\MoneyType;
use Sales\Infrastructure\Doctrine\Type\UUIDType;

class DoctrineTypeRegistrar
{
    public static function registerTypes(): void
    {
        if (!Type::hasType(UUIDType::NAME)) {
            Type::addType(UUIDType::NAME, UUIDType::class);
        }

        if (!Type::hasType(EmailType::NAME)) {
            Type::addType(EmailType::NAME, EmailType::class);
        }

        if (!Type::hasType(MoneyType::NAME)) {
            Type::addType(MoneyType::NAME, MoneyType::class);
        }
    }
}
