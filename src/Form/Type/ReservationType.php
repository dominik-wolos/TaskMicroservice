<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Reservation\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

final class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start_date', DateTimeType::class, [
            'label' => 'app.reservation.start_date',
            'date_widget' => 'single_text',
            'constraints' => [
                new GreaterThan(
                    new \DateTime("+3 hours")
                ),
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'app.common.description',
            'constraints' => [
                new Length([
                    'min' => 20,
                    'max' => 100,
                ]),
            ]
        ])
        ->add('name', TextType::class, [
            'label' => 'app.common.name',
            'constraints' => [
                new Length([
                    'min' => 4,
                    'max' => 20,
                ]),
            ]
        ])
        ->add('duration', IntegerType::class, [
            'label' => 'app.reservation.duration',
            'required' => false,
            'constraints' => [
                new Range([
                    'min' => 1,
                    'max' => 60,
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'app_reservation_type';
    }
}
