<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppUsersFixtures extends Fixture
{
    private $encoder;
    public const USER_REFERENCE = 'user_lanza';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new Users;

        $name = 'alphonse';
        $password = $this->encoder->encodePassword($user, '123456Rc');
        $user
            ->setAuthor($name)
            ->setEmail($name.'@gmail.com')
            ->setPassword($password);

        $this->addReference(self::USER_REFERENCE, $user);
        
        $manager->persist($user);

        $manager->flush();
    }

}