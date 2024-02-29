<?php
namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class MyCountryConstraint extends Constraint
{
    public $message = 'This string should represent either an existing country or state. Cities are not accepted';

    /**
     * @return
     * */
    public function validatedBy()
    {
        return MyCountryConstraintValidator::class;
    }
}


