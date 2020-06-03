<?php


namespace Domain\Coin;


use Domain\Coin;
use Domain\Currency;
use Domain\Money;

interface CoinCollection
{
    public function pop(): ?Coin;

    public function push(Coin $coin): void;

    public function remove(Coin $coin, int $qty): void;

    public function total(): Money;

    public function currency(): Currency;

    public function toArray(): array;

}