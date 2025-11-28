<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserRoleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditUserRolesController extends AbstractController
{
    #[Route('/edit/user/roles', name: 'app_edit_user_roles')]
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditUserRoleType::class);
        $form->handleRequest($request);
        $successMessage = null;
        $errorMessage = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $user = $userRepository->findOneBy(['username' => $username]);
            if (!$user) {
                $errorMessage = 'User not found';
            }
            else{
                $user->setRoles($form->get('roles')->getData());
                $em->flush();
                $successMessage = 'Roles updated';
            }
        }
        return $this->render('edit_user_roles/index.html.twig', [
            'form' => $form,
            'success' => $successMessage,
            'error' => $errorMessage
        ]);
    }
}
