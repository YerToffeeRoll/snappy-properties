<?php

namespace App\Validation;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\Validation as SymfonyValidation;

class Validation
{
    protected static array $errors = [];

    /**
     * Validates an entity.
     *
     * @param EntityInterface $entity
     * @return bool
     */
    public static function Validate(EntityInterface $entity): bool
    {
        $validator = SymfonyValidation::createValidatorBuilder()
            ->addYamlMapping('../config/validation/validation.yaml')
            ->getValidator();

        if (0 !== \count($errors = $validator->validate($entity))) {
            foreach ($errors as $error) {
                self::$errors[$error->getPropertyPath()] = $error->getMessage();
            }

            return false;
        }

        return true;
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }
}
