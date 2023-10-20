<?php

namespace App\Utils\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class FillDto
{
    public function __construct()
    {
    }
}