<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mailjet;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $manager;

    /**
     * OrderSuccessController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->manager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home_index');
        }
        if ($order->getState() == 0){
            //on vide le panier
            $cart->remove();
            // On modifie le statut de la commande en (payée)
           $order->setState(1);
            $this->manager->flush();
            // On envoie un mail de confirmation à l'utilisater
            $mail = new Mailjet();
            $content_email = "Bonjour ".$order->getUser()->getFirstname().","."<br/>Merci pour commande sur notre boutique en ligne !
            <br/>Pour suivre votre commantde, rendez-vous <a class='text-dark' href='https://ismaeldev.fr/'>ismaeldev</a>";

            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(),"Confirmation de votre commande sur la boutique Malienne",
                $content_email);
        }
        // On affiche quelques informations de sa commande
        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
