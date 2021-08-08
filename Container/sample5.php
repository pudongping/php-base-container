<?php
/**
 * 绑定接口到实现
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-08 14:50
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');

interface Board {

    public function type();

}

class NormalBoard implements Board {

    public function type()
    {
        return '普通键盘';
    }

}

class MechanicalKeyboard implements Board {

    public function type()
    {
        return '机械键盘';
    }

}

class Computer {

    protected $keyboard;

    // 这里的 Board 实现由容器的 bind 方法决定
    public function __construct(Board $keyboard)
    {
        $this->keyboard = $keyboard;
    }

    public function getType()
    {
        return $this->keyboard->type();
    }

}


// $container = new Container();
$container = Container::getInstance();

$container->bind('keyBoard', function () {
    // return new NormalBoard();  // 如果哪天不想用普通键盘了，想换成机械键盘，那么只需要修改这里即可，那么代码中所有的位置都将换成了机械键盘
    return new MechanicalKeyboard();
});

$container->bind('Computer', function (Container $container, $module) {
    return new Computer($container->make($module));
});

$computer = $container->make('Computer', ['module' => 'keyBoard']);

var_dump($computer);

// output：普通键盘 or 机械键盘（由上面的绑定决定）
var_dump($computer->getType());
