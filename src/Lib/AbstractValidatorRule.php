<?php
namespace Noob\Validator\Lib;

use Closure;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/9/2
 * Time: 下午12:19
 */

abstract class AbstractValidatorRule
{
    protected $rules = []; //自定义规则
    protected $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public final function setValidateData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 添加自定义规则
     * @param array $rules
     * @return bool
     */
    public final function addRule(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
        return true;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public final function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //如果满足自定义规则
        if (isset($this->rules[$name]) && $this->rules[$name] instanceof Closure) {
            return $this->rules[$name]($arguments[0]);
        }
        return true;
    }
}