<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VinylController
{
    #[Route(path: '/', name: 'homepage', methods: ['GET'])]
    public function homepage(): Response
    {
        return new Response('Title: PB and Jams');
    }

    #[Route(path: '/browse/{slug}', name: 'browse', methods: ['GET'])]
    public function browse(string $slug = null): Response
    {
        if (null === $slug) {
            return new Response('All Vinyls');
        }

        $title = ucwords(str_replace('-', ' ', $slug));

        return new Response("Genre: $title");
    }
}
