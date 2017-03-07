<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CleanupDefinitionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:cleanup-definitions')
            ->setDescription('HTML sanitization')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sto ripulendo le definizioni dai tag html');

        $purifier = $this->getContainer()->get('app.htmlsanitization');

        $index = 0;

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $definitions = $manager->getRepository('AppBundle:Definition')->findBy(array(), array('created' => 'DESC'));

        foreach ($definitions as $definition) {

            $content = $purifier->purify($definition->getBody());
            $definition->setBody($content);
            $manager->persist($definition);

            if($index%20==0) $manager->flush();
            $index++;
        }
        $manager->flush();

        $output->writeln('Ho finito');
    }

}
