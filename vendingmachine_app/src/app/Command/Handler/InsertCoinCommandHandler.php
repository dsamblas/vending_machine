<?php


namespace App\Command\Handler;

use App\Infrastructure\File\Loader\JsonFileStatusLoader;
use App\Infrastructure\File\Persister\JsonFileStatusPersister;
use App\VendingMachine\VendingMachine;
use Domain\VendingMachine\Command\InsertCoinCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class InsertCoinCommandHandler implements MessageHandlerInterface
{
    private JsonFileStatusPersister $persister;
    private JsonFileStatusLoader $loader;

    public function __construct(JsonFileStatusPersister $persister, JsonFileStatusLoader $loader)
    {
        $this->persister = $persister;
        $this->loader = $loader;
    }

    public function __invoke(InsertCoinCommand $command)
    {
        $vm = new VendingMachine($this->loader->load());
        $vm->status()->inputWallet()->push($command->coin());
        $this->persister->persist($vm->status());
    }

}