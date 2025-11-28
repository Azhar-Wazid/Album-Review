<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Review;
use App\Form\AddReviewType;
use App\Form\EditAlbumType;
use App\Repository\AlbumRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ViewAlbumController extends AbstractController
{
    #[Route('/view/album?{id}', name: 'app_view_album')]
    public function index(AlbumRepository $albumRepo, ReviewRepository $reviewRepo, int $id, Request $request, EntityManagerInterface $em, SluggerInterface $slugger,#[Autowire('%kernel.project_dir%/public/uploads/cover_image')] string $uploadsDir): Response
    {
        $album = $albumRepo->find($id);
        $tracks = $album->getTracks();
        $reviews = $reviewRepo->findBy(['albumID' => $album], ['createdAt' => 'DESC']);
        $averageScore = $reviewRepo->getAverageScoreByAlbum($album);

        $newReview = new Review();

        $form = $this->createForm(AddReviewType::class, $newReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newReview->setUserID($this->getUser());
            $newReview->setAlbumID($album);

            $em->persist($newReview);
            $em->flush();

            return $this->json([
                'success' => true,
                'reviewScore' => $newReview->getReviewScore(),
                'reviewDetails' => $newReview->getReviewDetails(),
            ]);
        }

        $editImageForm = $this->createForm(EditAlbumType::class, $album);
        $editImageForm->handleRequest($request);

        if ($editImageForm->isSubmitted() && $editImageForm->isValid()) {
            $coverFile = $editImageForm->get('coverImage')->getData();
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
            $em->flush();
            return $this->redirectToRoute('app_view_album', ['id' => $album->getId()]);
        }


        return $this->render('view_album/index.html.twig', [
            'album' => $album,
            'tracks' => $tracks,
            'reviews' => $reviews,
            'averageReviewScore' => $averageScore,
            'form' => $form,
            'editImageForm' => $editImageForm,

        ]);
    }

    #[Route('/delete/album?{id}', name: 'app_delete_album')]
    public function deleteAlbum(Album $album, EntityManagerInterface $em, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('delete_album_' . $album->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $em->remove($album);
        $em->flush();

        return $this->redirectToRoute('app_all_album');
    }
}
