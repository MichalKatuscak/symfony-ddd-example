<?php

declare(strict_types=1);

namespace Sales\Tests\Domain\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sales\Domain\ValueObject\Money;

class MoneyTest extends TestCase
{
    public function testCreateMoneyFromFloat(): void
    {
        $money = Money::fromFloat(10.99, 'EUR');

        $this->assertSame(1099, $money->getAmount());
        $this->assertSame('EUR', $money->getCurrency());
        $this->assertSame(10.99, $money->getAmountAsFloat());
    }

    public function testCreateMoneyFromCents(): void
    {
        $money = Money::fromCents(1099, 'USD');

        $this->assertSame(1099, $money->getAmount());
        $this->assertSame('USD', $money->getCurrency());
        $this->assertSame(10.99, $money->getAmountAsFloat());
    }

    public function testCannotCreateMoneyWithEmptyCurrency(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Currency cannot be empty');

        Money::fromCents(1000, '');
    }

    public function testCannotCreateMoneyWithInvalidCurrencyLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Currency must be a 3-letter ISO code');

        Money::fromCents(1000, 'EURO');
    }

    public function testAddMoney(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.50, 'EUR');

        $result = $money1->add($money2);

        $this->assertSame(1550, $result->getAmount());
        $this->assertSame('EUR', $result->getCurrency());
        $this->assertSame(15.50, $result->getAmountAsFloat());
    }

    public function testCannotAddMoneyWithDifferentCurrencies(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.50, 'USD');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot add money with different currencies');

        $money1->add($money2);
    }

    public function testSubtractMoney(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.50, 'EUR');

        $result = $money1->subtract($money2);

        $this->assertSame(450, $result->getAmount());
        $this->assertSame('EUR', $result->getCurrency());
        $this->assertSame(4.50, $result->getAmountAsFloat());
    }

    public function testCannotSubtractMoneyWithDifferentCurrencies(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.50, 'USD');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot subtract money with different currencies');

        $money1->subtract($money2);
    }

    public function testMultiplyMoney(): void
    {
        $money = Money::fromFloat(10.00, 'EUR');
        $result = $money->multiply(3);

        $this->assertSame(3000, $result->getAmount());
        $this->assertSame('EUR', $result->getCurrency());
        $this->assertSame(30.00, $result->getAmountAsFloat());
    }

    public function testEqualsMoney(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(10.00, 'EUR');
        $money3 = Money::fromFloat(10.00, 'USD');
        $money4 = Money::fromFloat(15.00, 'EUR');

        $this->assertTrue($money1->equals($money2));
        $this->assertFalse($money1->equals($money3));
        $this->assertFalse($money1->equals($money4));
    }

    public function testIsGreaterThan(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.00, 'EUR');

        $this->assertTrue($money1->isGreaterThan($money2));
        $this->assertFalse($money2->isGreaterThan($money1));
    }

    public function testCannotCompareGreaterThanWithDifferentCurrencies(): void
    {
        $money1 = Money::fromFloat(10.00, 'EUR');
        $money2 = Money::fromFloat(5.00, 'USD');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot compare money with different currencies');

        $money1->isGreaterThan($money2);
    }

    public function testIsLessThan(): void
    {
        $money1 = Money::fromFloat(5.00, 'EUR');
        $money2 = Money::fromFloat(10.00, 'EUR');

        $this->assertTrue($money1->isLessThan($money2));
        $this->assertFalse($money2->isLessThan($money1));
    }

    public function testCannotCompareLessThanWithDifferentCurrencies(): void
    {
        $money1 = Money::fromFloat(5.00, 'EUR');
        $money2 = Money::fromFloat(10.00, 'USD');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot compare money with different currencies');

        $money1->isLessThan($money2);
    }

    public function testToString(): void
    {
        $money = Money::fromFloat(10.50, 'EUR');
        $this->assertSame('EUR 10.50', (string)$money);
    }
}
