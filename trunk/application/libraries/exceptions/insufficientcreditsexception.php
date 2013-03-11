<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 3/11/13
 * Time: 4:01 PM
 * To change this template use File | Settings | File Templates.
 */

class InsufficientCreditsException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}