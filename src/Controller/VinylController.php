<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VinylController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage', methods: ['GET'])]
    public function homepage(): Response
    {
        $tracks = [
            ['artist' => 'The Beatles ', 'song' => ' Abbey Road',],
            ['artist' => 'The Beatles ', 'song' => ' Sgt. Pepper\'s Lonely Hearts Club Band',],
            ['artist' => 'The Beatles ', 'song' => ' Revolver',],
            ['artist' => 'The Beatles ', 'song' => ' Rubber Soul',],
            ['artist' => 'The Beatles ', 'song' => ' The Beatles (White Album)',],
            ['artist' => 'The Beatles ', 'song' => ' Magical Mystery Tour',],
            ['artist' => 'The Beatles ', 'song' => ' Let It Be',],
            ['artist' => 'The Beatles ', 'song' => ' Please Please Me',],
            ['artist' => 'The Beatles ', 'song' => ' With The Beatles',],
            ['artist' => 'The Beatles ', 'song' => ' A Hard Day\'s Night',],
        ];

        return $this->render('vinyl/homepage.html.twig', [
            'title' => 'PB & Jams',
            'tracks' => $tracks,
        ]);
    }

    #[Route(path: '/browse/{slug}', name: 'app_vinyl_browse', methods: ['GET'])]
    public function browse(string $slug = null): Response
    {
        $genre = $slug ? ucwords(str_replace('-', ' ', $slug)) : null;

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
        ]);
    }
}
