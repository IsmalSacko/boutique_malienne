<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    private $manager;

    /**
     * AccountOrderController constructor.
     * @param $mapper
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/account/mes-commandes", name="mes_commandes")
     */
    public function index(): Response
    {
        $orders = $this->manager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        return $this->render('account/mes_commandes.html.twig', [
            'orders' => $orders,
        ]);
    }
    /**
     * @Route("/account/mes-commandes/{reference}", name="mes_commandes_sow")
     */
    public function show($reference): Response
    {
        $order = $this->manager->getRepository(Order::class)->findOneByReference($reference);
        if (!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('mes_commandes');
        }
        return $this->render('account/mes_commandes_show.html.twig', [
            'order' => $order,
        ]);
    }
}
