<?php

namespace AppBundle\Validator\AddObservation;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainsBothName extends Constraint
{
    public $message = "Vous devez sélectionner une espèce par son nom scientifique ou son nom commun, mais pas les deux.";

    public function validatedBy()
    {
        return ContainsBothNameValidator::class;
    }
}
