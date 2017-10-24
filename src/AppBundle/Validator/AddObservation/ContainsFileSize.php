<?php

namespace AppBundle\Validator\AddObservation;

use Symfony\Component\Validator\Constraint;

class ContainsFileSize extends Constraint
{
    public $message = "Votre fichier ne peux pas dépasser 4Mo, veuillez vérifier";

    public function validatedBy()
    {
        return ContainsFileSizeValidator::class;
    }
}