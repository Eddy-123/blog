<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniquePseudo extends Constraint
{
    public $message = "This pseudo is not available for use";
}
