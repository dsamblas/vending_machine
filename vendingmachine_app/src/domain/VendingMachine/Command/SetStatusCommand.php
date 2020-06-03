<?php

namespace Domain\VendingMachine\Command;

use Domain\VendingMachine\Command;
use Domain\VendingMachine\Status;

interface SetStatusCommand extends Command
{
    public function status(): Status;
}