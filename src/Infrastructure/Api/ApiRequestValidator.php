<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRequestValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(Request $request, object $dto): ?JsonResponse
    {
        $violations = $this->validator->validate($dto);
        
        if (count($violations) === 0) {
            return null;
        }
        
        $errors = [];
        
        foreach ($violations as $violation) {
            $propertyPath = $violation->getPropertyPath();
            $errors[$propertyPath] = $violation->getMessage();
        }
        
        return new JsonResponse([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => 'Validation failed',
            'errors' => $errors,
        ], Response::HTTP_BAD_REQUEST);
    }
}
