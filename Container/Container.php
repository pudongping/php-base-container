<?php
/**
 *
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-03 17:51
 * E-mail: <276558492@qq.com>
 */

class Container
{

    /**
     * 当前全局可用的容器(如果有)
     *
     * @var static
     */
    protected static $instance;

    /**
     * 容器的绑定
     *
     * @var array[]
     */
    protected $bindings = [];

    /**
     * 容器的共享实例
     *
     * @var object[]
     */
    protected $instances = [];

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function bind($className, $generator)
    {

    }

    /**
     * 从容器解析给定类型
     *
     * @param $abstract
     * @param array $parameters
     */
    public function make($abstract, array $parameters = [])
    {

    }

    private function resolveCallable(callable $callbackName, array $realArgs = [])
    {
        $reflector = new ReflectionFunction($callbackName);

        // 获取回调函数的参数列表
        $parameters = $reflector->getParameters();
        $list = [];
        if (count($parameters) > 0) {
            $list = $this->resolveDependencies($parameters, $realArgs);
        }

        // 调用函数参数
        return $reflector->invokeArgs($list);
    }

    private function resolveClass(ReflectionParameter $className, array $realArgs = [])
    {
        try {
            // 对目标类进行反射（解析其方法、属性）
            $reflector = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Target class [$className] does not exist.", 0, $e);
        }

        if (! $reflector->isInstantiable()) {  // 检查类是否可以实例化
            throw new RuntimeException("Target class [$className] is not instantiable.");
        }

        // 获取目标类的构造函数，当类不存在构造函数时返回 null
        $constructor = $reflector->getConstructor();
        // 没有构造函数，则直接实例化
        if (is_null($constructor)) {
            // return new $className;  // 或者也可以直接这样去实例化，因为目标类没有构造函数，不需要传参数
            return $reflector->newInstance();
        }

        // 获取构造函数的参数列表
        $parameters = $constructor->getParameters();
        // 递归解析构造函数的参数
        $list = $this->resolveDependencies($parameters, $realArgs);

        // 从给出的参数创建一个新的类实例
        return $reflector->newInstanceArgs($list);
    }

    private function resolveDependencies(array $dependencies, array $parameters = [])
    {
        // 用于存储所有的参数
        $results = [];

        foreach ($dependencies as $dependency) {

            // 获取类型提示类
            $obj = $dependency->getClass();

            // 如果类为 null，则表示依赖项是字符串或其他类型
            if (is_null($obj)) {

                $parameterName = $dependency->getName();  // 获取参数的名称

                // 检查参数是否有默认值
                if (! $dependency->isDefaultValueAvailable()) {
                    if (! isset($parameters[$parameterName])) {
                        throw new RuntimeException($parameterName . ' has no value');
                    } else {
                        $results[] = $parameters[$parameterName];
                    }
                } else {  // 参数有默认值的时候
                    if (isset($parameters[$parameterName])) {
                        $results[] = $parameters[$parameterName];
                    } else {
                        $results[] = $dependency->getDefaultValue();  // 获取参数的默认值
                    }
                }

            } else {  // 类型提示确定是一个类时，则需要递归处理依赖项
                $objName = $obj->getName();  // 获取依赖项的类名
                if (! class_exists($objName)) {
                    throw new RuntimeException('Unable to load class: ' . $objName);
                } else {
                    $results[] = $this->make($objName);
                }
            }

        }

        return $results;
    }

}
