<?php
/**
 *Подгрузка прокси листа
 * @autor: Petr Supe <cmdconfig@gmail.com>
 * @date: 7/20/14
 * @time: 12:04 AM
 * @version 1.0
 */
 
 

namespace Core;

class Proxy {
    /**
     * Последний использованный прокси сервре
     * @var int
     */
    private static $lastProxy = 0;
    /**
     * список IP
     * @var array
     */
    private static $proxyList = [];
    /**
     * Количество запросов для получения прокси серверов
     * @var int
     */
    private static $int = 0;

    /**
     * Основной метод возвращает IP
     * @return bool|int
     */
    public static function init(){
       if(empty(self::$proxyList)){
           if(!self::getProxyList()){
               return false;
           }
       }
        while(1 == 1){
            $count = rand(0,count(self::$proxyList) - 1);
            if(self::$lastProxy != $count && isset(self::$proxyList[$count])){
                self::$lastProxy = $count;
                return self::$proxyList[$count];
                break;
            }
        }

        return self::$lastProxy ;
    }

    /**
     * Метод выдирает IP из файла
     * @return bool
     */
    public static function getProxyList(){
        $fileName = '../../'.Config::get('proxy.list.file');
        if(file_exists($fileName)){
            $file = file_get_contents($fileName);
            preg_match_all("/([\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\:[\d]{1,4});/Uis",$file,$mch);
            if(!empty($mch[1])){
                self::$proxyList = $mch[1];
                return true;
            }
        }else {
            if(self::$int > Config::get('proxy.config.maxInt')) {
                self::$int = 0;
                return false;
            }
            self::updateProxyList();
            self::$int++;
            self::getProxyList();
        }
    }

    /**
     * Метод качает список проксей
     */
    public static function updateProxyList(){
        $pl = Config::get('proxy.list.server');
        $data = file_get_contents($pl);
        file_put_contents('../../'.Config::get('proxy.list.file'),$data);
    }

} 