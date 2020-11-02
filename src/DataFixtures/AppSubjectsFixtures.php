<?php

namespace App\DataFixtures;

use App\Entity\Subjects;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AppUsersFixtures;

class AppSubjectsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $subject = new Subjects();
            $subject
                ->setTitle('Sujet'.' '.$i)
                ->setDescription("Dans la plupart des applications, créer tous vos appareils dans une seule classe est très bien. Cette classe peut finir par être un peu longue, mais cela en vaut la peine car avoir un fichier permet de garder les choses simples.

                Si vous décidez de diviser vos appareils en fichiers séparés, Symfony vous aide à résoudre les deux problèmes les plus courants: le partage d'objets entre les appareils et le chargement des appareils dans l'ordre.")
                ->setUser($this->getReference(AppUsersFixtures::USER_REFERENCE));

            $manager->persist($subject);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AppUsersFixtures::class,
        );
    }
}
