<?php


namespace App\Command\Handler;

use App\Infrastructure\File\Persister\JsonFileStatusPersister;
use App\VendingMachine\Command\SetStatusCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class SetStatusCommandHandler implements MessageHandlerInterface
{
    private JsonFileStatusPersister $persister;

    public function __construct(JsonFileStatusPersister $persister)
    {
        $this->persister = $persister;
    }

    public function __invoke(SetStatusCommand $command)
    {
        $this->persister->persist($command->status());
        echo "OK";
    }

}