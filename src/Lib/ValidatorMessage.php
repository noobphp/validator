<?php
namespace Noob\Validator\Lib;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-19
 * Time: 16:59
 */

class ValidatorMessage extends AbstractValidatorMessage
{
    /**
     * required初始message
     * @param $key
     * @return string
     */
    public function required($key)
    {
        return $key.' must required';
    }
}