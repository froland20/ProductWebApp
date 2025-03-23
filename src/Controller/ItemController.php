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

    #[Route('/list', name: 'item_list')]
    #[IsGranted('ROLE_USER')]
    public function list(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAllByUser(['isActive' => true]);

        return $this->render('item/list.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/details/{id}', name: 'item_details', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function details(int $id, ItemRepository $itemRepository): Response
    {
        $item = $itemRepository->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $item->getId(),
            'name' => $item->getName(),
            'shortDescription' => $item->getShortDescription(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'currency' => $item->getCurrency()->getSymbol(),
            'image' => $item->getImage(),
            'createdBy' => $item->getCreatedBy()->getFirstName() . ' ' . $item->getCreatedBy()->getLastName()
        ]);
    }
}
