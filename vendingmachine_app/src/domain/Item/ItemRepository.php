<?php


namespace Domain\Item;


use Domain\Item;

interface ItemRepository
{
    public function getBySlotCode(string $slotCode): Item;

}