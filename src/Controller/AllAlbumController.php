<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AllAlbumController extends AbstractController
{
    #[Route('/all/album', name: 'app_all_album')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();

        return $this->render('all_album/index.html.twig', [
            'albums' => $albums,
        ]);
    }
}
