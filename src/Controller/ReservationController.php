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

final class ReservationController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface $formFactory,
    ){
    }

    #[Route(path: '/reservations', name: 'app_reservation_index', methods: ['GET'])]
    public function taskIndex(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Reservation::class);
        $reservations = $repository->findAll();

        return new Response($this->twig->render('Reservation/index.html.twig', [
            'reservations' => $reservations,
        ]));
    }

    #[Route(path: '/reservations/new', name: 'app_reservation_create', methods: ['GET', 'POST'])]
    public function taskCreate(Request $request): Response
    {
        $form = $this->formFactory->create(ReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();

            /**@var Reservation $reservation */
            $reservation->setReservedAt(new \DateTime());
            $startDate = $reservation->getStartDate();
            $reservation->setEndDate(
                \DateTime::createFromInterface($startDate)->modify(
                    sprintf('+%d minutes', $reservation->getDuration()
                )
            ));

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_reservation_index');
        }

        return new Response($this->twig->render('Reservation/Create/form.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        ]));
    }
}
