<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ContactFormType;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="sendMail")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactFormType::class,$contact);
        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid()){
            $this->addContact($contact);
            $this->sendMail($formContact);
            return new RedirectResponse('/forum');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formContact' => $formContact->createView(),
        ]);
    }

    public function addContact($contact){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contact);
        $entityManager->flush();
    }

    public function sendMail($formContact){
        $email = (new Email())
            ->from($formContact->get('email')->getData())
            ->to('dwwm2019@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('IHM Robotique - formulaire contact')
            // ->text($formContact->get('message')->getData());
            ->html('
                <p>'.$formContact->get('nom')->getData().'</p>
                <p>'.$formContact->get('prenom')->getData().'</p>
                <p>'.$formContact->get('tel')->getData().'</p>
                <p>'.$formContact->get('message')->getData().'</p>'
            );

            $this->mailer->send($email);
    }
}
