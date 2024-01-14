<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'attr' => [
                    'name' => '_username',
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'name' => '_password',
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'app.login.login'])
        ;
    }
}
