<?php

namespace AppBundle\Validator\SearchObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsPeriodBeginValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $periodEnd = $this->context->getRoot()->getData()['begin'];

        // Vérification si le champs de fin de période est vide
        if ($value === null && is_a($periodEnd,'DateTime')) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
