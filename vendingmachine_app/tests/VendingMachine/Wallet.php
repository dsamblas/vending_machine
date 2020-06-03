<?php


namespace App\Test\VendingMachine;

use App\VendingMachine\Coin;
use App\VendingMachine\CoinCollection;
use App\VendingMachine\Wallet;
use Domain\Currency;
use Domain\Money;
use Domain\Money\MoneyInterface;
use Domain\VendingMachine\Wallet\Exception\NoExactCoinCombination;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{

    private array $coinArray1;
    private array $coinArray2;
    private Currency $currency1;
    private Currency $currency2;
    private MoneyInterface $inboxMoneyStub;

    public function testpopMoney()
    {

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(3));

        $this->inboxMoneyStub->expects($this->any())
            ->method('currency')
            ->will($this->returnValue($this->currency1));

        $wallet = new Wallet(new CoinCollection($this->coinArray1, $this->currency1));

        $this->assertSame(55.0, $wallet->total()->amount());

        $coins = $wallet->popMoney($this->inboxMoneyStub);

        $this->assertSame(3.0, $coins->total()->amount());

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(5));

        $coins = $wallet->popMoney($this->inboxMoneyStub);

        $this->assertSame(5.0, $coins->total()->amount());

        $this->expectException(NoExactCoinCombination::class);


        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.1));

        $wallet = new Wallet(new CoinCollection($this->coinArray2, $this->currency2));

        $this->assertSame(5.5, $wallet->total()->amount());

        $coins = $wallet->popMoney($this->inboxMoneyStub);

        $this->assertSame(5.4, $coins->total()->amount());

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(0.2));

        $coins = $wallet->popMoney($this->inboxMoneyStub);

        $this->assertSame(5.2, $coins->total()->amount());

        $this->inboxMoneyStub->expects($this->any())
            ->method('amount')
            ->will($this->returnValue(10000));

        $this->expectException(Money\Exception\NotEnoughMoney::class);

        $wallet->popMoney($this->inboxMoneyStub);

        $this->expectException(Money\Exception\NotEnoughMoney::class);


    }

    protected function setUp()
    {
        parent::setUp();

        $this->currency1 = $this->createMock(Currency::class);
        $this->inboxMoneyStub = $this->createMock(Money::class);
        $this->currency1->expects($this->any())
            ->method('code')
            ->will($this->returnValue('EUR'));

        $this->currency2 = $this->createMock(Currency::class);
        $this->currency2->expects($this->any())
            ->method('code')
            ->will($this->returnValue('USD'));

        for ($i = 1; $i <= 10; $i++) {
            $this->coinArray1[] = new Coin(
                implode(
                    '_',
                    ['coin', (string)$i, $this->currency1->code()]
                ),
                (float)$i,
                $this->currency1
            );
        }

        for ($i = 0; $i <= 1; $i += 0.1) {
            $this->coinArray2[] = new Coin(
                implode(
                    '_',
                    ['coin', (string)$i, $this->currency2->code()]
                ),
                (float)$i,
                $this->currency2
            );
        }
    }


}