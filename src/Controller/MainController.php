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
     * @Route("/error", name="error404")
     */
    public function error404()
    {
        return $this->render('error/error.html.twig', []);
    }

    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionsLegales()
    {
        return $this->render('rgpd/mentionsLegales.html.twig', ['title' => 'IHM-Robotique']);
    }

    /**
     * @Route("/robot/creer-un-hotspot-WiFi", name="hotPost")
     */
    public function hotPost()
    {
        return $this->render('bot/hotPost/hotPost.html.twig', []);
    }

    /**
     * @Route("/robot/installer-et-configurer-une-camera", name="camera")
     */
    public function rpiCam()
    {
        return $this->render('bot/rpiCam/rpiCam.html.twig', []);
    }

    /**
     * @Route("/robot/code-source-arduino", name="robot_codeSource")
     */
    public function robotCodeSource()
    {
        return $this->render('bot/codeSource/codeSource.html.twig', []);
    }

    /**
     * @Route("/robot/liste-des-composants", name="robot_composant")
     */
    public function robotComposant()
    {
        return $this->render('bot/composants/composants.html.twig', []);
    }

    /**
     * @Route("/telecommande/solution-materiel-et-logiciel", name="technos_web")
     */
    public function technos()
    {
        return $this->render('remoteControl/technos/technos.html.twig', []);
    }

    /**
     * @Route("/telecommande/telecommande-web", name="interfaceWeb")
     */
    public function interfaceWeb()
    {
        return $this->render('remoteControl/interfaceWeb/interfaceWeb.html.twig', []);
    }

    /**
     * @Route("/telecommande/liste-des-composants", name="telecom_composant")
     */
    public function telecomComposant()
    {
        return $this->render('remoteControl/composants/composants.html.twig', []);
    }

    /**
     * @Route("/telecommande/emetteur-recepteur-radio", name="emetteur_radio")
     */
    public function emetteurRecepteur()
    {
        return $this->render('remoteControl/emetteurRecepteur/emetteurRecepteur.html.twig', []);
    }

    /**
     * @Route("/telecommande/diode-electroluminescente", name="led")
     */
    public function led()
    {
        return $this->render('remoteControl/led/led.html.twig', []);
    }
   
    /**
     * @Route("/telecommande/code-source-arduino", name="telecom_codeSource")
     */
    public function telecomCodeSource()
    {
        return $this->render('remoteControl/codeSource/codeSource.html.twig', []);
    }

}
