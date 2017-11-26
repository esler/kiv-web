<?php
namespace Esler\KivWeb\Exception;

use Exception;
use Throwable;

class AbstractException extends Exception
{

    /**
     * Constructor
     *
     * @param string         $message  message of an exception
     * @param Throwable|null $previous optional reason
     */
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, static::CODE, $previous);
    }
}
