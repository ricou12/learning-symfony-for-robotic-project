<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Nom *'
                ],
                'required'   => true,
                'label'=>'Nom ou pseudo',
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' =>'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un e-mail',
                    ]),
                ],
                'required' => true,
                'label' => 'Email',
                'label_attr' => ['class'=>'mt-4'],
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => ['class'=>'form-group'],
                'choices' => [
                    'Utilisateur' =>'ROLE_USER',
                    'Editeur' => 'ROLE_EDITOR',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return [ 'class' => 'ml-4 mr-1'];
                },
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles',
                'label_attr' => ['class'=>'mt-4'],
            ])
            ->add('valider', SubmitType::class,[
                'attr'=>['class'=>'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
