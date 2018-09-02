<?php
namespace Noob\Validator\Lib;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/9/2
 * Time: 下午12:06
 */

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function createValidatorMessage()
    {
        // TODO: Implement createValidatorMessage() method.
        return new ValidatorMessage();
    }

    public function createValidatorRule(array $data)
    {
        // TODO: Implement createValidatorRule() method.
        return new ValidatorRule($data);
    }
}