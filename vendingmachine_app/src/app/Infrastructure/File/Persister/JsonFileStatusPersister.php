<?php


namespace App\Infrastructure\File\Persister;

use App\Infrastructure\File\Repository\JsonFileStatusRepository;
use Domain\VendingMachine\Status;
use Domain\VendingMachine\Status\Persister;


class JsonFileStatusPersister implements Persister
{
    private JsonFileStatusRepository $repository;

    public function __construct(JsonFileStatusRepository $repository)
    {
        $this->repository = $repository;
    }

    public function persist(Status $status): void
    {
        $this->repository->save($status);
    }

}