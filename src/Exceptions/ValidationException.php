<?php

namespace Moyasar\Exceptions;

use Throwable;

class ValidationException extends BaseException
{
    /**
     * @var array[]
     */
    protected $errors;

    public function __construct($message, $errors, $code = 0, Throwable $previous = null)
    {
        $errorMessages = '';

        foreach ($errors as $key => $value) {
            $errorMessages .= $key . ': ' . implode(', ', $value) . "\n";
        }

        $message .= "\nErrors:\n" . $errorMessages;

        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    public function errors()
    {
        return $this->errors;
    }
}