<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    private $manager;

    /**
     * AccountAdressController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/account/adress", name="account_adress")
     */
    public function index(): Response
    {

        return $this->render('account/adress.html.twig', [

        ]);
    }

    /**
     * @Route("/account/ajouter-une-adresse", name="add_account_adress")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, Cart $cart): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $adress->setUser($this->getUser());
            $this->manager->persist($adress);
            $this->manager->flush();
            if ($cart->get()){
                return $this->redirectToRoute('order_index');
            }else{
            return $this->redirectToRoute('account_adress');
          }
        }
        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/account/modifier-une-adresse/{id}", name="edit_account_adress")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, $id): Response
    {
        $adress = $this->manager->getRepository(Adress::class)->findOneById($id);
        if(!$adress || $adress->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_adress');
        }
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->manager->flush();
            return $this->redirectToRoute('account_adress');
        }
        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/supprimer-une-adresse/{id}", name="delete_account_adress")
     * @param $id
     * @return Response
     */
    public function delete($id): Response
    {
        $adress = $this->manager->getRepository(Adress::class)->findOneById($id);
        if($adress && $adress->getUser() == $this->getUser()){
            $this->manager->remove($adress);
            $this->manager->flush();
        }
        return $this->redirectToRoute('account_adress');

    }
}
