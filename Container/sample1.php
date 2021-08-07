<?php
/**
 * 通过容器绑定非对象实例
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-08 00:14
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');

$container = new Container();

$container->bind('counter', function () {
    return '1 * 2 * 3 = ' . strval(1 * 2 * 3);
});

$counter = $container->make('counter');

// output："1 * 2 * 3 = 6"
var_dump($counter);
