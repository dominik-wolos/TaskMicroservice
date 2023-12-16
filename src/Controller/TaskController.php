<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class TaskController extends AbstractController
{
    public function __construct(
        private Environment $twig,
    ){
    }

    #[Route(path: '/', name: 'app_api_song_show', methods: ['GET'])]
    public function homepage(): Response
    {
        return new Response($this->twig->render('task/index.html.twig'));
    }
}
