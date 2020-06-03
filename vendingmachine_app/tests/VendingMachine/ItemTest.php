<?php


namespace App\Test\VendingMachine;

use App\VendingMachine\Item;
use Domain\Money;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testCreateItem()
    {
        $moneyMock = $this->createMock(Money::class);
        $item = new Item('test', $moneyMock);
        $this->assertSame($item->name(), 'test');
        $this->assertInstanceOf(Money::class, $item->unitPrice());
        $this->assertSame($moneyMock->amount(), $item->unitPrice()->amount());
    }

}