<?php

use Noob\Validator\Validator;
define('ROOT_DIR', dirname(__DIR__));
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/8/10
 * Time: 下午10:21
 */

require (ROOT_DIR."/vendor/autoload.php");

$validate = new Validator(['a' => '','b' => 'abc'],[
    'a' => 'required|number',
    'b' => 'required|number',
    'c' => 'required'
], [
    'a.required' => 'a must required',
    'a.number' => 'a must number',
    'b.required' => 'b must required',
    'b.number' => 'b must number',
    'c.required' => 'c must required'
]);

var_dump($validate->fails());

var_dump($validate->firstError());
var_dump($validate->errors());