<?php

namespace App\Form;

use App\Entity\Plat;
// use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
                ])
            ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('price', MoneyType::class)
            ->add('submit', SubmitType::class, array(
                'label' => 'Enregistrer'
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
