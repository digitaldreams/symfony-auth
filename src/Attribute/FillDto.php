<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FillDto
{
    public function __construct(public readonly string $method = 'POST')
    {
    }
}