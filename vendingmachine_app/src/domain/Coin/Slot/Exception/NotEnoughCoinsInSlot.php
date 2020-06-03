<?php


namespace Domain\Coin\Slot\Exception;

use Domain\Coin;
use Exception;


class NotEnoughCoinInSlot extends Exception
{
    public function __construct(Coin $coin, string $code)
    {
        parent::__construct(sprintf('Not enough coin:%s on slot:%', $coin->label(), $code));

    }
}