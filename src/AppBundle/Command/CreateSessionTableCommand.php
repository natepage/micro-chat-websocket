<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateSessionTableCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:session_table:create')
            ->setDescription('Create the session\'s table with PDO.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $pdoSessionHandler = $this->getContainer()->get('session.handler.pdo');

        try {
            $pdoSessionHandler->createTable();
            $io->success('Session\'s table was successfully created.');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}