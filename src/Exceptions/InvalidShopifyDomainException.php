<?php

namespace Bryanyeh\Shopify\Exceptions;

use Exception;

class InvalidShopifyDomainException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'The request with the domain provided is not a myshopify domain.';
}