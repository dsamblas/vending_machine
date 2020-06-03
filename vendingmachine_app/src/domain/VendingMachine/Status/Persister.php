<?php


namespace Domain\VendingMachine\Status;


use Domain\VendingMachine\Status;

interface Persister
{
    public function persist(Status $status): void;

}