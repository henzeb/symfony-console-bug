<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

#[AsCommand(name: 'debug:watch2')]
class Watch2Command extends Command
{
    private bool $loop = true;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $section = $output->section();
        $counter = 0;

        pcntl_signal(
            SIGINT,
            function () {
                $this->loop = false;
            }
        );

        while ($this->loop) {
            $counter++;
            $section->overwrite(sprintf('this is the %dnth loop', $counter));
            sleep(1);
        }

        /**
         * uncomment the line to enable the workaround
         */
//        $this->section->write('');
        $section->clear();

        return Command::SUCCESS;
    }
}