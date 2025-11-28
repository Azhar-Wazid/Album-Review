<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class EditProfileController extends AbstractController
{
    #[Route('/edit/profile', name: 'app_edit_profile')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger,#[Autowire('%kernel.project_dir%/public/uploads/profile_picture')] string $uploadsDir): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverFile = $form->get('profilePicture')->getData();
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

                $user->setProfilePicture($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('edit_profile/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
