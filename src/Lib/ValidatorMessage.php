<?php
namespace Noob\Validator\Lib;

use Closure;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-19
 * Time: 16:59
 */

class ValidatorMessage
{
    protected $message = [];
    /**
     * required初始message
     * @param $key
     * @return string
     */
    public function required($key)
    {
        return $key.' must required';
    }

    /**
     * 自定义消息规则
     * @param array $message
     * @return bool
     */
    public function addMessage(array $message)
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
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (isset($this->message[$name]) && $this->message[$name] instanceof Closure) {
            return $this->message[$name]($arguments[0]);
        }
        return $arguments[0].' '.$name.' error ';
    }
}