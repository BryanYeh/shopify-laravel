<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidHmacException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'This request does not contain a valid Hmac.';
}