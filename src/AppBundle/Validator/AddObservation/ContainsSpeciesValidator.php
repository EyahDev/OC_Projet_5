<?php

namespace AppBundle\Validator\AddObservation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsSpeciesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $photos = $this->context->getRoot()->getData()['photoPath'];
        $vernacular = $this->context->getRoot()->getData()['vernacularName'];

        // Vérification si le champs de fin de période est vide
        if ($photos === null) {
            if ($value === null && $vernacular === null) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
