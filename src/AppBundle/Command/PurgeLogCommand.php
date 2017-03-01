<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PurgeLogCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:purge-log')
            ->setDescription('Elimina i log più vecchi di una data')
            ->addArgument('interval', InputArgument::REQUIRED, 'Intervallo di tempo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $interval = $input->getArgument('interval');
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $qb = $manager->createQueryBuilder();
        $qb->delete('AppBundle:Hit', 'h');
        $qb->where('DATEDIFF(NOW(), h.visitTime) > :interval');
        $qb->setParameter('interval', $interval);
        $qb->getQuery()->execute();

        $output->writeln('Ho fatto pulizia');
    }

}
