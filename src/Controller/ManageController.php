<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\User;
use App\Form\ManageUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[AllowDynamicProperties] class ManageController extends AbstractController
{
    public function __construct(#[Autowire('%photo_dir%')] string $photoDir)
    {
        $this->photoDir = $photoDir;
    }

    #[Route('/manage/edit', name: 'app_manage')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $form = $this->createForm(ManageUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($photo = $form->get('profile_picture')->getData()) {
                $fileName = uniqid() . '.' . $photo->guessExtension();
                $filesystem = new Filesystem();
                if (!$filesystem->exists($this->photoDir)) {
                    $filesystem->mkdir($this->photoDir, 0755);
                }
                $photo->move($this->photoDir, $fileName);
                $filesystem->chmod($this->photoDir . '/' . $fileName, 0644);
                $oldFile = $user->getProfilePicture();
                $user->setProfilePicture($fileName);

                if (file_exists($this->photoDir . '/' . $user->getProfilePicture())) {
                    unlink($this->photoDir . '/' . $oldFile);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated.');

            return $this->redirectToRoute('app_manage');
        }

        return $this->render('manage/edit.html.twig', [
            'manageUserForm' => $form->createView(),
        ]);
    }
}
