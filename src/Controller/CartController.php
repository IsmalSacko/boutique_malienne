<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $manager;

    /**
     * CartController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/mon-panier", name="panier_index")
     * @param Cart $cart
     * @return Response
     */
    public function index(Cart $cart): Response
    {


        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/cart/add/{id}", name="add_cart")
     * @param Cart $cart
     * @param $id
     * @return Response
     */
    public function add(Cart $cart,$id): Response
    {
            $cart->add($id);
            return $this->redirectToRoute('panier_index', [
                'cart' => $cart,
            ]);

    }

    /**
     * @Route("/commande/cart/remove", name="remove_cart")
     * @param Cart $cart
     * @return Response
     */
    public function remove(Cart $cart): Response
    {
            $cart->remove();
            return $this->redirectToRoute('produit_index');

    }
    /**
     * @Route("/commande/cart/remove/{id}", name="remove_onecart")
     * @param Cart $cart
     * @return Response
     */
    public function delete(Cart $cart, $id): Response
    {
            $cart->delete($id);
            return $this->redirectToRoute('panier_index');

    }
    /**
     * @Route("/commande/cart/decrease/{id}", name="decrease_cart")
     * @param Cart $cart
     * @return Response
     */
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('panier_index');

    }
}
