<?php

namespace Domain\VendingMachine\Command;

use Domain\VendingMachine\Wallet;

interface ReturnInboxCommand
{
    public function inbox(): Wallet;
}