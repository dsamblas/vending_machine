<?php


namespace App\VendingMachine\Status;


use App\VendingMachine\Coin;
use App\VendingMachine\CoinCollection;
use App\VendingMachine\Item;
use App\VendingMachine\ItemStore;
use App\VendingMachine\Wallet;
use Domain\Coin\Slot as CoinSlot;
use Domain\Currency;
use Domain\Item\Slot;
use Domain\Money;
use InvalidArgumentException;

class StatusFactory
{
    public static function fromArray(array $arr): Status
    {
        echo "geted from file" . PHP_EOL;
        var_export($arr);

    }

    public static function fromArgs(array $args): Status
    {
        unset($args[0]);

        $exchangeWalletCoins = [];
        $itemSlots = [];
        $currency = new Currency('EUR');
        $machine_id = uniqid();
        foreach ($args as $statusToken) {
            $statusTokenParts = explode(':', $statusToken);
            switch ($statusTokenParts[0]) {
                case "coin":
                    $exchangeWalletCoins[] = new CoinSlot(
                        new Coin(
                            implode('_', ['coin', (string)$statusTokenParts[1], $currency->code()]),
                            (float)$statusTokenParts[1],
                            $currency
                        ),
                        implode('_', ['coin', (string)$statusTokenParts[1], $currency->code()]),
                        (int)$statusTokenParts[2]
                    );
                    break;
                case "item":
                    $itemSlots[(string)$statusTokenParts[1]] = new Slot(
                        new Item(
                            (string)$statusTokenParts[1],
                            new Money((float)$statusTokenParts[3], $currency)
                        ),
                        (string)$statusTokenParts[1],
                        (int)$statusTokenParts[2]
                    );
                    break;
                case "currency":
                    $currency = new Currency((string)$statusTokenParts[1]);
                    break;
                case "machineId":
                    $machine_id = $statusTokenParts[1];
                    break;
                default:
                    throw new InvalidArgumentException();

            }

        }

        $status = new Status(
            $machine_id,
            new Wallet(new CoinCollection([], $currency)),
            new Wallet(new CoinCollection($exchangeWalletCoins, $currency)),
            new ItemStore($itemSlots),
            $currency
        );

        return $status;


    }
}