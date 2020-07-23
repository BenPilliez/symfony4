<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

 class AntiFlood extends Constraint
 {
     public $message = 'Vous avez déjà posté un message il y a moins de 15 secondes, merci d\'attendre un petit peu.';

     public function validatedBy()
     {
       return 'antiflood'; // Ici, on fait appel à l'alias du service
     }
 }