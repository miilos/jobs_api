<?php

namespace Milos\JobsApi\Middleware;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class Middleware
{
    public function __construct(
       public string $method,
       public string $path,
       public int $priority = 1,
    ) {}
}