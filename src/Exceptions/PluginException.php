<?php
namespace Gallery\Plugin\Exceptions;

class PluginException extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null) {
        if ($message instanceof \Exception) {
            parent::__construct($message->getMessage(), $message->getCode(), $message->getPrevious());
        } else {
            parent::__construct($message, $code, $previous);
        }
    }
}
