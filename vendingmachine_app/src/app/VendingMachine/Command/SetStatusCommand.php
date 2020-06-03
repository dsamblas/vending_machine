<?php


namespace App\VendingMachine\Command;


use Domain\VendingMachine\Command\SetStatusCommand as SetStatusCommandInterface;
use Domain\VendingMachine\Status;

class SetStatusCommand implements SetStatusCommandInterface
{

    private Status $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function status(): Status
    {
        return $this->status;
    }
}