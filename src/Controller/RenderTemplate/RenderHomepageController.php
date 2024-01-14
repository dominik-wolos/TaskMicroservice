<?php

declare(strict_types=1);

namespace App\Controller\RenderTemplate;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class RenderHomepageController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('homepage.html.twig');
    }
}
