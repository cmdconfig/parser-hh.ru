<?php
/**
 * Title
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 18:42
 */
 
 

namespace Model;


abstract class PupshevModel{

    protected $data;


    function __construct(){

    }


    private function addToBase($data){

    }

    public function run(){
        $data = $this->parse();

        $this->addToBase($data);
    }


    abstract function parse();



}