<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
// use App\Entity\Users;
use App\Entity\Comments;
use App\Form\CommentsFormType;
use App\Entity\Subjects;
use App\Form\SubjectsFormType;

/**
 * Class ForumController
 * @package App\Controller
 * @Route("/forum", name="forum_")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="view")
     */
    public function index(Request $request): Response
    {
        // Utilisateur avec session active
        $userSession = $this->get('security.token_storage')->getToken()->getUser();
        
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findBy([],['createdAt' => 'desc']);
        // if (!$subjects) {
        //     // Si aucun sujet n'est trouvé, nous créons une exception
        //     throw $this->createNotFoundException(
        //         'Aucun sujet n\'a été publié'
        //     );
        // }

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

        return $this->render('forum/forum.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => $subjects,
            'subjectsform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subject/{slug}", name="subject")
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
     * @route("/subjects/delete/{slug}", name="deleteSubject")
     */
    public function deleteSubject($slug) {
        // Utilisateur avec session active

        $userSession = $this->get('security.token_storage')->getToken()->getUser();

        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => $slug]);

        if($subject && $userSession->getUsername()  == $subject->getUser()->getEmail() ){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }
        return $this->redirectToRoute('forum_view');
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