<?php


namespace Domain\Coin;


use Domain\Coin;
use Domain\Money;

interface CoinCollection
{
    public function popCoin(): Coin;

    public function total(): Money;

}