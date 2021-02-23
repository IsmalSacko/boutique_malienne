<?php
namespace App\Controller\SecondSite;
// use App\Entity\Contact;
// use App\Form\ContactType;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("/blog", name="homepage")
     */
    public function home(): Response
    {
        return $this->render('secondsite/blog.html.twig');
    }
    /**
     * Controlleur pour le jeu
     * 
     * @Route("/jeu",name="jeu")
     */
    public function jeu(): Response
    {

        return $this->render('secondsite/jeu.html.twig');
    }
}
