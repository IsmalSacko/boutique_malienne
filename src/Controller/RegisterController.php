<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Entity\User;
use App\Form\User_2Type;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $encoder;
    private $manager;

    /**
     * RegisterController constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $notification = null;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $uniqemail = $this->manager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$uniqemail){
           $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
           $this->manager->persist($user);
           $this->manager->flush();
           //return$this->redirectToRoute('login');
             $mail = new Mailjet();
             $content_email = "Bonjour ".$user->getFirstname().","."<br/>Bienvenue sur notre boutique en ligne";

             $mail->send($user->getEmail(), $user->getFirstname(),"Confirmation d'inscription sur la boutique Malienne",
                 $content_email);
                $notification = "Votre inscription s'est bien passée, vous pouvez vous connecter dès à présent avec votre compte !";

            }else{
                $notification ="L'email que vous avez renseigné existe déjà !";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'notify' => $notification,
        ]);
    }
}
