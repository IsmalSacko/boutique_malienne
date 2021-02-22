<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $manager;

    /**
     * ResetPasswordController constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('home_index');
        }
        if ($request->get('email')){
            $user = $this->manager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user){
                //enregistrer le mot de passe de l'utilisateur en base dd si le mail qu'il a renseigné existe bien
                // dans la base des données
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTime());
                $this->manager->persist($resetPassword);
                $this->manager->flush();
                // envoyer un email à l'utilisateur pour mettre à jour son de passe oublié
                $mail = new Mailjet();
                $url = $this->generateUrl('update_password', [
                    'token' =>$resetPassword->getToken()
                ]);

                $content = "Bonjour ". $user->getFirstname().",<br/> Vous avez demandé à réinitialiser votre mot de passe
                sur le site de la boutique malienne. <br/> <br/>";
                $content .="Merci de cliqur sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de 
                passe</a> ";
                $mail->send($user->getEmail(),$user->getFirstname(),'Réinitialiser votre mot de passe sur la 
                boutique malienne',$content);
                $this->addFlash('notice', 'Vous allez recevoir dans un instant un mail contenant un lien vous permettant de réinitialiser votre mot de passe');
                //dd($url);
            }else{
                $this->addFlash('notice', 'Cette adresse email est inconnue !');
            }
        }
        return $this->render('reset_password/index.html.twig', [

        ]);
    }

    /**
     * @Route("/modifier-mot-de-passe/{token}", name="update_password")
     */
    public function update(Request $request,$token, UserPasswordEncoderInterface $encoder){
        $reset_password = $this->manager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$reset_password){
            return $this->redirectToRoute('reset_password');
        }
        //on vérifie si la date de maintnant est -3 hueres ou ....
        $now_date = new \DateTime();
        if ($now_date > $reset_password->getCreatedAt()->modify('+3 hours')){
            $this->addFlash('notice', 'Votre de demande de mot passe à expiré. Merci de la renouvler !');
            return $this->redirectToRoute('reset_password');
        }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $new_pass = $form->get('new_password')->getData();
            $password = $encoder->encodePassword($reset_password->getUser(),$new_pass);
            $reset_password->getUser()->setPassword($password);
            $this->manager->flush();
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jours.');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('reset_password/update.html.twig', [
                'form' => $form->createView(),
        ]);

    }
}
