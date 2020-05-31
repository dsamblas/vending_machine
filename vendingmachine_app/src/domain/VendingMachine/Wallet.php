<?php


namespace Domain\VendingMachine;


use Domain\Coin\CoinCollection;
use Domain\Money;

interface Wallet extends CoinCollection
{
    public function popMoney(Money $money): CoinCollection;
}