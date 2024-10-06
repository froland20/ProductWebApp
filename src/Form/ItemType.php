<?php

namespace App\Form;

use App\Entity\Currency;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('short_description', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', TextType::class)
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => function(Currency $currency) {
                    return $currency->getName() . ' (' . $currency->getSymbol() . ')';
                },
            ])
            ->add('image', FileType::class, [
                'label' => 'Item Image (215x215)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('isActive', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
