<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adverts;
use App\Entity\Image;
use App\Entity\Application;
use App\Entity\Category;
use App\Entity\AdvertSkill;
use App\Entity\Skill;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Form\AdvertType;
use App\Form\AdvertEditType;

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
   * @Route("/{page}", name="advert_index", requirements={"page"="\d+", "nbPerPage"= "\d+"},defaults={"page" = 1, "nbPerPage" = 4 }, methods={"GET"})
   */
  public function index(int $page, int $nbPerPage)
  {
    if ($page < 1) {
      throw $this->createNotFoundException('Page "' . $page . '" inexistante.');
    }

    $em = $this->getDoctrine()->getManager();

    $listAdverts = $em->getRepository(Adverts::class)->getAdverts($page, $nbPerPage);

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages && $page !== 1) {
      throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
    }


    // Et modifiez le 2nd argument pour injecter notre liste
    return $this->render('Advert/index.html.twig', array(
      'listAdverts' => $listAdverts,
      'page' => $page,
      'nbPages' => $nbPages
    ));
  }

  /**
   * @Route("/view/{id}", name="advert_view", requirements={"id"="\d+"}, methods={"GET"})
   */
  public function view(Adverts $advert, int $id, Request $request)
  {
     $em = $this->getDoctrine()->getManager();

     /*
    $reposository = $em->getRepository(Adverts::class);

    $advert = $reposository->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException('Aucune annonce avec cette identifiant');
    } */

    $listApplications = $em->getRepository(Application::class)
      ->findBy(array('advert' => $advert));

    $listAdvertsSkills = $em->getRepository(AdvertSkill::class)
      ->findBy(array('advert' => $advert));

    return $this->render('Advert/view.html.twig', array(
      'advert' => $advert,
      'listApplications' => $listApplications,
      'listSkills' => $listAdvertsSkills
    ));
  }

  /**
   * @Route("/add", name="advert_add", methods={"POST","GET"})
   */
  public function add(Request $request)
  {

    $advert = new Adverts();

    $form = $this->get('form.factory')->create(AdvertType::class, $advert);

    /* $image = new Image();
    $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image->setAlt('Job de rêve');

    $application1 = new Application();
    $application1->setAuthor('Clément');
    $application1->setContent('Bonjour, je suis très interessé par cette offre, prenez moi !');

    $application2 = new Application();
    $application2->setAuthor('Madeleine');
    $application2->setContent("J'ai besoin de taff prenez moi !");

    $advert->setImage($image);

    $advert->addApplication($application1);
    $advert->addApplication($application2);

    $em = $this->getDoctrine()->getManager();

    $listSkills = $em->getRepository(Skill::class)->findAll();

    foreach ($listSkills as $skill) {
      //On instancie l'objet
      $advertSkill = new AdvertSkill();
      // On lui ajoute notre annonce
      $advertSkill->setAdvert($advert);
      // On oublie pas les différents skill
      $advertSkill->setSkill($skill);
      // Et on lui ajoute un level
      $advertSkill->setLevel('Débutant');

      $em->persist($advertSkill);
    }

    $em->persist($advert);
    $em->flush(); */


    if ($request->isMethod("POST")) {

      $form->handleRequest($request);

      if ($form->isValid()) {
                
        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $this->addFlash('success', 'Annonce créée!');

        // Puis on redirige vers la page de visualisation de cette annonce
        return $this->redirectToRoute('advert_view', ['id' => $advert->getId()]);
      }
    }

    return $this->render('Advert/add.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
   * @Route("/edit/{id}", name="advert_edit", requirements={"id"="\d+"}, methods={"POST","GET"})
   */
  public function edit(int $id, Request $request)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository(Adverts::class)->find($id);

    if (null === $id) {
      throw new NotFoundHttpException('Aucune annonce avec cet indentifiant');
    }

    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

    /* $listCategories = $em->getRepository(Category::class)->findAll();

    foreach ($listCategories as $category) {
      $advert->addCategory($category);
    }

    $em->flush();
    */

    if ($request->isMethod("POST")) {

      $form->handleRequest($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('notice', 'Annonce bien modifiée');
        return $this->redirectToRoute('advert_view', ["id" => $advert->getId()]);
      }
    }

    return $this->render('Advert/edit.html.twig', array(
      'form' => $form->createView(),
      'advert' => $advert
    ));
  }

  /**
   * @Route("/delete/{id}", name="advert_delete", requirements={"id"="\d+"}, methods={"POST","GET"})
   */
  public function delete(Request $request, int $id)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository(Adverts::class)->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException('Aucune annonce avec cette identifiant');
    }


    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $this->addFlash('info', "L'annonce a bien été supprimée.");

      return $this->redirectToRoute('advert_index');
    }
    
    return $this->render('Advert/delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));

  }

  /**
   * @Route("/purge/{days}", name="advert_purge", methods={"GET"})
   */
  public function purge($days, Request $request)
  {

    // On récupère notre service
    $purger = $this->get('purger.advert');

    // On purge les annonces
    $purger->purge($days);

    // On ajoute un message flash arbitraire
    $request->getSession()->getFlashBag()->add('info', 'Les annonces plus vieilles que ' . $days . ' jours ont été purgées.');

    // On redirige vers la page d'accueil
    return $this->redirectToRoute('advert_index');
  }
}
