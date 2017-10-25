<?php

namespace AppBundle\Validator\SearchObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsPeriodEndValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $periodBegin = $this->context->getRoot()->getData()['end'];

        // Vérification si le champs de fin de période est vide
        if ($value == null && is_a($periodBegin,'DateTime')) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}