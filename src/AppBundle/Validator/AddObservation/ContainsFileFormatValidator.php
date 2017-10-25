<?php

namespace AppBundle\Validator\AddObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsFileFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Défintion d'un compteur vide
        $count = 0;

        if (!empty($value)) {
            // Parcours des fichiers à vérifier
            foreach ($value as $file) {
                // Vérification si le format du fichier et un jpeg ou png
                if ($file->guessExtension() != 'jpeg' && $file->guessExtension() != 'png') {
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