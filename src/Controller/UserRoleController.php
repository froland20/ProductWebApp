<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserRoleController extends AbstractController
{
    #[Route('/user/role', name: 'app_user_role')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user_role/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/role/{id}/edit', name: 'edit_user_role', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editUserRole(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        RoleRepository $roleRepository
    ): Response {
        $roles = $roleRepository->findAll();

        if ($request->isMethod('POST')) {
            return $this->handleUserRoleUpdate($user, $request, $entityManager);
        }

        return $this->render('user_role/edit.html.twig', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    private function handleUserRoleUpdate(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $selectedRoles = $request->request->all('roles');
        if (is_array($selectedRoles)) {
            $user->setRoles($selectedRoles);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'A szerepkörök sikeresen frissítve.');
        } else {
            $this->addFlash('error', 'A szerepkörök mentése nem sikerült.');
        }

        return $this->redirectToRoute('app_user_role');
    }
}
