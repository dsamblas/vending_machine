<?php


namespace App\Test\VendingMachine;

use App\VendingMachine\ItemStore;
use Domain\Currency;
use Domain\Item;
use Domain\Item\Slot;
use Domain\Money;
use Domain\Money\MoneyInterface;
use PHPUnit\Framework\TestCase;

class ItemStoreTest extends TestCase
{

    private MoneyInterface $unitPriceStub;
    private Item $itemStub1;
    private Item $itemStub2;
    private MoneyInterface $inboxMoneyStub;
    private Currency $currencyStub;

    public function testBuyItem()
    {

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.50));

        $this->inboxMoneyStub->expects($this->any())
            ->method('currency')
            ->will($this->returnValue($this->currencyStub));


        $slots = [
            'SODA' => new Slot($this->itemStub1, 'SODA', 3),
            'THING' => new Slot($this->itemStub2, 'THING', 4)

        ];

        $store = new ItemStore($slots);

        $item = $store->buyItem('SODA', $this->inboxMoneyStub);

        self::assertSame('SODA', $item->name());

        $this->expectException(Slot\Exception\NotEnoughItemInSlot::class);

        for ($i = 0; $i <= 99; $i++) {
            $store->buyItem('SODA', $this->inboxMoneyStub);
        }

    }

    public function testBuyItemWhithOutEnoughMoney()
    {

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.10));

        $this->inboxMoneyStub->expects($this->any())
            ->method('currency')
            ->will($this->returnValue($this->currencyStub));


        $slots = [
            'SODA' => new Slot($this->itemStub1, 'SODA', 3),
            'THING' => new Slot($this->itemStub2, 'THING', 4)

        ];

        $store = new ItemStore($slots);

        $this->expectException(Money\Exception\NotEnoughMoney::class);

        $store->buyItem('SODA', $this->inboxMoneyStub);

    }

    public function testToArray()
    {

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.50));

        $this->inboxMoneyStub->expects($this->any())
            ->method('currency')
            ->will($this->returnValue($this->currencyStub));

        $slots = [
            'SODA' => new Slot($this->itemStub1, 'SODA', 3),
            'THING' => new Slot($this->itemStub2, 'THING', 4)

        ];

        $store = new ItemStore($slots);

        $storeArray = $store->toArray();

        $expectedArray = [
            'SODA' => ['name' => "SODA", 'unitPrice' => 0.25, 'qty' => 3],
            'THING' => ['name' => "THING", 'unitPrice' => 0.25, 'qty' => 4],


        ];

        $this->assertSame($expectedArray, $storeArray);

        $store->buyItem('SODA', $this->inboxMoneyStub);

        $expectedArray = [
            'SODA' => ['name' => "SODA", 'unitPrice' => 0.25, 'qty' => 2],
            'THING' => ['name' => "THING", 'unitPrice' => 0.25, 'qty' => 4]


        ];
        $storeArray = $store->toArray();

        $this->assertSame($expectedArray, $storeArray);

    }

    protected function setUp()
    {
        parent::setUp();

        $this->unitPriceStub = $this->createMock(Money::class);
        $this->itemStub1 = $this->createMock(Item::class);
        $this->itemStub2 = $this->createMock(Item::class);
        $this->inboxMoneyStub = $this->createMock(Money::class);
        $this->currencyStub = $this->createMock(Currency::class);


        $this->itemStub1->expects($this->any())
            ->method('name')
            ->will($this->returnValue('SODA'));

        $this->itemStub1->expects($this->any())
            ->method('unitPrice')
            ->will($this->returnValue($this->unitPriceStub));

        $this->itemStub2->expects($this->any())
            ->method('name')
            ->will($this->returnValue('THING'));

        $this->itemStub2->expects($this->any())
            ->method('unitPrice')
            ->will($this->returnValue($this->unitPriceStub));

        $this->unitPriceStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.25));

        $this->unitPriceStub->expects($this->any())
            ->method('currency')
            ->will($this->returnValue($this->currencyStub));
    }

}