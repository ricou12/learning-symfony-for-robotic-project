<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Subjects;
use App\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use app\Form\UsersFormType;
use app\Form\RegistrationFormType;
use App\Form\CommentsFormType;
use App\Form\SubjectsFormType;

/**
 * Class ForumController
 * @package App\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    public function adminDashboard()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }

    

    /**
     * @Route("/", name="listUsers")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        if(!$request->query->get('sort')){
            $donnees = $this->getDoctrine()->getRepository(Users::class)->findBy([],['author'=>'ASC']);
        } else {
             $donnees = $this->getDoctrine()->getRepository(Users::class)->findBy([],[$request->query->get('sort') => $request->query->get('direction')]);
        }

        $users = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        /* PERSONALISER POUR LE RENDU
        align: 'left', 'center', or 'right'. By default align is not modified
        size: 'small', 'medium', or 'large'. By default, size is not modified
        rounded: true or false. By default it's false
        */
        $users->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);

    }

    /**
     * @Route("/user/delete{id}", name="user_delete")
     */
    public function deleteUser(){

    }

    /**
     * @Route("/user/update", name="user_update")
     */
    public function updateUser(){
        //  // formulaire pour l'ajout d'un nouveau sujet
        //  $subject = new subjects();
        //  $form = $this->createForm(SubjectsFormType::class, $subject);
        //  $form->handleRequest($request);

        //   // Si le formulaire est validé on ajoute le nouveau sujet
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($subject);
        //     $entityManager->flush();
        //     return new RedirectResponse('/admin');
        // }
    }
}