<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DropSessionTableCommand extends ContainerAwareCommand
{
    private $table = 'sessions';

    protected function configure()
    {
        $this
            ->setName('app:session_table:drop')
            ->setDescription('Drop the session\'s table.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');

        try {
            $db->exec(sprintf('DROP TABLE %s', $this->table));
            $io->success('Session\'s table was successfully dropped.');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}