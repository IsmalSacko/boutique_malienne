<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Products;
use App\Form\SearchType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $manager;

    /**
     * ProductController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/nos_produits", name="produit_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {        
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $products = $this->manager->getRepository(Products::class)->findWithSearch($search);
        }else{$products = $this->manager->getRepository(Products::class)->findAll();
        }
        return $this->render('product/index.html.twig',
            [
            'products' =>$products,
             'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/produit/{slug}", name="produit_sow_index")
     * @param Products $products
     * @return Response
     */
    public function showProduct (Products $products ): Response
    {
        $best = $this->manager->getRepository(Products::class)->findByIsBest(1);
        if (!$products){
            $this->redirectToRoute('produit_index');
        }
        return $this->render('product/show.html.twig',
            [
            'product' =>$products,
             'best'   =>$best,
        ]);

    }

}
