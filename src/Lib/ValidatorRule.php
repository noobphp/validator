<?php
namespace Noob\Validator\Lib;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-19
 * Time: 16:55
 */

class ValidatorRule extends AbstractValidatorRule
{
    /**
     * 验证数组元素不能为空或者不存在
     * @param $key
     * @return bool
     */
    public function required($key)
    {
        return ! empty($this->data[$key]);
    }

    /**
     * 验证手机
     * @param $key
     * @param $data
     * @return bool
     */
    public function mobile($key)
    {
        $pattern = '/^1[3,4,5,7,8]\d{9}$/';
        return isset($this->data[$key]) && preg_match($pattern, $this->data[$key]);
    }

    /**
     * 验证座机
     * @param $key
     * @param $data
     * @return bool
     */
    public function plane($key)
    {
        $pattern = '/^(0[0-9]{2,3}[-]{0,})?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/';
        return isset($this->data[$key]) && preg_match($pattern, $this->data[$key]);
    }

    /**
     * 验证座机或者手机
     * @param $key
     * @param $data
     * @return bool
     */
    public function phone($key)
    {
        return $this->mobile($key) || $this->plane($key);
    }

    /**
     * 判断是否是上传的文件
     * @param $key
     * @return bool
     */
    public function file($key)
    {
        if (isset($_FILES[$key])) {
            if (is_array($_FILES[$key]['tmp_name'])) {
                foreach ($_FILES[$key]['tmp_name'] as $no => $tmp_name) {
                    if (! is_uploaded_file($tmp_name) || $_FILES[$key][$no] !== 0)
                        return false;
                }
                return true;
            } else {
                return is_uploaded_file($_FILES[$key]['tmp_name']) && $_FILES[$key]['error'] === 0;
            }
        }
        return false;
    }

    /**
     * 验证jpg
     * @param $mime
     * @return bool
     */
    protected function jpg($mime)
    {
        return $mime === 'image/jpeg';
    }

    /**
     * 验证png
     * @param $mime
     * @return bool
     */
    protected function png($mime)
    {
        return $mime === 'image/png';
    }

    protected function zip($mime)
    {
        return $mime === 'application/x-zip-compressed';
    }

    protected function rar($mime)
    {
        return $mime === 'application/octet-stream';
    }

    /**
     * 普通文件使用的验证方法
     * @param $key
     * @param $extension_str
     * @return bool
     */
    public function extension($key, $extension_str)
    {
        $extension_array = $this->getMethodParam($extension_str);
        $mime = [];
        if (is_string($this->data[$key])) {
            if (is_file($this->data[$key])) {
                $mime = [filetype($this->data[$key])];
            } else {
                $mime = [pathinfo($this->data[$key], PATHINFO_EXTENSION)];
            }
        }
        if (! empty($mime))
            return $this->checkFileType($mime, $extension_array);
        return false;
    }

    /**
     * $_FILES文件上传使用的验证方法
     * @param $key
     * @param $extension_str
     * @return bool
     */
    public function fileExtension($key, $extension_str)
    {
        $extension_array = $this->getMethodParam($extension_str);
        if ($this->file($key)) {
            $mime = [];
            //如果是多文件上传
            if (is_array($_FILES[$key]['type'])) {
                $mime = $_FILES[$key]['type'];
            } else {
                $mime = [$_FILES[$key]['type']];
            }
            return $this->checkFileType($mime, $extension_array);
        }
        return false;
    }

    protected function checkFileType(array $mime, array $extension_array)
    {
        $class_method = get_class_methods(get_class());
        //只要有一个extension命中就通过
        foreach ($extension_array as $extension) {
            //如果是一个方法
            if (in_array($extension, $class_method)) {
                foreach ($mime as $file_mime) {
                    //只要有一个mime没有通过就不通过
                    if (! call_user_func([$this, $extension], $file_mime))
                        break;
                    return true; //只要有一个extension命中就通过
                }
            } else {
                //命中自定义的extension就算通过
                if (in_array($extension, $mime))
                    return true;
            }
        }
        return false;
    }

    /**
     * 获取给方法传参的分割符号
     * @return string
     */
    protected function getExplodeMethodParamSign()
    {
        return ',';
    }

    /**
     * 通过分隔符号获取数组
     * @param $param_str
     * @return array
     */
    protected function getMethodParam($param_str)
    {
        return explode($this->getExplodeMethodParamSign(), $param_str);
    }

    /**
     * 验证是否是数字
     * @param $key
     * @return bool
     */
    public function number($key)
    {
        if (! isset($this->data[$key]) || is_numeric($this->data[$key])) {
            return true;
        }
        return false;
    }


    /**
     * 验证是否为整数
     * @param $key
     * @return bool
     */
    public function integer($key)
    {
        if (! isset($this->data[$key]) || ($this->number($key) && ($this->data[$key] == (int)$this->data[$key]))) {
            return true;
        }
        return false;
    }

    public function dateFormat($key, $format)
    {
        $data = $this->getMethodParam($format);

        if (isset($this->data[$key])) {
            foreach ($data as $format) {
                if (! date_create_from_format($format, $this->data[$key]))
                    continue;
                else
                    return true;
            }
            return false;
        }
        return true;
    }

    /**
     * 确认与某个数组元素中的值是否相同
     * @param $key
     * @param $same_key
     * @return bool
     */
    public function same($key, $same_key)
    {
        if (isset($this->data[$key])) {
            if ($this->data[$key] === $this->data[$same_key])
                return true;
            return false;
        }
        return true;
    }
}
