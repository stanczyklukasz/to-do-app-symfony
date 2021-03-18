<?php


namespace App\Form\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być takie same',
                'options' => [
                    'attr' => ['class' => 'password-field']
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Hasło:',
                    'attr' => ['class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło:',
                    'attr' => ['class' => 'form-control']
                ]
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Zarejestruj się',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }
}