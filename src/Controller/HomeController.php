<?php

namespace App\Controller;


use App\Entity\Header;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $manager;

    /**
     * HomeController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home_index")
     */
    public function index(): Response
    {
        $bestP = $this->manager->getRepository(Products::class)->findByIsBest(1);
        $headers = $this->manager->getRepository(Header::class)->findAll();

        return $this->render('home/index.html.twig', [
            'best' => $bestP,
            'headers' => $headers,
        ]);
    }


}
