<?php

namespace App\Controller;

use App\Entity\Pedidosproductos;
use App\Form\PedidosproductosType;
use App\Repository\PedidosproductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pedidosproductos')]
class PedidosproductosController extends AbstractController
{
    #[Route('/', name: 'app_pedidosproductos_index', methods: ['GET'])]
    public function index(PedidosproductosRepository $pedidosproductosRepository): Response
    {
        return $this->render('pedidosproductos/index.html.twig', [
            'pedidosproductos' => $pedidosproductosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pedidosproductos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pedidosproducto = new Pedidosproductos();
        $form = $this->createForm(PedidosproductosType::class, $pedidosproducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedidosproducto);
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidosproductos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidosproductos/new.html.twig', [
            'pedidosproducto' => $pedidosproducto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidosproductos_show', methods: ['GET'])]
    public function show(Pedidosproductos $pedidosproducto): Response
    {
        return $this->render('pedidosproductos/show.html.twig', [
            'pedidosproducto' => $pedidosproducto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedidosproductos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedidosproductos $pedidosproducto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PedidosproductosType::class, $pedidosproducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidosproductos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidosproductos/edit.html.twig', [
            'pedidosproducto' => $pedidosproducto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidosproductos_delete', methods: ['POST'])]
    public function delete(Request $request, Pedidosproductos $pedidosproducto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedidosproducto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pedidosproducto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pedidosproductos_index', [], Response::HTTP_SEE_OTHER);
    }
}
