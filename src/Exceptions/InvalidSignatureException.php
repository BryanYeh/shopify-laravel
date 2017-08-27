<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidSignatureException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'This request does not contain a valid signature.';
}