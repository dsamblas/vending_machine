<?php


namespace Domain\Money\Exception;

use Exception;

class NotEnoughMoney extends Exception
{
    public function __construct(float $qty, float $otherQty)
    {
        parent::__construct(sprintf('Amount %s not equals %s', $qty, $otherQty));
    }
}