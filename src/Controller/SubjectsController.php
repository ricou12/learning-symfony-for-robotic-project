<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subjects;

class SubjectsController extends AbstractController
{
    /**
     * @Route("/subjects", name="subjects")
     */
    public function index()
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $subjects = $this->getDoctrine()->getRepository(Subjects::class)->findAll();
        
        return $this->render('subjects/index.html.twig', [
            'controller_name' => 'SubjectsController',
            'subjects' => compact('subjects'),
        ]);
    }
}
