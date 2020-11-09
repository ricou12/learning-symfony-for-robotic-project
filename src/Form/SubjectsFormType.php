<?php

namespace App\Form;

use App\Entity\Subjects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubjectsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Titre du sujet',
                'required'=> true,
            ])
            ->add('description',TextareaType::class,[
                'attr'=> ['class'=> 'form-control',
                    'rows' => '5'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un commentaire',
                    ]),
                ],
                'label' => 'Description',
                'label_attr' => ['class' => 'mt-3'],
                'required' => true,
            ])
            ->add('save',SubmitType::class,[
                'attr' => ['class' => 'btn btn-success mt-3'],
                'label' => 'Créer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subjects::class,
        ]);
    }
}
