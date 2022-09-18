<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Command\SignalableCommandInterface;

#[AsCommand(name: 'debug:watch')]
class WatchCommand extends Command implements SignalableCommandInterface
{
    private $section;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $this->section = $output->section();
        $counter = 0;

        while (true) {
            $counter++;
            $this->section->overwrite(sprintf('this is the %dnth loop', $counter));
            sleep(1);
        }

        return Command::SUCCESS;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGINT];
    }

    public function handleSignal(int $signal): void
    {
        /**
         * uncomment the line to enable the workaround
         */
//        $this->section->write('');
        $this->section->clear();
        exit();
    }
}