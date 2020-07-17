<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/advert")
 */

class AdvertController extends AbstractController
{
  // requirements permet de typé les params grâce à des regex, defaults= permet de passer une valeur par défaut au param
  /**
   * @Route("/{page}", name="advert_index", requirements={"page"="\d+"},defaults={"page" = 1}, methods={"GET"})
   */
  public function index(int $page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException('Page "' . $page . '" inexistante.');
    }

    return $this->render('Advert/index.html.twig');
  }

  /**
   * @Route("/view/{id}", name="advert_view", requirements={"id"="\d+"}, methods={"GET"})
   */
  public function view(int $id, Request $request)
  {

    return $this->render(
      'Advert/view.html.twig',
      ['id' => $id]
    );
  }

  /**
   * @Route("/add", name="advert_add", methods={"POST","GET"})
   */
  public function add(Request $request)
  {

    if ($request->isMethod("POST")) {
      $this->addFlash('info', 'Annonce bien enregistrée');

      // Le « flashBag » est ce qui contient les messages flash dans la session
      // Il peut bien sûr contenir plusieurs messages :
      $this->addFlash('info', 'Oui oui, elle est bien enregistrée !');

      // Puis on redirige vers la page de visualisation de cette annonce
      return $this->redirectToRoute('advert_view', ['id' => 5]);
    }

    return $this->render('Advert/add.html.twig');
  }

  /**
   * @Route("/edit/{id}", name="advert_edit", requirements={"id"="\d+"}, methods={"PUT"})
   */
  public function edit(int $id, Request $request)
  {
    if ($request->isMethod("POST")) {
      $this->addFlash('notice', 'Annonce bien modifiée');
      return $this->redirectToRoute('advert_view', ["id" => 5]);
    }

    return $this->render('Advert/edit.html.twig');
  }

  /**
   * @Route("/delete/{id}", name="advert_delete", requirements={"id"="\d+"}, methods={"DELETE"})
   */
  public function delete(int $id)
  {

    return $this->render('Advert/delete.html.twig');
  }
}
