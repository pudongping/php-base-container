<?php
/**
 * 直接通过容器获取对象实例（不再需要自己手动 new，也不需要自己去解决依赖）
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-08 01:07
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');

class Water {

    protected $category = '农夫三拳';

    protected $price = 0;

}

class Human {

    protected $clothes = 'shirt';

    protected $hometown = 'Hubei';

    protected $water;

    public function __construct(Water $water, $my_clothes, $my_hometown)
    {
        $this->clothes = $my_clothes;
        $this->hometown = $my_hometown;
        $this->water = $water;
    }

    public function __get($attribute)
    {
        return $this->{$attribute};
    }

}

$container = Container::getInstance();

$alex = $container->make(Human::class, ['my_clothes' => 'T-shirt', 'my_hometown' => 'Chongqing']);

var_dump($alex);

// output：T-shirt Chongqing
var_dump($alex->clothes, $alex->hometown);

$alex1 = $container->make(Human::class, ['my_clothes' => 'T-shirt', 'my_hometown' => 'Chongqing']);

// output：false
var_dump($alex === $alex1);
