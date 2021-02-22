<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $manager;

    /**
     * OrderCancelController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/error/{stripeSessionId}", name="order_cancel")
     *
     */
    public function index($stripeSessionId): Response
    {
        $order = $this->manager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home_index');
        }
        //Envoyer un email à notre utilisateur pour lui idiquer l'échec de paiement
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}
