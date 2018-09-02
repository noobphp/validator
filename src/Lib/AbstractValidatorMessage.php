<?php
namespace Noob\Validator\Lib;

use Closure;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/9/2
 * Time: 下午12:49
 */

abstract class AbstractValidatorMessage
{
    protected $message = [];
    /**
     * 自定义消息规则
     * @param array $message
     * @return bool
     */
    public final function addMessage(array $message)
    {
        $this->message = array_merge($this->message, $message);
        return true;
    }

    /**
     * 没有设置初始message统一message
     * @param $name
     * @param $arguments
     * @return string
     */
    public final function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (isset($this->message[$name]) && $this->message[$name] instanceof Closure) {
            return $this->message[$name]($arguments[0]);
        }
        return $arguments[0].' '.$name.' error ';
    }
}