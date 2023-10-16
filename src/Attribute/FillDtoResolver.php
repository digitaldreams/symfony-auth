<?php

namespace App\Attribute;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

use function Symfony\Component\String\u;

class FillDtoResolver implements ValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(
            FillDto::class,
            ArgumentMetadata::IS_INSTANCEOF
        )[0] ?? null;

        if (!$attribute) {
            return [];
        }

        if ($argument->isVariadic()) {
            throw new \LogicException(
                sprintf('Mapping variadic argument "$%s" is not supported.', $argument->getName())
            );
        }
        $reflectionClass = new \ReflectionClass($argument->getType());
        $dtoClass = $reflectionClass->newInstanceWithoutConstructor();
        $paramBag = $request->getMethod() === 'POST' ? $request->request : $request->query;
        foreach ($paramBag->all() as $property => $value) {
            $attribute = u($property)->camel();
            if (property_exists($dtoClass, $attribute)) {
                $reflectionProperty = $reflectionClass->getProperty($attribute);
                $reflectionProperty->setValue($dtoClass, $value);
            }
        }

        return [$dtoClass];
    }
}