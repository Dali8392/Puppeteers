<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MyCountryConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        
        // Get the path to your JSON file
$jsonFilePath = __DIR__ . '/../../data/countries+states.json';

// Read the JSON file
$jsonData = file_get_contents($jsonFilePath);

// Decode the JSON data into an array
        $countries = json_decode($jsonData, true);
        foreach ($countries as $country) {
             $validNames = [];
             $validNames[] = strtolower($country['name']);
            foreach (array_values($country['translations']) as $translation) {
                $validNames[] = strtolower($translation);
            }
        
            // Add the states names to the list
            foreach ($country['states'] as $state) {
                $validNames[] = strtolower($state['name']);
            }


        $normalizedValue = strtolower($value);
        if (in_array($normalizedValue, $validNames)) {
            
            return;
        }
    }
        
            if ($this->context->getPropertyName() == 'depart') {
                $this->context->buildViolation($constraint->message)
                    ->atPath('depart')
                    ->addViolation();
            }
    
            
            if ($this->context->getPropertyName() == 'destination') {
                $this->context->buildViolation($constraint->message)
                    ->atPath('destination')
                    ->addViolation();
            }
        }
    }
