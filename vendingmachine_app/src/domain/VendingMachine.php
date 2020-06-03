<?php


namespace Domain;

use Domain\VendingMachine\Status;

interface VendingMachine
{
    public function id(): string;

    public function status(): Status;
}