<?php


namespace App\Command\Handler;

use App\Infrastructure\File\Persister\JsonFileStatusPersister;
use App\Infrastructure\Output\SystemOutputPrintService;
use App\VendingMachine\Command\SetStatusCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class SetStatusCommandHandler implements MessageHandlerInterface
{
    private JsonFileStatusPersister $persister;
    private SystemOutputPrintService $printService;

    public function __construct(JsonFileStatusPersister $persister, SystemOutputPrintService $outputPrintService)
    {
        $this->persister = $persister;
        $this->printService = $outputPrintService;
    }

    public function __invoke(SetStatusCommand $command)
    {
        $this->persister->persist($command->status());
        $this->printService->add('OK');
    }

}