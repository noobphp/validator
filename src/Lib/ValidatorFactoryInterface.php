<?php
namespace Noob\Validator\Lib;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/9/2
 * Time: 下午12:04
 */
interface ValidatorFactoryInterface
{
    public function createValidatorRule(array $data);

    public function createValidatorMessage();
}