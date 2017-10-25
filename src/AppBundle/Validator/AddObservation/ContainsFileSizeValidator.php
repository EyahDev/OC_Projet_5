<?php

namespace AppBundle\Validator\AddObservation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsFileSizeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Défintion d'un compteur vide
        $count = 0;

        if (!empty($value)) {
            // Parcours des fichiers à vérifier
            foreach ($value as $file) {
                // Vérification si la taille est supérieur ou égale à 4Mo
                if ($file->getSize() >= 4194304) {
                    // Incrementation du compteur
                    $count++;
                }
            }

            // Vérification si au moins 1 fichier est en défaut
            if ($count > 0) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}