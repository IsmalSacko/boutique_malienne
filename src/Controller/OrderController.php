<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $manager;

    /**
     * OrderController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/", name="order_index")
     * @param Cart $cart
     * @return Response
     */
    public function index(Cart $cart): Response
    {
        if(!$this->getUser()->getAdresses()->getValues()){
        return $this->redirectToRoute('add_account_adress');
    }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            'cart'=>$cart->getFull(),
        ]);
    }
    /**
     * @Route("/commande/details", name="order_details_index", methods={"POST"})
     * @param Cart $cart
     * @param Request $request
     * @return Response
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Initialisations de nos variables
            $date = new \DateTime();
            $cariers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'. $delivery->getPhone();

            if($delivery->getCompany()){
               $delivery_content .= '<br/>'.$delivery->getCompany();
           }

           $delivery_content .= '<br/>'. $delivery->getAdress();
           $delivery_content .= '<br/>'. $delivery->getPostal().' '.$delivery->getCity();
           $delivery_content .= '<br/>'. $delivery->getCountry();

           //Enregistrement de la commande
            $order = new Order();
            $reference = $date->format('dmy').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarierName($cariers->getName());
            $order->setCarierPrice($cariers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);
            //Persistence
            $this->manager->persist($order);

            //Détailes de la commande et initialisation/enegistrement des produits en bdd
                        foreach($cart->getFull() as $product){
            $orderDetails = new  OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            //Pesristence en bdd
            $this->manager->persist($orderDetails);
            }
           $this->manager->flush();

           return $this->render('order/add.html.twig', [
            'cart'=>$cart->getFull(),
            'carrier'=>$cariers,
            'delivery' => $delivery_content,
            'reference' => $order->getReference(),


        ]);
        }
        $this->redirectToRoute('panier_index');
    }
}
