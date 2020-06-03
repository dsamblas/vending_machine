<?php


namespace App\VendingMachine;


use Domain\VendingMachine as VendingMachineInterface;
use Domain\VendingMachine\Status;

class VendingMachine implements VendingMachineInterface
{

    private $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function id(): string
    {
        return $this->status()->machineId();

    }

    public function status(): Status
    {
        return $this->status;
    }
}