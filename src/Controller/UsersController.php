<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/* On peux creer un formulaire dans le controller non recommande */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Users;
use App\Form\UsersType;
use App\Entity\Subjects;
use App\Form\SubjectsFormType;

class UsersController extends AbstractController
{
    /**
     * @Route("/profil", name="profilUsers")
     */
    public function profil(Request $request): Response {
        // Utilisateur avec session active
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Champs de formulaire pour ajout d'un sujet
        $subject = new subjects();
        $form = $this->createForm(SubjectsFormType::class, $subject);
        $form->handleRequest($request);

        // Si le formulaire est validé 
        if ($form->isSubmitted() && $form->isValid()) {

             // relates this product to the category
            $subject->setUser($users);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();
        }

        return $this->render('users/profil.html.twig', [
            'subjectsform' => $form->createView(),
            // 'users' => $users,
        ]);
    }



    public function index(Request $request)
    {
        
    }
}
