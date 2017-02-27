<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Role;

class LoadRoleData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $entity_a = new Role();
        $entity_a->setRole('ROLE_ADMIN');
        $entity_a->setIsAdmin(true);
        $manager->persist($entity_a);

        $entity_u = new Role();
        $entity_u->setRole('ROLE_USER');
        $entity_u->setIsAdmin(false);
        $manager->persist($entity_u);

        $manager->flush();
        $manager->clear();
    }


}