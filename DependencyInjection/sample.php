<?php
/**
 * 简单描述何为依赖注入
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-03 19:00
 * E-mail: <276558492@qq.com>
 */

/**
 * 定义一个「花」的类
 *
 * Class Flowers
 */
class Flowers
{
    /**
     * @var 叶子
     */
    protected $leaves;

    /**
     * @var 躯干
     */
    protected $trunk;

    protected $water;

    // 以下是不使用依赖注入的做法
    // public function __construct()
    // {
    //     // 花依赖水（如果没有水，花就要枯萎）
    //     $this->water = new Water();
    // }

    // 以下是使用依赖注入的做法
    public function __construct(Water $water)
    {
        // 花依赖水（如果没有水，花就要枯萎）
        $this->water = $water;
    }

}

/**
 * 定义一个「水」的类
 *
 * Class Water
 */
class Water
{

    /**
     * @var 类型
     */
    protected $category;

}


/**
 * 不使用依赖注入的做法：
 *
 * 如果是以前，我们通常会这么去操作，但是如果依赖项太多了的话，
 * 我们都得去每个类中手动实例化依赖对象，那么维护性就变得尤为复杂
 *（试想，如果 A 类依赖于 B 类，B 类依赖于 C 类，C 类还同时依赖 D 类、E 类、F 类……）
 **/
// $rose = new Flowers();
// var_dump($rose);

// =================================================

/**
 * 使用依赖注入的做法：
 *
 * 直接将依赖项通过构造函数传递进去，虽然相对于以上的方式可能会好很多，
 * 但是这样还是不能够实现自动管理依赖，因此就需要借助「反射」模块，
 * 来逐层解析每一个依赖类的构造函数，推导出相关的依赖，然后注入即可。
 * 因为依赖层级不定，所以需要使用递归实现。详见本目录下 DI 类。
 */

// 将实例化过程放在 Flowers 的外部进行
$rose = new Flowers(new Water());
var_dump($rose);
