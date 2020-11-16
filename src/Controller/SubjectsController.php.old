<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Subjects;


/**
 * Class ForumController
 * @package App\Controller
 * @Route("/sujects", name="subjects_")
 */
class SubjectsController extends AbstractController
{
    /**
     * @Route("/", name="subjects")
     */
    public function index(Request $request): Response
    {
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findAll();

        $slug = $request->query->get('slug');
        if($slug){
            $this->deleteSubject($slug);
        }

        return $this->render('subjects/index.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => $subjects,
        ]);
    }

    
    public function viewSubject() {
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findBy([],['createdAt' => 'desc']);
        // Champs de formulaire pour ajout d'un sujet
        $subject = new subjects();
        $form = $this->createForm(SubjectsFormType::class, $subject);
        $form->handleRequest($request);
        // L'objet Response contient toutes les informations qui doivent être renvoyées au client à partir d'une demande donnée.
        return compact('subjects','$form');
    }

    
    public function deleteSubject($slug) : Response {
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => $slug]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($subject);
        $entityManager->flush();
        // L'objet Response contient toutes les informations qui doivent être renvoyées au client à partir d'une demande donnée.
        return new RedirectResponse('/subjects');
    }

    /**
     * @Route("/subjects/createSubject", name="createSubject")
     */
    public function createSubject(Request $request) : Response {
         // ref entity
         $subject = new subjects();

         // utilise un formulaire symfony
         $form = $this->createForm(SubjectsFormType::class, $subject);
         // requete methodes http
         $form->handleRequest($request);
         // Si le formulaire est validé 
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajoute les données dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();
        }

        // L'objet Response contient toutes les informations qui doivent être renvoyées au client à partir d'une demande donnée.
        return new RedirectResponse('/subjects');
    }

    /**
     * @Route("/subjects/test01", name="test01")
     */
    public function test01() : Response {
        // get data
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => 'sujet03']);
        $subject->setDescription('Ceci est un test01');
        // manager for handle database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($subject);
        $entityManager->flush();
        // return a response for the client
        $route = '/forum/'.$subject->getSlug();
        return new response("<a href=".$route.">redirectToRoute</a>",
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/subjects/getSujet03", name="getSujet03")
     */
    public function getSujet03() : Response {
        $subject = $this->getDoctrine()->getRepository(Subjects::class)->findOneBy(['slug' => 'sujet03']);
        $subject->setDescription('Ceci est un test02');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($subject);
        $entityManager->flush();

        // L'objet Response contient toutes les informations qui doivent être renvoyées au client à partir d'une demande donnée.
        $response = new Response;
        $route = '/forum/'.$subject->getSlug();
        $response->setContent("<a href=".$route.">redirectToRoute</a>");
        // L'entete de la requete
        $response->headers->set('Content-Type', 'text/html');
        // Status de la requete code d'erreur
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        // Jeu de caracteres
        $response->setCharset('ISO-8859-1');
        // Eventuelllemnt utiliser la méthode pour corriger toute incompatibilité avec la spécification HTTP
        // $response->prepare($response);
        // L'envoi de la réponse au client 
        $response->send();
    }

    
}
