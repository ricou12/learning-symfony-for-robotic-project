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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="sendMail")
     */
    public function index(Request $request,SluggerInterface $slugger, MailerInterface $mailer)
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
                // Renomme la pièce jointe
                $originalFilename = pathinfo($docFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Ceci est nécessaire pour inclure en toute sécurité le nom du fichier dans l'URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$docFile->guessExtension();

                // Déplace le fichier vers le dossier ou les documents sont stockés
                try
                {
                    $docFile->move(
                        $this->getParameter('docs_directory'),
                        $newFilename
                    );
                    // Met à jour la propriété pour stocker le nom du fichier PDF ou jpg
                    $copyFile = $this->getParameter('docs_directory').'/'.$newFilename;
                    $contact->setFilePath($copyFile);
                }
                catch (FileException $e)
                {
                    // ... gérer l'exception si quelque chose se produit pendant le téléchargement du fichier
                    $copyFile =null;
                }

            } else {
                $copyFile =null;
            }

            // peristence des données
            $this->persistContact($contact);
            // Envoi de l'email
            $this->sendMail($formContact, $mailer, $copyFile);

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

    public function sendMail($formContact, $mailer, $downloadFile)
    {
        $email = (new Email())
            ->from($formContact->get('email')->getData())
            ->to('lanzae32@gmail.com')
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
            if(!is_null($downloadFile)){
                $email->attachFromPath($downloadFile);
            }
            $mailer->send($email);
    }
}
