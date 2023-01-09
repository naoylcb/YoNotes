<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'minlength' => '3',
                    'maxlength' => '30'
                ],
                'label' => 'Pseudo',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => '3', 'max' => '30']),
                ]
            ])
            ->add('password', RepeatedType::class, [
                 'type' => PasswordType::class,
                 'first_options' => [
                    'label' => 'Mot de passe'
                 ],
                 'second_options' => [
                    'label' => 'Confirmer Mot de passe'
                 ],
                 'invalid_message' => 'Les mots de passe ne correspondent pas.',
                 'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
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
