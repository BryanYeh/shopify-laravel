<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidWebHookException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'This request is not an authentic webhook request.';
}