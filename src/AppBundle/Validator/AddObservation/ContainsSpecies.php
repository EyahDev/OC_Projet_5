<?php

namespace AppBundle\Validator\AddObservation;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainsSpecies extends Constraint
{
    public $message = "Veuillez sélectionner une espèce si vous n'avez pas de photo";

    public function validatedBy()
    {
        return ContainsSpeciesValidator::class;
    }
}