<?php

namespace App\Utils\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueValueValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        private Security $security
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueValue) {
            throw new UnexpectedTypeException($constraint, UniqueValue::class);
        }
        if (null === $value || '' === $value) {
            return;
        }
        if (!is_scalar($value)) {
            throw new UnexpectedValueException($value, 'scalar');
        }
        $repository = $this->em->getRepository($constraint->entity);
        if ($method = $constraint->repositoryMethod) {
            $user = $constraint->currentUser===true ? $this->security->getUser() : null;
            $result = $repository->{$method}(
                $constraint->field,
                $value,
                $this->requestStack->getCurrentRequest(),
                $user
            );
        } else {
            $result = $repository->count([$constraint->field => $value]);
        }
        if ($result === 0) {
            return;
        }
        // the argument must be a string or an object implementing __toString()
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
        // access your configuration options like this:
    }
}