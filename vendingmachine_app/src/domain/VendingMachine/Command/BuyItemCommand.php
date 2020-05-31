<?php

namespace Domain\VendingMachine\Command;

use Domain\Item\Item;
use Domain\VendingMachine\Wallet;

interface BuyItemCommand
{
    public function item(): Item;

    public function inbox(): Wallet;

    public function exchangebox(): Wallet;
}