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

/**
 * Class ForumController
 * @package App\Controller
 * @Route("/forum", name="forum_")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        // Utilisateur avec session active
        $userSession = $this->get('security.token_storage')->getToken()->getUser();    
        
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findBy([],['createdAt' => 'desc']);
        // if (!$subjects) {
        //     // Si aucun article n'est trouvé, nous créons une exception
        //     throw $this->createNotFoundException(
        //         'Aucun sujet n\'a été publié'
        //     );
        // }

        // Champs de formulaire pour ajout d'un sujet
        $subject = new subjects();
        $form = $this->createForm(SubjectsFormType::class, $subject);
        $form->handleRequest($request);

        // Si le formulaire ajout d'un sujet est validé 
        if ($form->isSubmitted() && $form->isValid()) {
            // relates this product to the category
           $subject->setUser($userSession);

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($subject);
           $entityManager->flush();
            // return new RedirectResponse($this->urlGenerator->generate('forum_index'));
           return $this->redirectToRoute('forum_index');
       }

        return $this->render('forum/forum.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => $subjects,
            'userSession' => $userSession,
            'subjectsform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="subject")
     */
    public function getSubjects(Request $request, $slug): Response {
        // Utilisateur avec session active
        $userSession = $this->get('security.token_storage')->getToken()->getUser();  

        // On récupère l'article correspondant au slug
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => $slug]);
        if(!$subject){
            // Si aucun article n'est trouvé, nous créons une exception
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
            $test = '/forum/'.$subject->getSlug();
            return $this->redirectToRoute($test);
        }


        // Si l'article existe nous envoyons les données à la vue
        return $this->render('forum/subject.html.twig', [
            'subject' => $subject,
            'commentsForm' => $formComments->createView(),
        ]);
    }
}
