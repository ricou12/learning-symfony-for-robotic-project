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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder'=>'Nom *'
                ],
                'required'   => true,
            ])
            ->add('prenom',TextType::class,[
                'label'=>false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder'=>'Prénom *'
                ],
                'required'   => true,
            ])
            ->add('email',EmailType::class,[
                'label'=>false,
                'label_attr'=>['class'=>''],
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder'=>'Adresse mail *'
                ],
                'required'   => true,
            ])
            ->add('tel',TelType::class,[
                'label'=>false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder'=>'Téléphone'
                ],
                'required'   => false,
            ])
            ->add('message',TextareaType::class,[
                'label'=>false,
                'attr' => [
                    'rows' => '5','placeholder'=>'',
                    'class'=>'form-control mb-4',
                    'placeholder'=>'Laissez votre message ... *'
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
