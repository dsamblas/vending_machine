<?php


namespace Domain\Item\Slot\Exception;

use Exception;


class UnknownItemSlotCode extends Exception
{
    public function __construct(string $code)
    {
        parent::__construct(sprintf('Unknown on slot code:%', $code));
    }
}