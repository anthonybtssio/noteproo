<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('email')
                // C'est une bonne pratique d'avoir une case à cocher pour les conditions
                ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'J\'accepte les conditions d\'utilisation',
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Vous devez accepter nos conditions.',
                        ]),
                    ],
                ])
                // ✅ Ce bloc gère la double saisie et la validation du mot de passe
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    // Ne pas stocker le mot de passe en clair dans l'entité
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                    // Contraintes de sécurité pour le mot de passe
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe.',
                        ]),
                        new Regex([
                            'pattern' => "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/",
                            'message' => "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial."
                        ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
