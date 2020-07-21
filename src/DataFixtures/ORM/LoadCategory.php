<?php 

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class LoadCategory extends Fixture 
{

    public function load(ObjectManager $manager)
    {
         // Liste des noms de catégorie à ajouter
    $names = array(
        'Développement web',
        'Développement mobile',
        'Graphisme',
        'Intégration',
        'Réseau'
      );
  
      foreach ($names as $name) {
        // On crée la catégorie
        $category = new Category();
        $category->setName($name);
  
        // On la persiste
        $manager->persist($category);
      }
  
      // On déclenche l'enregistrement de toutes les catégories
      $manager->flush();
    }



}