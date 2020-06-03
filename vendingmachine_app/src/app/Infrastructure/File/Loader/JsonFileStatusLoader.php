<?php


namespace App\Infrastructure\File\Loader;

use App\Infrastructure\File\Repository\JsonFileStatusRepository;
use App\VendingMachine\Status\Status;
use Domain\VendingMachine\Status\Loader;


class JsonFileStatusLoader implements Loader
{
    private JsonFileStatusRepository $repository;

    public function __construct(JsonFileStatusRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(): Status
    {
        return $this->repository->get();
    }
}