<?php

namespace AzartPay\Azart\Exceptions;

use RuntimeException;

class AzartdException extends RuntimeException
{
    /**
     * Construct new azartd exception.
     *
     * @param object $error
     *
     * @return void
     */
    public function __construct($error)
    {
        parent::__construct($error['message'], $error['code']);
    }
}