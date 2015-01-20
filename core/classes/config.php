<?php
/**
 * Title
 * @autor: Petr Supe <cmdconfig@gmail.com>
 * @date: 7/18/14
 * @time: 6:25 PM
 * @version 1.0
 */
 
 

namespace Core;


class Config {

    /**
     * @var
     */
    public static $items;

    /**
     *  Для загрузки конфига из файла config.php
     * @param $string
     * @return mixed
     */
    public static function get($string){
        static::$items = require('config.php');
        $conf = explode('.',$string);
        $result = static::$items[$conf[0]];
        unset($conf[0]);
        foreach($conf as $key=>$val){
            $result = $result[$val];
        }

        return $result;
    }

    /**
     * @param $string
     * @param $value
     */
    public static function set($string,$value){
        $items = require('config.php');
        $conf = explode('.',$string);
        $result = $items[$conf[0]];
        unset($conf[0]);
        foreach($conf as $key=>$val){
            if(is_null($conf[$key + 1])){
                $result[$val] = $value;
            }

        }


    }
} 