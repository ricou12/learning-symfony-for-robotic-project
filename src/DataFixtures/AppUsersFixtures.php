<?php

namespace App\DataFixtures;

use App\Entity\Users;
use App\Entity\Subjects;
use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\SubjectsRepository;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppUsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new Users;
            $userAdmin
                ->setAuthor("Lanza eric")
                ->setEmail("lanzae32@gmail.com")
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($this->encoder->encodePassword($userAdmin, 'RicouAdmin=05-12'));
            $manager->persist($userAdmin);

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        for ($i=1; $i <= 20; $i++) { 
            $user = new Users;
            $user
                ->setAuthor($faker->name)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, '123456Rc'));

            $manager->persist($user);
            $this->createSubjects($faker,$manager,$user);
        }
        $manager->flush();
        
    }

    public function createSubjects($faker,$manager, $user) {
        $maxSubject = rand (1, 15);
        for ($i = 1; $i <= $maxSubject; $i++) {
            $subject = new Subjects();
            $subject
                ->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true))
                ->setDescription($faker->text($maxNbChars = 200) )
                ->setUser($user);
            $manager->persist($subject);
            $this->createComments($faker,$manager,$user,$subject);
        }
    }

    public function createComments($faker,$manager, $user,$subject) {
        $maxSubject = rand (1, 10);
        for ($i = 0; $i <= $maxSubject; $i++) {
            $comment = new Comments();
            $comment
                ->setMessage($faker->paragraph($nbSentences = 10, $variableNbSentences = true))
            
                ->setUser($user)
                ->setSubject($subject);

            $manager->persist($comment);
        }
    }
}