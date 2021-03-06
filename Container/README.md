# php 简单容器实现篇

## 目录

├── Container.php  实现一个简单的 php 容器  
├── sample.php  实例绑定到容器及传参  
├── sample1.php  通过容器绑定非对象实例（保存任何值到容器中）  
├── sample2.php  往容器中注册一个单例对象实例  
├── sample3.php  直接通过容器获取对象实例  
├── sample4.php  将现有实例注册为容器中的共享实例  
└── sample5.php  绑定接口到实现  

## 测试方式

```php

// 直接使用 php 去执行 php 脚本文件，如：

php sample.php

```

## 什么是容器

容器是一个用于管理类依赖以及实现依赖注入的强有力工具。服务容器用于存储各种注入到容器中的类或对象实例，
使得依赖注入更加方便。它的实现逻辑大致是：首先对象实例会通过绑定到容器，然后再通过反射机制获取里面的对象实例。也可以不通过绑定，直接通过容器去
创建对应的对象实例。

## 为什么使用 `bind` 方法的时候要用到匿名函数？

这样做的好处是用到该依赖时才会实例化，从而提升了应用的性能。
