<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Entity\Users;
use App\Repository\SubjectsRepository;
use App\Repository\CommentsRepository;
use App\Form\EditUserFormType;
use App\Form\SubjectsFormType;
use App\Form\CommentsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/utilisateurs/{sort}/{direction}/{page}", name="users", methods={"GET"})
     */
    public function index($sort='id', $direction='ASC', $page='1', UsersRepository $users, PaginatorInterface $paginator)
    {
        $donnees = $users->findBy([],[$sort => $direction]);

        $usersPagination = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $page, // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
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
            return $this->redirectToRoute('admin_users');
        }
        
        return $this->render('admin/edituser.html.twig', [
            'author'=> $user->getAuthor(),
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
        return $this->redirectToRoute('admin_users');
    }

    // SUJETS FORUM //

    /**
     * @Route("/sujets/{id}/{page}", name="user_subjects")
     */
    public function showSubjects($id, $page='1', UsersRepository $users, PaginatorInterface $paginator){
        $user = $users->find($id);
        $allSubjects = $user->getSubjects();

        $usersPagination = $paginator->paginate(
            $allSubjects,
            $page,
            10
        );

        /* PERSONALISER POUR LE RENDU */
        $usersPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);

        return $this->render('admin/subjects.html.twig', [
            'user' => $user,
            'subjects' => $allSubjects,
            'usersPagination' => $usersPagination,
        ]);
    }

    /**
     * @Route("/sujet/{slug}/{page}", name="user_subject")
     */
    public function showSubject(SubjectsRepository $subjects, $slug, $page='1', PaginatorInterface $paginator)
    {
        // On récupère l'article correspondant au slug
        $subject = $subjects->findOneBy(['slug' => $slug]);
        $comments = $subject->getComments();
        if(!$subject){
            // Si aucun sujet n'est trouvé, nous créons une exception
            throw $this->createNotFoundException('L\'article n\'existe pas');
        } 
        
        $subjectPagination = $paginator->paginate(
            $comments,
            $page,
            10
        );

        /* PERSONALISER POUR LE RENDU */
        $subjectPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);

        // Si l'article existe nous envoyons les données à la vue
        return $this->render('admin/subject.html.twig', [
            'subject' => $subject,
            'subjectPagination' => $subjectPagination,
        ]);
    }

    /**
     * @Route("/sujet/modifier/{slug}", name="update_subject")
     */
    public function editSubject($slug, Request $request, SubjectsRepository $SubjectsRepository)
    {
        $subject = $SubjectsRepository->findOneBy(['slug'=> $slug]);
        $form = $this->createForm(SubjectsFormType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute( 'admin_user_subjects',['id' => $subject->getUser()->getId()] );
        }
        
        return $this->render('admin/editSubject.html.twig', [
            'author'=> $subject->getUser()->getAuthor(),
            'subjectForm' => $form->createView(),
        ]);
    }

    /**
     * @route("/sujet/supprimer/{slug}", name="delete_subject")
     */
    public function deleteSubject($slug, SubjectsRepository $subjects) {
        $subject = $subjects->findOneBy(['slug' => $slug]);

        if($subject)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();

        return $this->redirectToRoute('admin_user_subjects',['id'=>$subject->getUser()->getId()]);
    }


    // COMMENTAIRES FORUM //

    /** 
     * @Route("/commentaires/{id}/{sort}/{direction}/{page}", name="user_comments")
     */
    public function showComments($id, $sort='id', $direction='ASC', $page='1', PaginatorInterface $paginator,CommentsRepository $commentsRepository){
        // $user = $users->find($id);
        // $comments = $user->getComments();
        $comments = $commentsRepository->findBy(['user' => $id], [$sort => $direction]);

        $commentsPagination = $paginator->paginate(
            $comments,
            $page,
            10
        );

        /* PERSONALISER POUR LE RENDU */
        $commentsPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);


        return $this->render('admin/comments.html.twig', [
            // 'user' => $user,
            'commentsPagination' => $commentsPagination,
        ]);
    }

    /**
     * @Route("/commentaire/modifier/{slugComment}/{slugSubject}", name="update_comment")
     */
    public function editComment($slugComment, $slugSubject = null, Request $request, CommentsRepository $CommentsRepository)
    {
        $Comment = $CommentsRepository->findOneBy(['slug'=> $slugComment]);
        $form = $this->createForm(CommentsFormType::class, $Comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* RECUPERE LES PARMETRE DE LA ROUTE
                $route = $request->attributes->get('_route');
                $params = $request->attributes->get('_route_params');
            */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Comment);
            $entityManager->flush();

            if(is_null( $slugSubject )){
                // Redirige vers la liste des commentaires lié à un utilisateur
                return $this->redirectToRoute('admin_user_comments',['id' => $Comment->getUser()->getId()]);
            } else {
                // Redirige vers le sujet lié à un utilisateur
               return $this->redirectToRoute('admin_user_subject',['slug' => $slugSubject]); 
            }
        }
        
        return $this->render('admin/editComment.html.twig', [
            'author'=> $Comment->getUser()->getAuthor(),
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @route("/commentaire/supprimer/{slugComment}/{slugSubject}", name="delete_comment")
     */
    public function deleteComment($slugComment, $slugSubject = null, CommentsRepository $Comments) {
        $Comment = $Comments->findOneBy(['slug' => $slugComment]);

        if($Comment)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Comment);
            $entityManager->flush();

        if(is_null( $slugSubject )){
            // Redirige vers la liste des commentaires lié à un utilisateur
            return $this->redirectToRoute('admin_user_comments',['id' => $Comment->getUser()->getId()]);
        } else {
            // Redirige vers le sujet lié à un utilisateur
           return $this->redirectToRoute('admin_user_subject',['slug' => $slugSubject]); 
        }
        
    }
   
}