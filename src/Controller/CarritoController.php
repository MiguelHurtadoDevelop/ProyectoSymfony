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
    public function index(SessionInterface $session): Response
    {
        
        $cart = $session->get('cart', []);

        return $this->render('carrito/carrito.html.twig', [
            'carrito' =>  $cart,
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

        if($producto->getStock() == 0){
            throw $this->createNotFoundException(
                'No hay stock del producto con el id: ' . $id
            );
        }

        $productoCarrito = [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'precio' => $producto->getPrecio(),
            'imagen' => $producto->getImagen(),
            'cantidad' => 1
        ];
        $cart = $session->get('cart', []);
        $cart[] = $productoCarrito;
        $session->set('cart', $cart);

        return $this->render('carrito/carrito.html.twig', [
            'carrito' =>  $cart,
        ]);
    }

    #[Route('/carrito/borrar/{id}', name: 'app_carrito_borrar')]
    public function borrar($id,EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $cart = array_filter($cart, function ($producto) use ($id) {
            return $producto['id'] != $id;
        });

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_carrito');
    }
}