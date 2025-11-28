<?php

namespace App\Controller;

use App\Form\EditReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditReviewController extends AbstractController
{
    #[Route('/edit/review?{id}', name: 'app_edit_review')]
    public function index(ReviewRepository $reviewRepository, int $id, Request $request, EntityManagerInterface $em): Response
    {
        $review = $reviewRepository->find($id);
        $form = $this->createForm(EditReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('edit_review/index.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }
}
