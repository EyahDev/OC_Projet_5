<?php

namespace AppBundle\Validator\SearchObservation;

use Symfony\Component\Validator\Constraint;

class ContainsPeriodEnd extends Constraint
{
    public $message = "La fin de periode n'est pas rempli";

    public function validatedBy()
    {
        return ContainsPeriodEndValidator::class;
    }
}