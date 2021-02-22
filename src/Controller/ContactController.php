<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $notify = null;
        $mail = new Mailjet();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $name = $form->get('nom')->getData().' '.$form->get('prenom')->getData();
                $message= $form->get('message')->getData();
            $adress_email = $form->get('email')->getData();
            $content = "Bonjour Ismael SACKO, <br/> Vous avez un message de la part de ";
            $content .= $name. "Contenu du message : $message. <hr> Pour répondre le client, il faut envoyer un email à ";
            $content .="<strong>$adress_email</strong>";
           $mail->send('ismalsacko@yahoo.fr','Ismaeldev','Contact_client', $content);

            $notify = "Votre message nous est parvenu et nous vou répondrons dans le plus bef délai";
        }
        return $this->render('contact/index.html.twig', [
            'form'=> $form->createView(),
            'notify' => $notify,
        ]);
    }
}
