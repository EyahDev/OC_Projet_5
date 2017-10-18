<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');
        // creation d'un user avec role admin
        $admin = new User();
        $passwordAdmin = $encoder->encodePassword($admin, 'admin');
        $admin->setName('admin')
            ->setFirstname('admin')
            ->setEmail('admin@nao.fr')
            ->setUsername('admin')
            ->setPassword($passwordAdmin)
            ->setRoles($manager->getRepository('AppBundle:Role')->findOneBy(array('name' => "ROLE_ADMIN")))
            ->setSalt(substr(md5(time()), 0, 23))
            ->setProPermission(false)
            ->setNewsletter(false)
            ->setSignupDate(new \DateTime());
        $manager->persist($admin);
        $this->addReference('admin', $admin);

        // creation d'un user avec role professionnel
        $pro = new User();
        $passwordPro = $encoder->encodePassword($pro, 'pro');
        $pro->setName('pro')
            ->setFirstname('pro')
            ->setEmail('pro@nao.fr')
            ->setUsername('pro')
            ->setPassword($passwordPro)
            ->setRoles($manager->getRepository('AppBundle:Role')->findOneBy(array('name' => "ROLE_PROFESSIONAL")))
            ->setSalt(substr(md5(time()), 0, 23))
            ->setProPermission(false)
            ->setNewsletter(false)
            ->setSignupDate(new \DateTime());
        $manager->persist($pro);
        $this->addReference('pro', $pro);

        // creation d'un user avec role particulier
        $user = new User();
        $passwordUser = $encoder->encodePassword($user, 'user');
        $user->setName('user')
            ->setFirstname('user')
            ->setEmail('user@nao.fr')
            ->setUsername('user')
            ->setPassword($passwordUser)
            ->setSalt(substr(md5(time()), 0, 23))
            ->setProPermission(false)
            ->setNewsletter(false)
            ->setSignupDate(new \DateTime());

        $manager->persist($user);

        //enregistre les utilisateurs
        $manager->flush();
        $this->addReference('user', $user);
    }
    // permet de générer les roles avant les users
    public function getDependencies()
    {
        return array(
            RoleFixtures::class,
        );
    }
}