<?php
/**
 *
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-07 22:27
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');


class  Family
{
    /**
     * @var int $level
     */
    private $level;

    /**
     * @var string $children_num
     */
    private $children_num;

    /**
     * @var Container $container
     */
    private $container;

    /**
     * Family constructor.
     * @param $level
     * @param $children_num
     * @param $container
     */
    public function __construct($level, $children_num, $container)
    {
        $this->level = $level;
        $this->children_num = $children_num;
        $this->container = $container;
    }

}


class Student
{

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var Family $family
     */
    private $family;

    /**
     * Student constructor.
     * @param $name
     * @param Family $family
     */
    public function __construct($name, Family $family)
    {
        $this->name = $name;
        $this->family = $family;
    }

}

$container = Container::getInstance();

$container->bind(Family::class, function (Container $container) {
    return new Family(3, 2, $container);
});

$alex = $container->make(Student::class, ['name' => 'alex']);
var_dump($alex);
