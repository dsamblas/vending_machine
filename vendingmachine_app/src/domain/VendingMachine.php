<?php


namespace Domain;

use Domain\VendingMachine\Status;

interface VendingMachine
{
    public function status(): Status;
}