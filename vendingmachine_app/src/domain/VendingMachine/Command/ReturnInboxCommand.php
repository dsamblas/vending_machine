<?php

namespace Domain\VendingMachine\Command;

use Domain\VendingMachine\Command;
use Domain\VendingMachine\Wallet;

interface ReturnInboxCommand extends Command
{
    public function inbox(): Wallet;
}