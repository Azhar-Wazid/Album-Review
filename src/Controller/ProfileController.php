<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ProfileType;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ReviewRepository $reviewRepo): Response
    {
        $user = $this->getUser();
        $reviews = $reviewRepo->findBy(['userID' => $user], ['createdAt' => 'DESC']);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }

    #[Route('/delete/review?{id}', name: 'app_delete_review')]
    public function deleteReview(Review $review, EntityManagerInterface $em, Request $request): Response
    {
        if ($review->getUserID() !== $this->getUser()) {
            $reroute = $this->redirectToRoute('app_view_album', ['id' => $review->getAlbumID()->getID()]);
        }
        else{
            $reroute = $this->redirectToRoute('app_profile');
        }

        if (!$this->isCsrfTokenValid('delete_review_' . $review->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $em->remove($review);
        $em->flush();

        return $reroute;
    }
}
