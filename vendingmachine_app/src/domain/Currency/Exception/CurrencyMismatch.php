<?php


namespace Domain\Currency\Exception;

use Exception;

class CurrencyMismatch extends Exception
{
    public function __construct(string $code, string $otherCode)
    {
        parent::__construct(sprintf('Currency %s not equals %s', $code, $otherCode));
    }
}