# 依赖注入篇

## 目录

├── DI.php  实现自动注入所有的依赖  
└── sample.php  解释了何为依赖注入  

## 测试方式

```php

// 直接使用 php 去执行 php 脚本文件，如：

php sample.php

php DI.php

```

## 什么是控制反转（Inversion of Control）和依赖注入（Dependency Injection）

> 依赖注入（Dependency Injection）是控制反转（Inversion of Control）的一种实现方式。

当调用者需要被调用者的协助时，在传统的程序设计过程中，通常由调用者来创建被调用者的实例，但在这里，创建被调用者的工作不再由调用者来完成，而是将被调用者的创建移到调用者的外部，从而反转被调用者的创建，消除了调用者对被调用者创建的控制，因此称为**控制反转**。

要实现控制反转，通常的解决方案是将创建被调用者实例的工作交由 IoC 容器来完成，然后在调用者中注入被调用者（通过构造器/方法注入实现），这样我们就实现了调用者与被调用者的解耦，该过程被称为**依赖注入**。

IOC：控制反转，从容器获取相关对象就为控制反转（将依赖类的控制权交出去，由主动变为被动）。控制正转就是自己实例自己的对象，给自己使用。而有了容器，就是由它帮助我们完成创建对象的过程。

DI：依赖注入，例如 A 类需要 B 类提供的功能，它们就存在依赖关系，而注入只是把对象 B 直接交给对象 A，不需要在对象 A 中去实例化 B 类。
