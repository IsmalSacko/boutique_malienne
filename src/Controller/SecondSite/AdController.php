<?php

namespace App\Controller\SecondSite;

use App\Entity\Aad;
use App\Form\AdType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ad")
 */
class AdController extends AbstractController
{
    /**
     * @Route("/", name="ad_index", methods={"GET"})
     */
    public function index(): Response
    {
        $ad = $this->getDoctrine()
            ->getRepository(Aad::class)
            ->findAll();
         $tmpfile = "/css_2/images/ad/";

        return $this->render('secondsite/ad/index.html.twig', [
            'ad' => $ad,
             'tmp' => $tmpfile
        ]);
    }


    /**
     * @Route("/new", name="ad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ad = new Aad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($ad);
            $entityManager->flush();
            $this->addFlash("success", "Votre annonce <strong>{$ad->getTitle()}</strong> à bien été enregistrée");
            return $this->redirectToRoute('ad_index');
        }

        return $this->render('secondsite/ad/new.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ad_show", methods={"GET"})
     */
    public function show(Aad $ad): Response
    {
        return $this->render('secondsite/ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Aad $ad): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ad_index');
        }

        return $this->render('secondsite/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Aad $ad): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ad_index');
    }
}
