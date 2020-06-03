<?php

namespace Domain\VendingMachine\Command;

use Domain\Item;
use Domain\VendingMachine\Command;
use Domain\VendingMachine\Wallet;

interface BuyItemCommand extends Command
{
    public function item(): Item;

    public function inbox(): Wallet;

    public function exchangebox(): Wallet;
}