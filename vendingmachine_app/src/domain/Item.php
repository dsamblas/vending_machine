<?php


namespace Domain\Item;

use Domain\Money;

interface Item
{
    public function name(): string;

    public function unitPrice(): Money;
}
