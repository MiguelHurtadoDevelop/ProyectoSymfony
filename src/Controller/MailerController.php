<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer, $email, $carrito, $pedido_id): Response
    {
        

        $total = 0;
        $email = (new TemplatedEmail())
        ->from(new Address('miguelhurtado.developer@gmail.com', 'Il Ristorante'))
        ->to($email)
        ->subject('Confirmación de Pedido nº'.$pedido_id.'');
        
        $htmlContent =
         '
            <h1>Gracias por realizar su pedido</h1>
            
                            <table border="1">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total por Producto</th>
                                
                            </tr>';
    
        for ($i = 0; $i < count($carrito); $i++) {
            $htmlContent .= '<tr>
                                <td>' . $carrito[$i]['nombre'] . '</td>
                                <td>' . $carrito[$i]['cantidad'] . '</td>
                                <td>' . $carrito[$i]['precio'] . '</td>
                                <td>' . $carrito[$i]['totalPorProducto'] . '</td>
                            </tr>';
                            $total += $carrito[$i]['totalPorProducto'];
                            
        }

        $htmlContent .= '<tr>
                            <td colspan="3">Total</td>
                            <td>'.$total.'€</td>
                        </tr>';
        $htmlContent .= '</table>';
    
        $email->html($htmlContent);
        $mailer->send($email);
    
        return new Response('Email sent!');
    }

    #[Route('/email/enviado')]
    public function sendEmailEnviado(MailerInterface $mailer, $email, $pedido_id): Response
    {
        $email = $email = (new TemplatedEmail())
        ->from(new Address('miguelhurtado.developer@gmail.com', 'Il Ristorante'))
        ->to($email)
        ->subject('¡Su Pedido nº '.$pedido_id.' ha sido enviado!');            

            $htmlContent = '<h1>¡Su Pedido ha sido enviado!</h1>';
            
            $htmlContent .= 'Pulse <a href="http://127.0.0.1:8000/pedidos/'.$pedido_id.'">aqui</a> para ver los detalles de su pedido.';
            $email->html($htmlContent);

            $mailer->send($email);
    
        return new Response('Email sent!');
    }
}
