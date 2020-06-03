<?php

namespace Domain\VendingMachine\Command;

use Domain\Item;
use Domain\VendingMachine\Command;

interface BuyItemCommand extends Command
{
    public function item(): Item;

}