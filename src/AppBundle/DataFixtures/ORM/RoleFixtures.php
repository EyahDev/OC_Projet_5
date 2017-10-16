<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Liste des roles à ajouter
        $names = array(
            'ROLE_USER',
            'ROLE_PROFESSIONAL',
            'ROLE_ADMIN'
        );

        foreach ($names as $name) {
            // On crée le role
            $role = new Role();
            $role->setName($name);

            // On la persiste
            $manager->persist($role);
        }

        // On déclenche l'enregistrement de tous les roles
        $manager->flush();
    }
}