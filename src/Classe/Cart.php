<?php


namespace App\Classe;


use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $manager;

    /**
     * Cart constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->manager = $manager;
    }

    public function add($id){
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
    $this->session->set('cart', $cart);
    }
    public function get(){
        return $this->session->get('cart');
    }
    public function remove(){
        return $this->session->remove('cart');
    }
    public function delete($id){
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    public function decrease($id){
        $cart = $this->session->get('cart', []);
        if($cart[$id]> 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);
    }

    public function getFull(){
        $cartentier = [];
        if ($this->get()){
            foreach ($this->get() as $id => $quantity ){
             $product_object = $this->manager->getRepository(Products::class)->findOneById($id);
             if (!$product_object){
                 $this->decrease($id);
                 continue;
             }
             $cartentier[] = [
                    'product'=> $product_object,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartentier;
    }
}