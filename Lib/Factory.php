<?php



class Factory{


    /**
     * 工厂声明类
     *
     * @param $sFullClassName
     * @return bool|mixed
     */
    public static function createClass($sFullClassName){

        if(empty($sFullClassName)){
            return false;
        }

        if (!isset(SingletonTrait::$_instances[$sFullClassName])) {
            SingletonTrait::$_instances[$sFullClassName] = new $sFullClassName;
        }

        return SingletonTrait::$_instances[$sFullClassName];
    }

}