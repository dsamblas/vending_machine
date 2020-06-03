<?php


namespace Domain\Item\Slot\Exception;

use Exception;


class NotEnoughItemInSlot extends Exception
{
    public function __construct(string $itemName, string $code)
    {
        parent::__construct(sprintf('Not enough item:%s on slot:%', $itemName, $code));

    }
}