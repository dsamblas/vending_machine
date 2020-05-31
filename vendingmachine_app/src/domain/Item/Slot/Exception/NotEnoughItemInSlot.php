<?php


namespace Domain\Item\Slot\Exception;

use Domain\Item\Item;
use Exception;


class NotEnoughItemInSlot extends Exception
{
    public function __construct(Item $item, string $code)
    {
        parent::__construct(sprintf('Not enough item:%s on slot:%', $item->name(), $code));

    }
}