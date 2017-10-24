<?php

namespace AppBundle\Validator\AddObservation;

use Symfony\Component\Validator\Constraint;

class ContainsFileFormat extends Constraint
{
    public $message = "Le format de l'image doit être jpg ou png, veuillez vérifier";

    public function validatedBy()
    {
        return ContainsFileFormatValidator::class;
    }
}