<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande-create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $manager,Cart $cart, $reference): Response
    {
        $product_for_srtipe = [];
        $YOUR_DOMAIN = 'https://ismaeldev.fr';
        $order = $manager->getRepository(Order::class)->findOneByReference($reference);
        if (!$order){
           new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {

            $product_object = $manager->getRepository(Products::class)->findOneByName($product->getProduct());
            $product_for_srtipe [] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
        //les données pour le transporteur à filler à stripe
        $product_for_srtipe [] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarierPrice(),
                'product_data' => [
                    'name' => $order->getCarierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        //Initiaisation de stripe
        Stripe::setApiKey('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');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_srtipe,
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/error/{CHECKOUT_SESSION_ID}',
        ]);
        $order->setStripeSessionId($checkout_session->id);
        $manager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
