<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Form\User_2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/account/password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $oldPassword = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $oldPassword)){
                $new_password = $form->get('new_password')->getData();
                $pwd = $encoder->encodePassword($user, $new_password);
                $user->setPassword($pwd);
            $manager->persist($user);
            $manager->flush();
                $this->addFlash("success", "Votre mot de passe a bien éte Modifié ! Connectez-vous maintenant");
            }else{
                $this->addFlash('danger', "Les informations saisies ne sont pas correctes !");
                return $this->redirectToRoute('account_password');
            }

        return $this->redirectToRoute('account_index');
        }

        return $this->render('account/password.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
