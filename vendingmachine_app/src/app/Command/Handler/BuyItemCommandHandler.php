<?php


namespace App\Command\Handler;

use App\Infrastructure\File\Loader\JsonFileStatusLoader;
use App\Infrastructure\File\Persister\JsonFileStatusPersister;
use App\Infrastructure\Output\SystemOutputPrintService;
use App\VendingMachine\Command\BuyItemCommand;
use App\VendingMachine\VendingMachine;
use Domain\Money;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

;


class BuyItemCommandHandler implements MessageHandlerInterface
{
    private JsonFileStatusPersister $persister;
    private JsonFileStatusLoader $loader;
    private SystemOutputPrintService $printService;

    public function __construct(JsonFileStatusPersister $persister, JsonFileStatusLoader $loader, SystemOutputPrintService $printService)
    {
        $this->persister = $persister;
        $this->loader = $loader;
        $this->printService = $printService;
    }

    public function __invoke(BuyItemCommand $command)
    {
        $vm = new VendingMachine($this->loader->load());

        $item = $vm->status()->itemStore()->buyItem(
            $command->item()->name(),
            $vm->status()->inputWallet()->total()
        );

        $exchangeAmount = $vm->status()->inputWallet()->total()->amount() - $item->unitPrice()->amount();

        $this->printService->add($item->name());
        if ($exchangeAmount > 0) {
            $exchangeCoins = $vm->status()->exchangeWallet()->popMoney(new Money($exchangeAmount, $vm->status()->currency()));
            $arrayToReverse = [];
            while ($coin = $exchangeCoins->pop()) {
                $arrayToReverse[] = $coin;
            }
            $arrayReversed = array_reverse($arrayToReverse);
            foreach ($arrayReversed as $coin) {
                $this->printService->add(explode('_', $coin->label())[1]);
            }
        }

        $coin = null;
        while ($coin = $vm->status()->inputWallet()->pop()) {
            $vm->status()->exchangeWallet()->push($coin);
        }
        $this->persister->persist($vm->status());
    }
}