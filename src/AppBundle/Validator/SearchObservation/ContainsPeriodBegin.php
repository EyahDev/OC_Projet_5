<?php

namespace AppBundle\Validator\SearchObservation;

use Symfony\Component\Validator\Constraint;

class ContainsPeriodBegin extends Constraint
{
    public $message = "Le début de periode n'est pas rempli";

    public function validatedBy()
    {
        return ContainsPeriodBeginValidator::class;
    }
}
