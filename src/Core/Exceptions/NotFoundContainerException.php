<?php

namespace Milos\JobsApi\Core\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundContainerException extends \Exception implements NotFoundExceptionInterface
{}