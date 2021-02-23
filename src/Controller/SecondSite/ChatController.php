<?php

namespace App\Controller\SecondSite;

use App\Entity\Form;
use App\Form\FormType;
use App\Repository\FormRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(Request $request, FormRepository $formRepository ) : Response{
         $contact = new Form();
         $form= $this->createForm(FormType::class, $contact);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()){
             $dataManager = $this->getDoctrine()->getManager();
              $dataManager->persist($contact);
              $dataManager->flush();    
            //   return $this->redirectToRoute('chat');          
            }
            
        return $this->render('secondsite/chat/index.html.twig', [
            'contact' => $formRepository->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
        ]);
    }
}
