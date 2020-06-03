<?php


namespace App\VendingMachine\Command;


use App\VendingMachine\Item;
use Domain\VendingMachine\Command\BuyItemCommand as BuyItemCommandInterface;

class BuyItemCommand implements BuyItemCommandInterface
{
    private Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function item(): Item
    {
        return $this->item;
    }

}