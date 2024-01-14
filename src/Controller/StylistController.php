<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation\Reservation;
use App\Form\Type\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class StylistController extends AbstractController
{
    public function __construct(
        private Environment $twig
    ){
    }

    #[Route(path: '/about', name: 'app_stylist_index', methods: ['GET'])]
    public function taskIndex(Request $request): Response
    {
        return new Response($this->twig->render('Stylists/index.html.twig', [
            'stylists' => $this->getStylists(),
        ]));
    }

    private function getStylists(): array
    {
        return $stylists = [
            [
                "name" => "Anna Rudolf",
                "age" => "55",
                "speciality" => "Nail Art",
                "working_hours" => "8-17",
            ],
            [
                "name" => "John Smith",
                "age" => "30",
                "speciality" => "Manicures",
                "working_hours" => "10-19",
            ],
            [
                "name" => "Emily Turner",
                "age" => "40",
                "speciality" => "Gel Nails",
                "working_hours" => "9-18",
            ],
            [
                "name" => "Carlos Martinez",
                "age" => "28",
                "speciality" => "Pedicures",
                "working_hours" => "12-21",
            ],
            [
                "name" => "Sophia Rodriguez",
                "age" => "35",
                "speciality" => "Nail Extensions",
                "working_hours" => "11-20",
            ],
            [
                "name" => "Michael Nguyen",
                "age" => "45",
                "speciality" => "Nail Polish",
                "working_hours" => "10-19",
            ],
        ];
    }
}
