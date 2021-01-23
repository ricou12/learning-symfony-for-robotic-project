<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Form\ContactFormType;
use App\Entity\Contact;
// use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="sendMail")
     */
    public function index(Request $request, MailerInterface $mailer, FileUploader $fileUploader)
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactFormType::class,$contact);
        $formContact->handleRequest($request);

        if( $formContact->isSubmitted() && $formContact->isValid() )
        {
            // Requete get pour la pièce jointes
            $docFile = $formContact->get('filePath')->getData();

            //  Si une piéce jointe existe
            if($docFile)
            {
                $docFileFileName = $fileUploader->upload($docFile, $this->getParameter('docs_directory'));
                $contact->setFilePath($docFileFileName);
            } 

            // // peristence des données
            $this->persistContact($contact);
            // // Envoi de l'email
            $fileAttachement = $this->getParameter('docs_directory').'/'.$docFileFileName;
            $this->sendMail($formContact, $mailer, $fileAttachement);

            // redirection vers le forum
            return $this->redirectToRoute('forum_subjects');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formContact' => $formContact->createView(),
        ]);
    }

    public function persistContact($contact)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contact);
        $entityManager->flush(); 
    }

    public function sendMail($formContact, $mailer, $downloadFile=null)
    {
        $email = (new TemplatedEmail())
            ->from($formContact->get('email')->getData())
            ->to('lanzae32@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('IHM Robotique - formulaire contact')
            // ->text($formContact->get('message')->getData());
            // path of the Twig template to render
            ->htmlTemplate('contact/sendEmailForAdmin.html.twig')
             // pass variables (name => value) to the template
            ->context([
                'sending_date' => new \DateTime('NOW'),
                'lastname' => $formContact->get('nom')->getData(),
                'firstname' => $formContact->get('prenom')->getData(),
                'phone' => $formContact->get('tel')->getData(),
                'message' => $formContact->get('message')->getData(),
            ]);
            if(!is_null($downloadFile)){
                $email->attachFromPath($downloadFile);
            }
            try {
                // $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                throw $this->createNotFoundException('Une erreur c\'est produite. Impossible d\'envoyer l\'émail. ');
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
    }
}
