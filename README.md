# php-base-container

> write a base container for php

使用 laravel 框架也有好长时间了，我们都知道 laravel 的核心是 laravel 的服务容器，一般情况下我们都停留在只会使用阶段，
并不是很理解他的实现原理，因此我就花了一部分时间通过查看源码和查询资料整理了一个使用 php 写的容器，其实更多的是需要查看
[php 的反射文档](http://php.net/manual/zh/book.reflection.php)。 更多的细节，我都在源码中有注释信息。

## Talk is cheap. Show me the code!

- [容器源码仓库地址](https://github.com/pudongping/php-base-container)
- [容器的实现](./Container)
- [DI 依赖注入](./DependencyInjection)
