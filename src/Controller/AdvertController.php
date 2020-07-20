<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
/* Permet de créer un service que nous avons nous même créé, 
pour l'instancier il suffit de : 
$service = new ServiceName(); */
use App\Services\AntiSpam;

/**
 * @Route("/advert")
 */

class AdvertController extends AbstractController
{

  public function lastAdverts($limit)
  {
    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur symfony'),
      array('id' => 3, 'title' => 'Mission de webmaster'),
      array('id' => 4, 'title' => 'Offre de stage de webdesigner')
    );

    return $this->render('Advert/_lastAdverts.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }

  // requirements permet de typé les params grâce à des regex, defaults= permet de passer une valeur par défaut au param
  /**
   * @Route("/{page}", name="advert_index", requirements={"page"="\d+"},defaults={"page" = 1}, methods={"GET"})
   */
  public function index(int $page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException('Page "' . $page . '" inexistante.');
    }
 $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );

    // Et modifiez le 2nd argument pour injecter notre liste
    return $this->render('Advert/index.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }

  /**
   * @Route("/view/{id}", name="advert_view", requirements={"id"="\d+"}, methods={"GET"})
   */
  public function view(int $id, Request $request)
  {

    $advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('Advert/view.html.twig', array(
      'advert' => $advert
    ));
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
   * @Route("/edit/{id}", name="advert_edit", requirements={"id"="\d+"}, methods={"PUT","GET"})
   */
  public function edit(int $id, Request $request)
  {
    if ($request->isMethod("POST")) {
      $this->addFlash('notice', 'Annonce bien modifiée');
      return $this->redirectToRoute('advert_view', ["id" => 5]);
    }
    
    $advert = array(
      'title'   => 'Recherche développpeur Symfony',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('Advert/edit.html.twig', array(
      'advert' => $advert
    ));

  }

  /**
   * @Route("/delete/{id}", name="advert_delete", requirements={"id"="\d+"}, methods={"DELETE"})
   */
  public function delete(int $id)
  {

    return $this->render('Advert/delete.html.twig');
  }
}
