<?php


namespace App\Infrastructure\File\Repository;

use App\Infrastructure\File\Loader\JsonFileStatusLoader;
use App\VendingMachine\Item;
use Domain\Item\ItemRepository;
use Domain\Item\Slot\Exception\UnknownItemSlotCode;
use Domain\Money;


class JsonFileItemRepository implements ItemRepository
{
    private JsonFileStatusLoader $loader;

    public function __construct(JsonFileStatusLoader $loader)
    {
        $this->loader = $loader;
    }


    public function getBySlotCode(string $slotCode): Item
    {
        $status = $this->loader->load();
        $itemStoreArray = $status->itemStore()->toArray();

        if (!isset($itemStoreArray[$slotCode])) {
            throw new UnknownItemSlotCode($slotCode);
        }

        return new Item(
            $itemStoreArray[$slotCode]['name'],
            new Money(
                $itemStoreArray[$slotCode]['unitPrice'],
                $status->currency()
            )
        );
    }

}