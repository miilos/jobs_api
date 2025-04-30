<?php

namespace Milos\JobsApi\Core;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public string $name = '',
        public array $requirements = [],
    )
    {}
}