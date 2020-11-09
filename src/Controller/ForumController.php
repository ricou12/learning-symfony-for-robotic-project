<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Comments;
use App\Form\CommentsFormType;
use App\Entity\Subjects;
use App\Form\SubjectsFormType;
use App\Form\EditUserFormType;
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
     * @Route("/", name="subjects")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Utilisateur avec session active
        $userSession = $this->get('security.token_storage')->getToken()->getUser();      
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findBy([],['createdAt' => 'ASC']);

        $subjects = $paginator->paginate(
            $subjects, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        $subjects->setCustomParameters([
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
            return new RedirectResponse('/forum');
        }

        return $this->render('forum/subjects.html.twig', [
            'subjects' => $subjects,
            'subjectsform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sujet/{slug}", name="subject")
     */
    public function getSubject(Request $request, $slug): Response 
    {
         // Utilisateur avec session active
         $userSession = $this->get('security.token_storage')->getToken()->getUser();    

        // On récupère l'article correspondant au slug
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => $slug]);
        if(!$subject){
            // Si aucun sujet n'est trouvé, nous créons une exception
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }       

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

    /**
     * @route("/comments/delete/{slug}", name="deleteComment")
     */
    public function deleteComment($slug) {
        // Utilisateur avec session active

        // METHODE GET : ajouter au parametre de la fonction Request $request 
        // Supprime le sujet à partir du slug

        // $slug = $request->query->get('slug');
        // if($slug ){
        //     $this->deleteSubject($slug);
        //     return new RedirectResponse('/forum');
        // }

        $userSession = $this->get('security.token_storage')->getToken()->getUser();

        $comment = $this->getDoctrine()->getRepository(Comments::class)->findOneBy(['slug' => $slug]);

        if($$comment && $userSession->getUsername()  == $comment->getUser()->getEmail() ){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('subject',['slug']);
    }
    
}