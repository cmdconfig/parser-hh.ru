<?php
namespace Parsers;

use Core\Config;
use Model\Curl;
use Model\PupshevModel;

/**
 * Парсер hh.ru
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 18:46
 */
 
 

class HH extends PupshevModel {
    /**
     * @var string
     */
    private $cookieFile = '';

    /**
     * @var int
     */
    private $page = 19;
    /**
     * @var int
     */
    private $maxPages = 0;

    /**
     * @var array
     */
    private $links = [];
    /**
     * @var string
     */
    private $html = '';

    /**
     * @return HH
     */
    public static function forge(){
        return new self();
    }

    function __construct(){
        $this->cookieFile = 'data/hh_'.time().'.txt';
        parent::__construct();
    }

    function __destruct(){
        unset($this->cookieFile);
    }

    /**
     * @param bool $payed
     * @return array
     */
    public function parse($payed = false){
        if($payed){
            $result = $this->goPayed();
        } else {
           $result = $this->goFree();
        }

        return $result;
    }

    /**
     * @return array
     */
    private function goPayed(){
        $result = [];

        return $result;
    }

    /**
     * @return array
     */
    private function goFree($back = false){
        $result = [];

        $url = Config::get('hh_parser.free_url').$this->page;

        $data = Curl::forge()->getUrl($url,$this->cookieFile);
        $this->html = $data['content'];
        $this->getCountFreePages();
        for($i = 18; $i >= 0 ;$i--){
            $list = $this->getFreeLinks();
            foreach($list as $val){
                $this->parseFreeList($val);
                $this->runAdd();

                sleep(rand(20,80));
            }
            $this->page = $i;
            $this->goFree();
        }


        return $result;
    }

    /**
     *
     */
    private function getCountFreePages(){
        $this->maxPages = substr_count($this->html,'pager-page');
    }

    /**
     * @return mixed
     */
    private function getFreeLinks(){
        preg_match_all('#hh.ru/vacancy/([0-9]{1,})"#Uis',$this->html,$mch);

        if(!empty($mch[1])){
            return $mch[1];
        }

        return false;
    }

    /**
     * @param int $item
     */
    private function parseFreeList($item){
        var_dump('parseFreeList');
        $url = Config::get('hh_parser.free_item_url').$item;
        $data = Curl::forge()->getUrl($url,$this->cookieFile);
        $html = $data['content'];
        //Название вакансии
//        echo $html;die();
        preg_match('#<h1 class="title b-vacancy-title">(.*)</h1>#Uis',$html,$mch);
        $this->data['vacancy'] = (!empty($mch[1]) ? $mch[1] : '');

        //название работодателя
        preg_match('#><div class="companyname">(.*)</div>#Uis',$html,$mch);
        if(!empty($mch[1])){
            $this->data['company_link'] = $mch[1];
            //<a itemprop="hiringOrganization" href="/employer/19804"> СМ-Клиника</a>
            preg_match('#>(.*)</a>#Uis',$mch[1],$comp_name);
            if(!empty($comp_name[1])){
                $this->data['company_name'] = $comp_name[1];
            }
        } else {
            $this->data['company_link'] = '';
            $this->data['company_name'] = '';
        }

        preg_match('#<td class="l-content-colum-1 b-v-info-content"><div class="l-paddings">(.*)</div></td>#Uis',$html,$mch);
        $this->data['zp'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#<td class="l-content-colum-2 b-v-info-content"><div class="l-paddings">(.*)</div>#Uis',$html,$mch);
        $this->data['city'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<td class="l-content-colum-3 b-v-info-content"><div class="l-paddings" itemprop="experienceRequirements">(.*)</div></td>#',$html,$mch);
        $this->data['experience'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<strong>Обязанности: </strong>(.*)</ul>#Uis',$html,$mch);
        $this->data['duties'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#<strong>Требования:</strong>(.*)</ul>#Uis',$html,$mch);
        $this->data['demands'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#<strong>Условия:</strong>(.*)</ul>#Uis',$html,$mch);
        $this->data['conditionTxt'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#приглашаем:</strong>(.*)</ul>#Uis',$html,$mch);
        $this->data['welcome'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#предлагаем:</strong>(.*)</ul>#Uis',$html,$mch);
        $this->data['offer'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#<div class="b-hhgmap-address HH-Maps-ShowAddress-Address">(.*)</div>#Uis',$html,$mch);
        $this->data['address'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<span itemprop="employmentType">(.*)</div>#Uis',$html,$mch);
        $this->data['employmentType'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

        preg_match('#datetime="(.*)\">#Uis',$html,$mch);
        $this->data['datetime'] = (!empty($mch[1]) ? trim(strip_tags($mch[1])) : '');

//        var_dump($this->data);

        $this->data['hash'] = md5(join('',$this->data));

//        var_dump($this->data);


    }
}