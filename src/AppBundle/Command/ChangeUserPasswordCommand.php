<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\User;

class ChangeUserPasswordCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:changeuserpassword')
            // the short description shown while running "php bin/console list"
            ->setDescription('Modifica password di un utente selezionato')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Specificare username e password")
            // Dichiaro input argument
            ->addArgument('username', InputArgument::REQUIRED, 'Il nome dell\'utente a cui modificare la password')
            ->addArgument('password', InputArgument::REQUIRED, 'La nuova password');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();

        $usr = $input->getArgument('username');
        $pwd = $input->getArgument('password');

        $entity = $manager->getRepository('AppBundle:User')->findOneBy(array('username' => $usr));

        if (!$entity) {
            $output->writeln('Utente non trovato');
        } else {
            $encoder = $this->getContainer()->get('security.password_encoder');
            $password = $encoder->encodePassword($entity, $pwd);
            $entity->setPassword($password);
            $entity->setUpdated(New \DateTime());
            $manager->persist($entity);
            $manager->flush();

            $output->writeln('Password modificata');
        }

    }

}