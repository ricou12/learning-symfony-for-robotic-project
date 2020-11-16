<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\CommentsRepository;
use App\Entity\Comments;
use App\Form\CommentsFormType;
use App\Repository\SubjectsRepository;
use App\Entity\Subjects;
use App\Form\SubjectsFormType;
use App\form\ForumUserFormType;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

/**
 * Class ForumController
 * @package App\Controller
 * @Route("/forum", name="forum_")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/sujets/{sort}/{direction}/{page}", name="subjects")
     */
    public function index($sort='createdAt', $direction='DESC', $page='1', Request $request, PaginatorInterface $paginator): Response
    {
        // Utilisateur avec session active
        $userSession = $this->get('security.token_storage')->getToken()->getUser();   
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findBy([],[$sort => $direction]);

        $subjectsPagination = $paginator->paginate(
            $subjects, // Requête contenant les données à paginer (ici nos articles)
            $page, // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

        $subjectsPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);

        // formulaire pour l'ajout d'un nouveau sujet
        $subject = new subjects();
        $form = $this->createForm(SubjectsFormType::class, $subject);
        $form->handleRequest($request);

        // Si le formulaire est validé on ajoute le nouveau sujet
        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setUser($userSession);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();
            return $this->redirectToRoute('forum_subjects');
        }

        return $this->render('forum/subjects.html.twig', [
            'subjectsPagination' => $subjectsPagination,
            'subjectsform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sujet/{slug}/{page}", name="subject")
     */
    public function getSubject($slug, $page='1', Request $request, SubjectsRepository $subjectsRepository, CommentsRepository $commentsRepository, PaginatorInterface $paginator): Response 
    {
         // Utilisateur avec session active
         $userSession = $this->get('security.token_storage')->getToken()->getUser();    

        // On récupère l'article correspondant au slug
        // $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => $slug]);
        $subject =  $subjectsRepository->findOneBy(['slug' => $slug]);
        $comments = $subject->getComments();
        // $comments = $commentsRepository->findOneBy([],['slug' => $slug]);

        $commentsPagination = $paginator->paginate(
            $comments, // Requête contenant les données à paginer (ici nos articles)
           $page, // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
           7 // Nombre de résultats par page
        );

        $commentsPagination->setCustomParameters([
            'align' => 'center',
            'size' => 'medium',
            'rounded' => true,
        ]);
        
        // $comments = $subject->getcom

        // Champs de formulaire pour ajout d'un commentaire
        $comment = new comments();
        $formComments = $this->createForm(CommentsFormType::class, $comment);
        $formComments->handleRequest($request);
        // Si le formulaire est validé 
        if ($formComments->isSubmitted() && $formComments->isValid()) {
            $comment->setUser($userSession);
            $comment->setSubject($subject);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('forum_subject',array('slug' => $slug));
        }

        // Si l'article existe nous envoyons les données à la vue
        return $this->render('forum/subject.html.twig', [
            'subject' => $subject,
            'commentsForm' => $formComments->createView(),
            'commentsPagination' => $commentsPagination,
        ]);
    }

    /**
     * @Route("/profil/modifier", name="update_user")
     */
    public function editUser(UserInterface $user, Request $request)
    {
        $form = $this->createForm(ForumUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('forum_subjects');
        }
        
        return $this->render('users/edituser.html.twig', [
            'title'=> 'Mes informations',
            'userForm' => $form->createView(),
        ]);
    }

    
    
}