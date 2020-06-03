<?php


namespace App\Test\VendingMachine\Coin;


use App\VendingMachine\Coin;
use App\VendingMachine\CoinCollection;
use Domain\Currency;
use PHPUnit\Framework\TestCase;

class CoinCollectionTest extends TestCase
{

    private array $coinArray1;
    private array $coinArray2;
    private Currency $currency1;
    private Currency $currency2;

    public function testPushPopCoins()
    {

        $coinCollection = new CoinCollection($this->coinArray1, $this->currency1);

        $total = $coinCollection->total()->amount();

        self::assertSame(55.0, $total);

        $coin = $coinCollection->pop();

        $total = $coinCollection->total()->amount();

        self::assertSame(54.0, $total);

        $coinCollection->push($coin);

        $total = $coinCollection->total()->amount();

        self::assertSame(55.0, $total);

        $this->expectException(Currency\Exception\CurrencyMismatch::class);

        $coinCollection->push($this->coinArray2[0]);

    }

    protected function setUp()
    {

        $this->currency1 = $this->createMock(Currency::class);
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