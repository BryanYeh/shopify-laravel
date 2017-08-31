<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidMethodRequestException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'The method request is not valid, it must be GET/POST/PUT/DELETE';
}