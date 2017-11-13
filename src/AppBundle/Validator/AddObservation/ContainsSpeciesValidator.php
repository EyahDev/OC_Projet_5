<?php

namespace AppBundle\Validator\AddObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsSpeciesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $photos = $this->context->getRoot()->getData()['photoPath'];

        // Vérification si le champs de fin de période est vide
        if ($value === null && $photos === null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
