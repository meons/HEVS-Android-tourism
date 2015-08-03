<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offices = $manager->getRepository('AppBundle:Office')->findAll();

        $userManager = $this->container->get('fos_user.user_manager');

        for($i = 0; $i < 3; $i++ ) {
            $user = $userManager->createUser();
            $user->setUsername("user" . $i);
            $user->setPlainPassword("passw0rd");
            $user->setEmail('user' . $i . '@domain');
            $user->setEnabled(true);
            $user->setOffice($offices[0]);

            $userManager->updateUser($user);
        }
    }

    public function getOrder()
    {
        return 3;
    }
}