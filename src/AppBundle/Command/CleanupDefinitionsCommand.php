<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Definition;
use HTMLPurifier_Config;
use HTMLPurifier;

class CleanupDefinitionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:cleanup-definitions')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sto ripulendo le definizioni dai tag html');

        $config = HTMLPurifier_Config::createDefault();
        $config->set('CSS.AllowedProperties', array());
        $config->set('HTML.ForbiddenElements', array('font', 'div', 'span', 'pre'));
        //$config->set( 'AutoFormat.RemoveSpansWithoutAttributes', true );
        $purifier = new HTMLPurifier($config);

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $definitions = $manager->getRepository('AppBundle:Definition')->findBy(array(), array('created' => 'DESC'));


        foreach ($definitions as $i => $definition) {

            $content = $definition->getBody();
            $content = $purifier->purify($content);
            $content = preg_replace('/\s*style\s*=\s*[\"\'][^\"|\']*[\"\']/', '', stripslashes($content));
            $content = preg_replace('/\s*<\s*font[^<]*>/', '', stripslashes($content));
            $content = preg_replace('/\s*<\/\s*font[^<]*>/', '', stripslashes($content));
            $content = preg_replace('/\s*<\s*span\s*>\s*<\/\s*span\s*>/', '', stripslashes($content));
            $definition->setBody($content);
            $manager->flush($definition);

        }

        $output->writeln('Ho finito');
    }

}
