<?php


namespace App\Command\Handler;

use App\Infrastructure\File\Loader\JsonFileStatusLoader;
use App\Infrastructure\File\Persister\JsonFileStatusPersister;
use App\Infrastructure\Output\SystemOutputPrintService;
use App\VendingMachine\Command\ReturnInboxCommand;
use App\VendingMachine\VendingMachine;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class ReturnInboxCommandHandler implements MessageHandlerInterface
{
    private JsonFileStatusPersister $persister;
    private JsonFileStatusLoader $loader;
    private SystemOutputPrintService $printService;

    public function __construct(JsonFileStatusPersister $persister, JsonFileStatusLoader $loader, SystemOutputPrintService $outputPrintService)
    {
        $this->persister = $persister;
        $this->loader = $loader;
        $this->printService = $outputPrintService;
    }

    public function __invoke(ReturnInboxCommand $command)
    {
        $vm = new VendingMachine($this->loader->load());
        while ($coin = $vm->status()->inputWallet()->pop()) {
            $this->printService->add(explode('_', $coin->label())[1]);
        }

        $this->persister->persist($vm->status());
    }

}