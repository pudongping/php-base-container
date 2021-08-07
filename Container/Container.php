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
    private static $instance;

    /**
     * 容器的绑定
     *
     * @var array[]
     */
    private $bindings = [];

    /**
     * 容器的共享实例
     *
     * @var object[]
     */
    private $instances = [];

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        // self::$instance->bindings[Container::class] = self::$instance;

        return self::$instance;
    }

    /**
     * 在容器中注册共享绑定
     *
     * @param $abstract
     * @param null $concrete
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * 向容器注册绑定
     *
     * @param $abstract
     * @param null $concrete
     * @param false $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if ($concrete instanceof Closure) {
            $this->bindings[$abstract] = compact('concrete', 'shared');
        } else {
            if (! is_string($concrete) || ! class_exists($concrete)) {
                throw new InvalidArgumentException('Argument 2 must be callback or class.');
            }
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');

    }

    /**
     * 从容器解析给定类型
     *
     * @param string $abstract  目标类的名称
     * @param array $parameters  实例化目标类时所需要的参数（非对象类型约束参数数组）
     * @return mixed|object
     */
    public function make(string $abstract, array $parameters = [])
    {

        if (! isset($this->instances[$abstract]) && ! isset($this->bindings[$abstract])) {
            if (! class_exists($abstract)) throw new InvalidArgumentException("Target class [$abstract] does not exist.");
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        try {

            if (isset($this->bindings[$abstract])) {
                $concrete = $this->bindings[$abstract]['concrete'];
                if (is_callable($concrete)) {
                    $instance = $this->resolveCallable($concrete, $parameters);
                } else {
                    $instance = $this->resolveClass($concrete, $parameters);
                }
            } else {
                $instance = $this->resolveClass($abstract, $parameters);
            }

            if (isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared']) {
                $this->instances[$abstract] = $instance;
            }

            return $instance;
        } catch (\Exception $exception) {
            print_r($exception->getTraceAsString());
        }

    }

    /**
     * 解决回调函数时的依赖
     *
     * @param callable $callbackName  目标回调函数
     * @param array $realArgs
     * @return mixed
     * @throws ReflectionException
     */
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

    /**
     * 解决对象时的依赖
     *
     * @param string|object $className  目标类的名称
     * @param array $realArgs
     * @return object  目标类对应的实例对象
     * @throws ReflectionException
     */
    private function resolveClass($className, array $realArgs = [])
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

    /**
     * 递归解析依赖树
     *
     * @param array $dependencies  目标类的构造函数参数列表
     * @param array $parameters  实例化目标类时的其他参数（非类型提示参数）
     * @return array  实例化目标类时构造函数所需的所有参数
     */
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
