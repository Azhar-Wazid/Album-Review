<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\CreateAlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CreateAlbumController extends AbstractController
{
    #[Route('/create/album', name: 'app_create_album')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,#[Autowire('%kernel.project_dir%/public/uploads/cover_image')] string $uploadsDir): Response
    {
        $album = new Album();
        $form = $this->createForm(CreateAlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album->setUserID($this->getUser());
            $artistName = $form->get('artistID')->getData();

            $artist = $entityManager->getRepository(Artist::class)->findOneBy(['name' => $artistName]);
            if (!$artist) {
                $artist = new Artist();
                $artist->setName($artistName);
                $entityManager->persist($artist);
            }

            $coverFile = $form->get('coverImage')->getData();
            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $uploadsDir,
                        $newFilename
                    );
                } catch (FileException $e) {}

                $album->setCoverImage($newFilename);
            }

            $album->setArtistID($artist);
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('app_add_track',[
                'id' => $album->getId(),
            ]);
        }

        return $this->render('create_album/index.html.twig', [
            'createAlbumForm' => $form,
        ]);
    }
}
