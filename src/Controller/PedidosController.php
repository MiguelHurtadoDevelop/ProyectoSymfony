<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Form\PedidosType;
use App\Repository\PedidosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Productos;
use App\Entity\Pedidosproductos;


#[Route('/pedidos')]
class PedidosController extends AbstractController
{
    #[Route('/', name: 'app_pedidos_index', methods: ['GET'])]
    public function index(PedidosRepository $pedidosRepository): Response
    {
        return $this->render('pedidos/index.html.twig', [
            'pedidos' => $pedidosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pedido = new Pedidos();
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $pedido->setEnviado(0);
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_show', methods: ['GET'])]
    public function show(Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {   
        $Pedidosproductos = $entityManager->getRepository(Pedidosproductos::class)->findBy(['pedido' => $pedido]);
        
        $productos = [];
        
        foreach ($Pedidosproductos as $producto) {
            $productoEntity = $entityManager->getRepository(Productos::class)->find($producto->getProducto());

            if ($productoEntity) {
                $productos[] = [
                    'id' => $productoEntity->getId(),
                    'nombre' => $productoEntity->getNombre(),
                    'precio' => $productoEntity->getPrecio(),
                    'cantidad' => $producto->getUnidades(),
                    'totalPorProducto' => $producto->getUnidades() * $productoEntity->getPrecio(),
                ];
            }
        }

        return $this->render('pedidos/show.html.twig', [
            'pedido' => $pedido,
            'productos' => $productos,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_delete', methods: ['POST'])]
    public function delete(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        
        // Check if $pedido is set and the request method is POST
        if ($pedido && $request->isMethod('POST') && $this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {
            
            $LineasDePedido = $entityManager->getRepository(Pedidosproductos::class)->findByPedido($pedido->getId());
            
            foreach ($LineasDePedido as $linea) {
                $entityManager->remove($linea);
                $entityManager->flush();
            }
            
            $entityManager->remove($pedido);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/realizar-pedido/{total}', name: 'app_pedidos_realizar_pedido')]
    public function realizarPedido($total, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Obtener el carrito de la sesión
        $cart = $session->get('cart', []);

        // Verificar el stock de cada producto en el carrito
        foreach ($cart as $producto) {
            $productoEntity = $entityManager->getRepository(Productos::class)->find($producto['id']);

            // Verificar si la cantidad pedida supera el stock disponible
            if ($productoEntity && $producto['cantidad'] > $productoEntity->getStock()) {
                return $this->render('carrito/carrito.html.twig', [
                    'carrito' => $cart,
                    'error' => 'No hay suficiente stock',
                ]);
            }
        }

        // Si todas las verificaciones de stock son exitosas, procede a realizar el pedido

        $pedido = new Pedidos();
        $pedido->setPrecio($total);
        $pedido->setEnviado(0);
        $pedido->setRestaurante($this->getUser());
        $pedido->setFecha(new \DateTime());

        $entityManager->persist($pedido);
        $entityManager->flush();

        $idPedido = $pedido->getId();

        // Redirige a la acción para crear las líneas de pedido
        return $this->redirectToRoute('crear_lineas_pedido', ['id_pedido' => $idPedido , 'precio' => $total]);
    }

    #[Route('/enviar-pedido/{id}', name: 'app_pedidos_enviar_pedido')]
    public function enviarPedido($id,EntityManagerInterface $entityManager): Response
    {
        $pedido = $entityManager->getRepository(Pedidos::class)->find($id);
        $pedido->setEnviado(1);
        $entityManager->flush();


        return $this->redirectToRoute('app_pedidos_index');
    }

    #[Route('/mostrarMisPedidos/{user_id}', name: 'app_misPedidos', methods: ['GET'])]
    public function misPedidos($user_id, EntityManagerInterface $entityManager): Response
    {
        $pedidos = $entityManager->getRepository(Pedidos::class)->findBy(['restaurante' => $user_id]);

        return $this->render('pedidos/misPedidos.html.twig', [
            'pedidos' => $pedidos,
        ]);
    }

}
