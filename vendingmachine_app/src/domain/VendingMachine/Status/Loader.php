<?php


namespace Domain\VendingMachine\Status;


use Domain\VendingMachine\Status;

interface Loader
{
    public function load(): Status;

}