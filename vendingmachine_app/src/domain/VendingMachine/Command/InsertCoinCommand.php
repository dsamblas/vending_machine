<?php

namespace Domain\VendingMachine\Command;

use Domain\Coin;

interface InsertCoinCommand
{
    public function coin(): Coin;
}