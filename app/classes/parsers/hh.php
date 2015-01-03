<?php
namespace Parsers;

use Model\PupshevModel;

/**
 * Title
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 18:46
 */
 
 

class HH extends PupshevModel {

    public static function forge(){
        return new self();
    }

    function __construct(){
        parent::__construct();
    }

    public function parse($payed = false){
        if($payed){
            $result = $this->parsePayed();
        } else {
           $result = $this->parseFree();
        }

        return $result;
    }

    private function parsePayed(){
        $result = [];

        return $result;
    }


    private function parseFree(){
        $result = [];

        return $result;
    }



}