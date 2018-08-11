<?php

use Noob\Validator\Validator;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018/8/10
 * Time: 下午10:21
 */

require ("../vendor/autoload.php");

$validate = new Validator(['a' => '','b' => 'abc'],[
    'a' => 'required|number',
    'b' => 'required|number'
], [
    'a.required' => 'a is required',
    'a.number' => 'a is number',
    'b.required' => 'b is required',
    'b.number' => 'b is number'
]);

var_dump($validate->fails());

var_dump($validate->firstError());
var_dump($validate->errors());