<?php

namespace Domain\VendingMachine\Command;

use Domain\Coin;
use Domain\VendingMachine\Command;

interface InsertCoinCommand extends Command
{
    public function coin(): Coin;
}