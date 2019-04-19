<?php

namespace micetm\conditionsBase\exceptions;

use Throwable;
use \Exception;

class WrongComparison extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Wrong comparison type!", $code, $previous);
    }
}