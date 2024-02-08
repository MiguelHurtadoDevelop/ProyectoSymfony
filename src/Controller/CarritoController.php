<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Productos;
use App\Repository\ProductosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CarritoController extends AbstractController
{
    #[Route('/carrito', name: 'app_carrito')]
    public function index(): Response
    {
        return $this->render('carrito/index.html.twig', [
            'controller_name' => 'CarritoController',
        ]);
    }

    #[Route('/carrito/add/{id}', name: 'app_carrito_add')]
    public function add($id, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $productosRepository = $entityManager->getRepository(Productos::class);
        $producto = $productosRepository->findOneById($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No se ha encontrado el producto con el id: ' . $id
            );
        }

        $cart = $session->get('cart', []);
        $cart[] = $producto;
        $session->set('cart', $cart);

        return $this->render('carrito/carrito.html.twig', [
            'carrito' =>  $cart,
        ]);
    }
}