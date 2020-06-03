<?php


namespace App\Infrastructure\File\Repository;

use App\VendingMachine\Status\Status;
use App\VendingMachine\Status\StatusFactory;
use Domain\VendingMachine\Status as StatusInterface;
use Domain\VendingMachine\Status\StatusRepository;


class JsonFileStatusRepository implements StatusRepository
{
    private string $path;
    private StatusFactory $statusFactory;

    public function __construct(string $path, StatusFactory $statusFactory)
    {
        $this->path = $path;
        $this->statusFactory = $statusFactory;
    }


    public function get(): Status
    {
        return $this->statusFactory->fromArray(json_decode(file_get_contents($this->path), true));
    }

    public function save(StatusInterface $status)
    {
        file_put_contents($this->path, json_encode($status->toArray()));
    }
}