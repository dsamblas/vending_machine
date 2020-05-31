<?php

namespace Domain\VendingMachine\Command;

use Domain\VendingMachine\Status;

interface SetStatusCommand
{
    public function status(): Status;
}