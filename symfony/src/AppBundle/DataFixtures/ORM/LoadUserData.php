<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData.
 *
 * @author Lotte Verwerft
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {

    const COUNT = 1;

    /**
     * {@inheritdoc}
     */
    public function getOrder(){
        // Load this file second.
        return 7;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        $projects = count($em->getRepository('AppBundle:Project')->findAll());

        $user = new User();
        $user
            ->setUsername('test')
            ->setEmail('test@email.be')
            ->setEmailCanonical('test@email.be')
            ->setEnabled(true)
            ->setPassword('test')
            ->setSalt(md5(uniqid()))
            ->setPlainPassword('test')
            ->addProject($this->getReference('Project-'. rand(1, ($projects / 2)-1)))
            ->addProject($this->getReference('Project-'. rand($projects/2, ($projects -1))))
            ->addGroup($this->getReference('Group-'. rand(2,3)))
            ->addGroup($this->getReference('Group-'. rand(0,1)))
            ->setRoles(array('ROLE_ADMIN'))
            ->setFirstName('Jhon')
            ->setLastName('Doe')
        ;
        $this->addReference("User-1", $user);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'secret_password');
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();
        $user = new User();
        $user
            ->setUsername('lorem')
            ->setEmail('loremt@email.be')
            ->setEmailCanonical('lorem@email.be')
            ->setEnabled(false)
            ->setPassword('lorem')
            ->setSalt(md5(uniqid()))
            ->setPlainPassword('lorem')
            ->setRoles(array('ROLE_USER'))
            ->setFirstName('Lorem')
            ->setLastName('Ipsum')
            ->addGroup($this->getReference('Group-'. rand(2,3)))
            ->addGroup($this->getReference('Group-'. rand(0,1)))
            ->addProject($this->getReference('Project-'. rand(1, ($projects / 2)-1)))
            ->addProject($this->getReference('Project-'. rand($projects/2, ($projects -1))))
        ;
        $this->addReference("User-2", $user);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'secret_password');
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        $user = new User();
        $user
            ->setUsername('lottverw')
            ->setEmail('lottverw@email.be')
            ->setEmailCanonical('lottverw@email.be')
            ->setEnabled(true)
            ->setPassword('lottverw')
            ->setSalt(md5(uniqid()))
            ->setPlainPassword('jW7G9eY8')
            ->setRoles(array('ROLE_ADMIN'))
            ->setFirstName('Lotte')
            ->setLastName('Verwerft')
            ->addGroup($this->getReference('Group-'. rand(2,3)))
            ->addGroup($this->getReference('Group-'. rand(0,1)))
            ->addProject($this->getReference('Project-'. rand(1, ($projects / 2)-1)))
            ->addProject($this->getReference('Project-'. rand($projects/2, ($projects -1))))
        ;
        $this->addReference("User-3", $user);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'secret_password');
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();
    }
}
