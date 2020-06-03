<?php


namespace App\VendingMachine\Command;


use Domain\Coin;
use Domain\VendingMachine\Command\InsertCoinCommand as InsertCommandInterface;

class InsertCoinCommand implements InsertCommandInterface
{

    private Coin $coin;

    public function __construct(Coin $coin)
    {
        $this->coin = $coin;
    }

    public function coin(): Coin
    {
        return $this->coin;
    }
}