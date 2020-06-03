<?php


namespace Domain\Item;


use Domain\Item;
use Domain\Money;

interface Store
{
    public function buyItem(string $slotCode, Money $money): Item;

    public function setItems(Item $item, string $slotCode, int $qty): void;

    public function toArray(): array;


}