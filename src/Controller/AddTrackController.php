<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Track;
use App\Form\AddMultipleTrackType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddTrackController extends AbstractController
{
    #[Route('/add/track?{id}', name: 'app_add_track')]
    public function index(Request $request, Album $album, EntityManagerInterface $em): Response
    {
        if ($album->getTracks()->isEmpty()) {
            $album->addTrack(new Track());
        }

        $form = $this->createForm(AddMultipleTrackType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($album->getTracks() as $track) {
                $track->setAlbumID($album);
                $em->persist($track);
            }
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('add_track/index.html.twig', [
            'addTrackForm' => $form,
            'album' => $album,
        ]);
    }
}
