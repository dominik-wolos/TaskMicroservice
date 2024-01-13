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

final class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start_date', DateTimeType::class, [
            'label' => 'app.reservation.start_date',
            'date_widget' => 'single_text',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'app.common.description',
        ])
        ->add('name', TextType::class, [
            'label' => 'app.common.name',
        ])
        ->add('duration', IntegerType::class, [
            'label' => 'app.reservation.duration',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'app.common.submit',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'app_reservation_type';
    }
}
