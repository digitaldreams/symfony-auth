<?php

namespace App\Utils\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueValue extends Constraint
{

    // all configurable options must be passed to the constructor
    public function __construct(
        public string $entity,
        public string $field,
        public string $repositoryMethod = '',
        public bool $currentUser = false,
        public ?string $message = '"{{ string }}" This value is already used.',
        ?array $groups = null,
        $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
