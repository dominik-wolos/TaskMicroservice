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

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('Reservation/Create/form.html.twig', [
            'form' => $form->createView()
        ], $response);
    }

    #[Route(path: '/reservations/{id}/edit', name: 'app_reservation_update', methods: ['GET', 'POST'])]
    public function taskUpdate(Request $request): Response
    {
        $id = $request->get('id');

        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            return $this->redirectToRoute('app_reservation_index');
        }

        $form = $this->formFactory->create(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_reservation_index');
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('Reservation/Update/form.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ], $response);
    }

    #[Route(path: '/reservations/{id}/delete', name: 'app_reservation_delete', methods: ['GET', 'POST'])]
    public function taskDelete(Request $request): Response
    {
        $id = $request->get('id');

        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw new \RuntimeException(sprintf('Reservation with id %d not found', $id));
        }

        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_reservation_index');
    }
}
