<?php
/**
 * 实现自动依赖注入
 * 反射相关文档： https://www.php.net/manual/zh/book.reflection.php
 *
 * Created by PhpStorm
 * User: Alex
 * Date: 2021-08-03 18:56
 * E-mail: <276558492@qq.com>
 */

class DI
{

    /**
     * 实例化对象，并自动解析依赖树
     *
     * @param $className  类名
     * @param array $params  类参数
     * @return mixed|object
     * @throws ReflectionException
     */
    public static function build($className, $params = [])
    {

        // 如果传入的是一个匿名函数，则直接返回（匿名函数负责实例化对象）
        if ($className instanceof Closure) {
            return $className($params);
        }

        // 对目标类进行反射（解析其方法、属性）
        $reflector = new ReflectionClass($className);

        if (! $reflector->isInstantiable()) {  // 检查类是否可实例化
            throw new RuntimeException("无法实例化 [{$className}] 类");
        }

        // 获取目标类的构造函数，当类不存在构造函数时返回 null
        $constructor = $reflector->getConstructor();
        // 没有构造函数，则直接实例化
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        // 获取构造函数的参数列表
        $parameters = $constructor->getParameters();
        // 递归解析构造函数的参数
        $list = self::resolveDependencies($parameters);

        // 从给出的参数创建一个新的类实例
        return $reflector->newInstanceArgs($list);
    }

    /**
     * 递归解析依赖树
     *
     * @param $parameters  入口类的参数列表
     * @return array
     * @throws ReflectionException
     */
    public static function resolveDependencies($parameters)
    {
        // 用于存储所有的参数
        $list = [];

        // 遍历所有参数
        foreach ($parameters as $parameter) {

            // 这个参数有约束类
            $obj = $parameter->getClass();  // 获取类型提示类

            if (is_null($obj)) {  // 如果为 null，则说明这只是一个普通参数

                // 检查参数是否有默认值
                if ($parameter->isDefaultValueAvailable()) {
                    $list[] = $parameter->getDefaultValue();  // 获取参数的默认值
                } else {
                    throw new Exception('无法处理参数');
                }

            } else {

                // 类型提示确实是一个类名，则递归处理依赖
                $list[] = self::build($obj->name);  // $obj->name 返回类型提示类的类名称

            }

        }

        // 返回所有的参数
        return $list;
    }

}


// 以下为测试代码：依赖依次为 Flowers（花） -> Water（水） -> Atmosphere （大气层）

// Flowers 依赖于 Water （花依赖于水）
class Flowers {

    protected $water;

    public function __construct(Water $water)
    {
        $this->water = $water;
    }

}

// Water 依赖于 Atmosphere （水依赖于大气层）
class Water {

    protected $atmosphere;

    public function __construct(Atmosphere $atmosphere)
    {
        $this->atmosphere = $atmosphere;
    }

}

// （大气层暂时没有依赖）
class Atmosphere {

}

// 如果此时想获取花的实例
$rose = DI::build(Flowers::class);
var_dump($rose);
