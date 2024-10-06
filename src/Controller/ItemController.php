<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/new', name: 'item_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setCreatedBy($this->getUser());

            $image = $form->get('image')->getData();
            if ($image) {
                $photoDir = $this->getParameter('photo_dir') . DIRECTORY_SEPARATOR . 'items';
                $filesystem = new Filesystem();
                if (!$filesystem->exists($photoDir)) {
                    $filesystem->mkdir($photoDir, 0755);
                }
                $newFilename = uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('photo_dir'),
                    $newFilename
                );
                $item->setImage($newFilename);
            }

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_list');
        }

        return $this->render('item/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'item_list')]
    public function list(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findBy(['isActive' => true]);

        return $this->render('item/list.html.twig', [
            'items' => $items,
        ]);
    }
}
