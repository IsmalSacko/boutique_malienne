<?php

namespace App\Controller\SecondSite;

use App\Entity\User2;
use App\Form\User2Type;
use App\Repository\User2Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agence-users")
 */
class User2Controller extends AbstractController
{
    /**
     * @Route("/user", name="agence_user_index", methods={"GET"})
     */
    public function index(User2Repository $user2Repository): Response
    {
        return $this->render('secondsite/user2/index.html.twig', [
            'user2s' => $user2Repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="agence_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user2 = new User2();
        $form = $this->createForm(User2Type::class, $user2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user2);
            $entityManager->flush();

            return $this->redirectToRoute('agence_user_index');
        }


        return $this->render('secondsite/user2/new.html.twig', [
            'user2' => $user2,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user2_show", methods={"GET"})
     */
    public function show(User2 $user2): Response
    {
        return $this->render('secondsite/user2/show.html.twig', [
            'user2' => $user2,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user2_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User2 $user2): Response
    {
        $form = $this->createForm(User2Type::class, $user2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agence_user_index');
        }

        return $this->render('secondsite/user2/edit.html.twig', [
            'user2' => $user2,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user2_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User2 $user2): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user2->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agence_user_index');
    }



}
