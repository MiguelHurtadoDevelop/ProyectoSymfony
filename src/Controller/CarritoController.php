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
            'carrito' =>  $cart
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


        $cantidadAAnadir = 1;
        if ($producto->getStock() > 0 && $producto->getStock() >= $cantidadAAnadir) {

            if (!empty($cart)) {
                foreach ($cart as $key => $productoCarrito) {
                    if ($productoCarrito['id'] == $id) {

                        if (($productoCarrito['cantidad'] + $cantidadAAnadir) <= $producto->getStock()) {
                            $cart[$key]['cantidad'] += $cantidadAAnadir;
                            $cart[$key]['totalPorProducto'] = $cart[$key]['cantidad'] * $cart[$key]['precio'];
                            $session->set('cart', $cart);

                            return $this->render('carrito/carrito.html.twig', [
                                'carrito' => $cart,
                            ]);
                        } else {
                            $cart[$key]['cantidad'] = $producto->getStock();
                            $cart[$key]['totalPorProducto'] = $cart[$key]['cantidad'] * $cart[$key]['precio'];
                            $session->set('cart', $cart);
                            return $this->render('carrito/carrito.html.twig', [
                                'carrito' => $cart,
                                'error' => 'No hay suficiente stock',
                            ]);
                        }
                    }
                }
            }

            $productoCarrito = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'totalPorProducto' => $producto->getPrecio(),
                'imagen' => $producto->getImagen(),
                'cantidad' => $cantidadAAnadir,
            ];
            $cart[] = $productoCarrito;
            $session->set('cart', $cart);

            return $this->render('carrito/carrito.html.twig', [
                'carrito' => $cart,
            ]);
        } else {

            return $this->render('carrito/carrito.html.twig', [
                'carrito' => $cart,
                'error' => 'No hay suficiente stock',
            ]);
        }
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

    #[Route('/carrito/sumarCantidad/{id}', name: 'app_carrito_sumarCantidad')]
    public function sumarCantidad($id, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);

        foreach ($cart as $key => $productoCarrito) {
            if ($productoCarrito['id'] == $id) {
                
                $productosRepository = $entityManager->getRepository(Productos::class);
                $producto = $productosRepository->findOneById($id);

                if (!$producto) {
                    throw $this->createNotFoundException(
                        'No se ha encontrado el producto con el id: ' . $id
                    );
                }

                
                if ($producto->getStock() > ($productoCarrito['cantidad'])) {
                    $cart[$key]['cantidad']++;
                    $cart[$key]['totalPorProducto'] = $cart[$key]['cantidad'] * $cart[$key]['precio'];
                    $session->set('cart', $cart);

                    return $this->redirectToRoute('app_carrito');
                } else {
                    $cart[$key]['cantidad'] = $producto->getStock();
                    $cart[$key]['totalPorProducto'] = $cart[$key]['cantidad'] * $cart[$key]['precio'];
                    $session->set('cart', $cart);
                    return $this->render('carrito/carrito.html.twig', [
                        'carrito' => $cart,
                        'error' => 'No hay suficiente stock',
                    ]);
                }
            }
        }

        
        return $this->render('carrito/carrito.html.twig', [
            'carrito' => $cart,
            'error' => 'No se encontró el producto en el carrito',
        ]);
    }


    #[Route('/carrito/restarCantidad/{id}', name: 'app_carrito_restarCantidad')]
public function restarCantidad($id, SessionInterface $session, EntityManagerInterface $entityManager)
{
    $cart = $session->get('cart', []);

    foreach ($cart as $key => $productoCarrito) {
        if ($productoCarrito['id'] == $id) {
            if ($cart[$key]['cantidad'] > 0) {
                $cart[$key]['cantidad']--;
            }

            
            $cart[$key]['totalPorProducto'] = $cart[$key]['cantidad'] * $cart[$key]['precio'];

            if ($cart[$key]['cantidad'] == 0) {
                unset($cart[$key]);
            }

            $session->set('cart', $cart);

            return $this->redirectToRoute('app_carrito');
        }
    }

    return $this->redirectToRoute('app_carrito');
}

    #[Route('/carrito/vaciar', name: 'app_carrito_vaciar')]
    public function vaciar(SessionInterface $session){
        $session->remove('cart');
        return $this->redirectToRoute('app_carrito');
    }


}