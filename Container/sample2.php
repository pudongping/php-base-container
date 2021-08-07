<?php
/**
 * 往容器中注册一个单例对象实例
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-08 00:33
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');

class Flowers {

    protected $water;

    public $color;

    public function __construct(Water $water, $flower_color)
    {
        $this->water = $water;
        $this->color = $flower_color;
    }

}

class Water {

    protected $atmosphere;

    public function __construct(Atmosphere $atmosphere)
    {
        $this->atmosphere = $atmosphere;
    }

}

class Atmosphere {

}

$container = Container::getInstance();

// 往容器中注册一个单例对象实例
$container->singleton('rose', function () use ($container) {
    return $container->make(Flowers::class, ['flower_color' => 'red']);
});

// $container->bind('rose', function () use ($container) {
//     return $container->make(Flowers::class, ['flower_color' => 'red']);
// }, true);

$rose = $container->make('rose');
$rose1 = $container->make('rose');

// output：true
var_dump($rose instanceof $rose1);

// output：true
var_dump($rose === $rose1);

// output：true
var_dump($rose == $rose1);

// output：red
var_dump($rose->color);
