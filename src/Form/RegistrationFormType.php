<?php

namespace App\Form;

use App\Entity\User;
use App\UserBundle\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('plainFirstName', TextType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'first-name'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your first name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your first name should be at least {{ limit }} characters',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('plainLastName', TextType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'last-name'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter Last name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your last name should be at least {{ limit }} characters',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('plainGender', ChoiceType::class, [
                'mapped' => false,
                'choices' => $this->getGenderChoices(),
                'choice_label' => function ($choice) {
                    return Gender::from($choice)->label();
                },
                'placeholder' => 'Choose your gender',
            ])
            ->add('plainProfilePicture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '5000k']),
                ]
            ])
        ;
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
