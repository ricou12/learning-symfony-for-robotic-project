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
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class SubjectsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'row_attr'=>['class'=>'form-group'],
                'attr' => ['class' => 'form-control'],
                'label' => 'Quelle est votre question ?',
                'required'=> true,
            ])
            ->add('description',CKEditorType::class,[
                'row_attr'=>['class'=>'form-group'],
                'attr'=> ['class'=> 'form-control',
                    'rows' => '5'
                ],
                'label' => 'Décrivez ',
                'label_attr' => ['class' => ''],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un commentaire',
                    ]),
                ],
            ])
            ->add('save',SubmitType::class,[
                'attr' => ['class' => 'btn btn-success mt-3'],
                'label' => 'Poster',
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
