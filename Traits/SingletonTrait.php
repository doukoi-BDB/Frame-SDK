<?php


trait SingletonTrait{

    /**
     * @var array
     */
    public static $_instances = [];

    /**
     * 获取单例服务
     * @return mixed|static
     * @author bruce
     */
    public static function getInstance()
    {
        $className = get_called_class();

        if (!isset(self::$_instances[$className])) {
            self::$_instances[$className] = new static;
        }

        return self::$_instances[$className];
    }

}