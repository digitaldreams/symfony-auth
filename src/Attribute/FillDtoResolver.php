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

        $fillDto = $argument->getAttributesOfType(FillDto::class, ArgumentMetadata::IS_INSTANCEOF)[0];

        if ($argument->isVariadic()) {
            throw new \LogicException(
                sprintf('Mapping variadic argument "$%s" is not supported.', $argument->getName())
            );
        }
        $reflectionClass = new \ReflectionClass($argument->getType());
        $dtoClass = $reflectionClass->newInstanceWithoutConstructor();

        $queryParams = $request->query->all();
        $requestParams = $request->request->all();
        foreach (array_merge($queryParams, $requestParams) as $property => $value) {
            $attribute = u($property)->camel();
            if (property_exists($dtoClass, $attribute)) {
                $reflectionProperty = $reflectionClass->getProperty($attribute);
                $reflectionProperty->setValue($dtoClass, $value);
            }
        }
        $files = $request->files->all();
        foreach ($files as $fileKey => $file) {
            $attribute = u($fileKey)->camel();
            if (property_exists($dtoClass, $attribute)) {
                $reflectionProperty = $reflectionClass->getProperty($attribute);
                $reflectionProperty->setValue($dtoClass, $file);
            }
        }

        return [$dtoClass];
    }
}