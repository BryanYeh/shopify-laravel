<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidNonceException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'The request with the state does not contain the same nonce provided from the app.';
}