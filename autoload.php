<?php

header("Content-Type:text/html;charset=utf-8");
/**
 * 遇到不认识的类名称时自动执行这段代码，实现自动引入类文件.
 * 在需要引入其他类文件的php中开头直接引入此文件即可
 */
spl_autoload_register(function($className){
    require_once $className . ".class.php";
});