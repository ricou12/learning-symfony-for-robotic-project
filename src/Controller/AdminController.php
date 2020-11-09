<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Entity\Users;
use App\Repository\SubjectsRepository;
use App\Entity\Subjects;
use App\Repository\CommentsRepository;
use App\Form\EditUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator


/**
 * Class ForumController
 * @package App\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="utilisateurs", methods={"GET"})
     */
    public function index(UsersRepository $users, Request $request, PaginatorInterface $paginator): Response
    {
        if($request->query->get('sort')){
            $sort = $request->query->get('sort');
            $direction = $request->query->get('direction');
            $page = $request->query->getInt('page', 1);
            $donnees = $users->findBy([],[$sort =>  $direction]);
            // $this->redirectToRoute('test', ['sort'=>$sort, 'direction'=>$direction,'page'=>$page ]);
        } else {
            $donnees = $users->findAll();
        }
        
        $usersPagination = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        /* PERSONALISER POUR LE RENDU
        align: 'left', 'center', or 'right'. By default align is not modified
        size: 'small', 'medium', or 'large'. By default, size is not modified
        rounded: true or false. By default it's false
        */
        $usersPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'usersPagination' => $usersPagination,
        ]);
    }

    // USTILISATEURS MODIFICATION ET SUPPRESSION //

    /**
     * @Route("/profil/modifier/{id}", name="user_update")
     */
    public function editUser($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }
        
        return $this->render('users/edituser.html.twig', [
            'title'=>"Editer le profil",
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/supprimer/{id}", name="user_delete")
     */
    public function deleteUser($id, UsersRepository $user)
    {
        $userDelete = $user->find($id);
        if($userDelete) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userDelete);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_utilisateurs');
    }

    // SUJETS FORUM //

    /**
     * @Route("/sujets/{id}", name="user_subjects")
     */
    public function showSubjects($id,UsersRepository $users){
        $user = $users->find($id);
        $subjects = $user->getSubjects();

        return $this->render('admin/subjects.html.twig', [
            'user' => $user,
            'subjects' => $subjects,
        ]);
    }

    /**
     * @route("/sujets/supprimer/{slug}/{id}", name="delete_subject")
     */
    public function deleteSubject($slug,$id, SubjectsRepository $subjects) {
        $subject = $subjects->findOneBy(['slug' => $slug]);

        if($subject)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();

        return $this->redirectToRoute('admin_user_subjects',['id'=>$id]);
    }


    // COMMENTAIRES FORUM //

    /**
     * @Route("/commentaires/{id}", name="user_comments")
     */
    public function showComments($id,UsersRepository $users){
        $user = $users->find($id);
        $comments = $user->getComments();

        return $this->render('admin/comments.html.twig', [
            'user' => $user,
            'comments' => $comments,
        ]);
    }

    /**
     * @route("/commentaires/supprimer/{slug}/{id}", name="delete_comment")
     */
    public function deleteComment($slug,$id, CommentsRepository $Comments) {
        $Comment = $Comments->findOneBy(['slug' => $slug]);

        if($Comment)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Comment);
            $entityManager->flush();

        return $this->redirectToRoute('admin_user_comments',['id'=>$id]);
    }

}