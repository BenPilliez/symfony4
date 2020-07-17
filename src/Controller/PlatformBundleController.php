<?php

namespace App\Controller;

use App\Entity\PlatformBundle;
use App\Form\PlatformBundleType;
use App\Repository\PlatformBundleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/platform/bundle")
 */
class PlatformBundleController extends AbstractController
{
    /**
     * @Route("/", name="platform_bundle_index", methods={"GET"})
     */
    public function index(PlatformBundleRepository $platformBundleRepository): Response
    {
        return $this->render('platform_bundle/index.html.twig', [
            'platform_bundles' => $platformBundleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="platform_bundle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $platformBundle = new PlatformBundle();
        $form = $this->createForm(PlatformBundleType::class, $platformBundle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($platformBundle);
            $entityManager->flush();

            return $this->redirectToRoute('platform_bundle_index');
        }

        return $this->render('platform_bundle/new.html.twig', [
            'platform_bundle' => $platformBundle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="platform_bundle_show", methods={"GET"})
     */
    public function show(PlatformBundle $platformBundle): Response
    {
        return $this->render('platform_bundle/show.html.twig', [
            'platform_bundle' => $platformBundle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="platform_bundle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PlatformBundle $platformBundle): Response
    {
        $form = $this->createForm(PlatformBundleType::class, $platformBundle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('platform_bundle_index');
        }

        return $this->render('platform_bundle/edit.html.twig', [
            'platform_bundle' => $platformBundle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="platform_bundle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PlatformBundle $platformBundle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$platformBundle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($platformBundle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('platform_bundle_index');
    }
}
