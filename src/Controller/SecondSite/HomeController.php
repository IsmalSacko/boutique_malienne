<?php

namespace App\Controller\SecondSite;

use App\Entity\Aad;
use App\Repository\AadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/agence", name="agence")
     */
    public function index():Response    {
        return $this->render('secondsite/home/index.html.twig', [
            'titre' => 'Bienvenue | Agence',
        ]);
    }

    /**
     * @Route("/home", name="home")
     * @param AadRepository $repository
     * @return Response
     */
    public function accueil(AadRepository $repository){
        $titre ="Bienvenue sur la page de mon Agence de location";

        return $this->render('secondsite/home/index.html.twig', [
          'titre'  => $titre,
            'ads' => $repository->findAll()
        ]);
    }
    /**
     * @Route ("/projets_realises", name="projets_realises-index")
     */
    public function projet() : Response{
        return $this->render('secondsite/home/projet.html.twig');
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function image(){
        return $this->render('secondsite/upload.html.twig');
    }
    /**
     * @Route ("/dev-web", name="dev-wev_index")
     */
        public function dev_web(){
        return $this->render('secondsite/home/dev_web.html.twig');
    }/**
     * @Route ("/demo", name="demo_index")
     */
        public function demo(){
        return $this->render('secondsite/home/demo.html.twig');
    }
}
