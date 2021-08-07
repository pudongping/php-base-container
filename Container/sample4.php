<?php
/**
 * 将现有实例注册为容器中的共享实例，给定的实例会始终在后面的调用中返回同一个实例对象
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-08 02:15
 * E-mail: <276558492@qq.com>
 */

require_once('./Container.php');

class Student {

    protected $name;

    protected $grade;

}

$container = Container::getInstance();

$student = new Student();

$container->instance(Student::class, $student);

$student1 = $container->make(Student::class);
$student2 = $container->make(Student::class);

var_dump($student);
var_dump($student1);
var_dump($student2);

echo PHP_EOL;

// output：true
var_dump($student === $student1);

// output：true
var_dump($student1 instanceof $student);

// output：true
var_dump($student1 === $student2);

// output：true
var_dump($student2 instanceof $student1);
