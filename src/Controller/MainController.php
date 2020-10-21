<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', []);
    }

    /**
     * @Route("/error.html", name="error404")
     */
    public function error404()
    {
        return $this->render('error/error.html.twig', []);
    }

    /**
     * @Route("/mentions-legales.html", name="mentions_legales")
     */
    public function mentionsLegales()
    {
        return $this->render('rgpd/mentionsLegales.html.twig', ['title' => 'IHM-Robotique']);
    }

    /**
     * @Route("/robot/creer-un-hotspot-WiFi.html", name="hotPost")
     */
    public function hotPost()
    {
        return $this->render('robot/hotPost/hotPost.html.twig', []);
    }

    /**
     * @Route("/robot/installer-et-configurer-une-camera.html", name="camera")
     */
    public function rpiCam()
    {
        return $this->render('robot/rpiCam/rpiCam.html.twig', []);
    }

    /**
     * @Route("/robot/code-source-arduino.html", name="robot_codeSource")
     */
    public function robotCodeSource()
    {
        return $this->render('robot/codeSource/codeSource.html.twig', []);
    }

    /**
     * @Route("/robot/liste-des-composants.html", name="robot_composant")
     */
    public function robotComposant()
    {
        return $this->render('robot/composants/composants.html.twig', []);
    }

    /**
     * @Route("/telecommande/solution-materiel-et-logiciel.html", name="technos_web")
     */
    public function technos()
    {
        return $this->render('telecommande/technos/technos.html.twig', []);
    }

    /**
     * @Route("/telecommande/telecommande-web.html", name="interfaceWeb")
     */
    public function interfaceWeb()
    {
        return $this->render('telecommande/interfaceWeb/interfaceWeb.html.twig', []);
    }

    /**
     * @Route("/telecommande/liste-des-composants.html", name="telecom_composant")
     */
    public function telecomComposant()
    {
        return $this->render('telecommande/composants/composants.html.twig', []);
    }

    /**
     * @Route("/telecommande/emetteur-recepteur-radio.html", name="emetteur_radio")
     */
    public function emetteurRecepteur()
    {
        return $this->render('telecommande/emetteurRecepteur/emetteurRecepteur.html.twig', []);
    }

    /**
     * @Route("/telecommande/diode-electroluminescente.html", name="led")
     */
    public function led()
    {
        return $this->render('telecommande/led/led.html.twig', []);
    }
   
    /**
     * @Route("/telecommande/code-source-arduino.html", name="telecom_codeSource")
     */
    public function telecomCodeSource()
    {
        return $this->render('telecommande/codeSource/codeSource.html.twig', []);
    }

}
