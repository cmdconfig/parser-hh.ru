<?php
/**
 * Title
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 18:56
 */
 
 

class Controller_Parsers {

    function __construct(){
        return $this;
    }


    public function index(){
        Parsers\HH::forge()->parse();
    }
}