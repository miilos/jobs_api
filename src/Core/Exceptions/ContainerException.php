<?php

namespace Milos\JobsApi\Core\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface
{}