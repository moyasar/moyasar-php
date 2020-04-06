<?php

namespace Moyasar\Exceptions;

use Throwable;

class ApiException extends BaseException
{
    protected $type;
    protected $errors;

    public function __construct($message, $type, $errors, $code = 0, $previous = null)
    {
        // Just ignore
        if (! $previous instanceof Throwable) {
            $previous = null;
        }

        parent::__construct($message, $code, $previous);

        $this->type = $type;
        $this->errors = $errors;
    }

    public function type()
    {
        return $this->type;
    }

    public function errors()
    {
        return $this->errors;
    }
}