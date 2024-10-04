<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\RequestConstraintException;
use App\Exception\RequestJsonNotValidException;
use App\Exception\RequestWrongTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestBodyResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        try {
            $model = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                JsonEncoder::FORMAT
            );
        } catch (NotEncodableValueException) {
            throw new RequestJsonNotValidException();
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
