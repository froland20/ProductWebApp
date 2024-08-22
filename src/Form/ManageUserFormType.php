<?php

namespace App\Form;

use App\Entity\User;
use App\UserBundle\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ManageUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Keresztnév',
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Vezetéknév',
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => $this->getGenderChoices(),
                'choice_label' => function ($choice) {
                    return Gender::from($choice)->label();
                },
                'label' => 'Nem',
            ])
            ->add('profile_picture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '5000k']),
                ]
            ]);
    }

    private function getGenderChoices(): array
    {
        $choices = Gender::cases();
        $result = [];

        foreach ($choices as $choice) {
            $result[$choice->label()] = $choice->value;
        }

        return $result;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
