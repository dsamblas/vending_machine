<?php


namespace Domain\Item;


use Domain\Money;

interface Store
{
    public function buyItem(Slot $slot, Money $money): Item;

    public function setItems(Item $item, Slot $slot, int $qty): void;

    public function toArray(): array;


}