<?php
namespace Noob\Validator;

use Closure;
use Noob\Validator\Lib\ValidatorFactory;
use Noob\Validator\Lib\ValidatorFactoryInterface;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-12
 * Time: 9:42
 */

/**
 * @example
 *     $validate = new Validator($arr, $validate, $error_message);
 *     //如果验证出错
 *     if ($validate->fails()) {
 *         $errors = $validate->errors(); //获取错误信息
 *     } else {
 *         ...
 *     }
 */

/**
 * 数据验证类
 */
class Validator
{
    protected $validate; //验证数据的规则
    protected $error_message; //验证失败的错误信息
    private $errors; //验证失败返回的完整错误信息
    private $rule_obj; //内置规则对象
    private $message_obj; //内置错误信息对象

    /**
     * Validator constructor.
     * @param array $data
     * @param array $validate
     * @param array $error_message
     * @param Closure|null $callback
     */
    public function __construct(array $data, array $validate = [], array $error_message = [], ValidatorFactoryInterface $validatorFactory = null)
    {
        $this->validate = $validate;
        //初始化错误信息数组
        $this->error_message = $this->parseErrorMessage($error_message);

        if (! $validatorFactory) {
            $validatorFactory = new ValidatorFactory();
        }

        $this->rule_obj = $validatorFactory->createValidatorRule($data);
        $this->message_obj = $validatorFactory->createValidatorMessage();

        //验证
        $this->validate();
    }

    /**
     * 检查验证是否没有通过
     * @return bool
     */
    public function fails()
    {
        return ! empty($this->errors());
    }

    /**
     * 返回所有的错误信息
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * 返回第一条错误字符串
     * @return mixed
     */
    public function firstError()
    {
        return array_shift(array_shift($this->errors()));
    }

    /**
     * 从$error_message中寻找错误信息,没有赋一个初始值
     * @param $key
     * @param $validate_func_name
     * @return string
     */
    private function getErrorMessage($key, $validate_func_name)
    {
        $error_str = '';
        if (! empty($this->error_message[$key][$validate_func_name])) {
            $error_str = $this->error_message[$key][$validate_func_name];
        } else {
            $error_str = $this->getDefaultErrorMessage($key, $validate_func_name);
        }
        return $error_str;
    }

    /**
     * 验证方法
     */
    private function validate()
    {
        foreach ($this->validate as $field => $rule_of_validate) {
            //获取要验证的规则名
            $validate_func_arr = explode($this->getValidateExplodeSign(), $rule_of_validate);
            foreach ($validate_func_arr as $validate_func_name) {
                $param = [$field];
                //如果有参数分隔符再次进行分隔并添加参数
                if (mb_strpos($validate_func_name, $this->getMethodParamSign())) {
                    list($validate_func_name, $method_param) = explode($this->getMethodParamSign(), $validate_func_name);
                    array_push($param, $method_param);
                }
                //调用rule类的方法（规则名即是方法名）将字符串参数传入
                $suc = call_user_func_array([$this->rule_obj, $validate_func_name], $param);
                if (! $suc) {
                    //如果验证失败添加错误信息
                    $this->errors[$field][$validate_func_name] = $this->getErrorMessage($field, $validate_func_name);
                }
            }
        }
        return;
    }

    /**
     * 初始化$error_message数组
     * @param array $message
     * @return array
     */
    private function parseErrorMessage(array $message)
    {
        $new_error_message = [];
        foreach ($message as $field_and_rule => $message_str) {
            list($field, $rule) = explode($this->getErrorExplodeSign(), $field_and_rule);
            $new_error_message[$field][$rule] = $message_str;
        }
        return $new_error_message;
    }

    /**
     * 获得$error_message数组规则的分隔符
     * @return string
     */
    private function getErrorExplodeSign()
    {
        return '.';
    }

    /**
     * 获得$validate数组规则的分隔符
     * @return string
     */
    private function getValidateExplodeSign()
    {
        return '|';
    }

    /**
     * 获得给方法传递参数的符号
     * @return string
     */
    private function getMethodParamSign()
    {
        return ':';
    }

    /**
     * 获取默认错误消息
     * @param $key
     * @param $rule
     * @return mixed
     */
    private function getDefaultErrorMessage($key, $rule)
    {
        return call_user_func([$this->message_obj, $rule], $key);
    }
}
