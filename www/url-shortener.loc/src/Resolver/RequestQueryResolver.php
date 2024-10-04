<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\RequestConstraintException;
use App\Exception\RequestWrongTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestQueryResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(MapQueryString::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        try {
            $model = $this->serializer->denormalize($request->query->all(), $argument->getType());
        } catch (NotNormalizableValueException $e) {
            throw new RequestWrongTypeException($e->getPath(), $e->getExpectedTypes()[0]);
        }

        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new RequestConstraintException($errors[0]->getMessage());
        }

        return [$model];
    }
}
