<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message',TextareaType::class,[
                'attr'=> ['class'=> 'form-control',
                    'rows' => '5'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un commentaire',
                    ]),
                ],
                'label' => 'Message',
                'required' => true,
            ])
            ->add('save',SubmitType::class,[
                'attr' => ['class'=>'mt-3 btn btn-outline-success'],
                'label' =>'Répondre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
