<?php


namespace App\VendingMachine\Status;


use App\VendingMachine\Coin;
use App\VendingMachine\CoinCollection;
use App\VendingMachine\Item;
use App\VendingMachine\ItemStore;
use App\VendingMachine\Wallet;
use Domain\Coin\Slot as CoinSlot;
use Domain\Currency;
use Domain\Item\Slot as ItemSlot;
use Domain\Money;
use InvalidArgumentException;

class StatusFactory
{
    public static function fromArray(array $arr): Status
    {

        $inboxcoinSlots = [];
        foreach ($arr['input']['coins'] as $coinLabel => $coinSlot) {
            $inboxcoinSlots[] = new CoinSlot(new Coin($coinLabel, $coinSlot['value'], new Currency($arr['input']['currency'])), $coinLabel, $coinSlot['qty']);
        }
        $exchangeCoinSlots = [];
        foreach ($arr['exchange']['coins'] as $coinLabel => $coinSlot) {
            $exchangeCoinSlots[] = new CoinSlot(new Coin($coinLabel, $coinSlot['value'], new Currency($arr['exchange']['currency'])), $coinLabel, $coinSlot['qty']);
        }
        $itemSlots = [];
        foreach ($arr['stock'] as $itemName => $itemSlot) {
            $itemSlots[] = new ItemSlot(
                new Item(
                    $itemName,
                    new Money(
                        $itemSlot['unitPrice'],
                        new Currency($arr['currency'])
                    )
                ),
                $itemName,
                $itemSlot['qty']
            );
        }

        return new Status(
            $arr['machineId'],
            new Wallet(new CoinCollection($inboxcoinSlots, new Currency($arr['input']['currency']))),
            new Wallet(new CoinCollection($exchangeCoinSlots, new Currency($arr['exchange']['currency']))),
            new ItemStore($itemSlots),
            new Currency($arr['currency'])
        );

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
                    $itemSlots[(string)$statusTokenParts[1]] = new ItemSlot(
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