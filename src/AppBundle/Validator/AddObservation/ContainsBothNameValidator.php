<?php

namespace AppBundle\Validator\AddObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsBothNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $referenceName = $this->context->getRoot()->getData()['species'];

        // Vérification si le champs de fin de période est vide
        if ($value !== null && !empty($referenceName)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}