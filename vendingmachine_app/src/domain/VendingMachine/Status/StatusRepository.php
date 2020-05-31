<?php


namespace Domain\VendingMachine\Status\Repository;


use Domain\VendingMachine\Status;

interface StatusRepository
{
    public function get(): Status;

    public function save(Status $status);
}