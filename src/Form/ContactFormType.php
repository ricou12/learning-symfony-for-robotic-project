<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'row_attr' => ['class' => 'form-group'],
                'label'=>false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Nom *'
                ],
                'required'   => true,
            ])
            ->add('prenom',TextType::class,[
                'row_attr' => ['class' => 'form-group'],
                'label'=>false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Prénom *'
                ],
                'required'   => true,
            ])
            ->add('email',EmailType::class,[
                'row_attr' => ['class' => 'form-group'],
                'label'=>false,
                'label_attr'=>['class'=>''],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Adresse mail *'
                ],
                'required'   => true,
            ])
            ->add('tel',TelType::class,[
                'row_attr' => ['class' => 'form-group'],
                'label'=>false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Téléphone'
                ],
                'required'   => false,
            ])
            ->add('message',TextareaType::class,[
                'row_attr' => ['class' => 'form-group'],
                'label'=>false,
                'attr' => [
                    'rows' => '5','placeholder'=>'',
                    'class'=>'form-control mb-4',
                    'placeholder'=>'Laissez votre message ... *'
                ],
            ])
            ->add('filePath', FileType::class,[
                'row_attr' => ['class' => 'custom-file'],
                'help' => 'Vous pouvez joindre un document PDF !',
                'help_attr'=> ['class'=> 'font-italic text-secondary text-center'],
                'attr'=>['class'=>"custom-file-input"],
                'label'=>'Choisir un fichier...',
                'label_attr'=>['class'=>'custom-file-label'],
                 // Non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                 'mapped' => false,
                 'required' => false,

                 'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('save',SubmitType::class, [

                'label'=>'Envoyer',
                'attr' => ['class' => 'btn btn-success my-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
