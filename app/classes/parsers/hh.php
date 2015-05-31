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
    private function checkLogin(){
        echo("checkLoginr\n");
        if(strpos($this->html,'Зарегистрироваться') > 0){
            $this->login();
        }
    }

    /**
     * @return array
     */
    private function goPayed(){
        $this->checkLogin();
        $result = [];
        echo('next payed page '.date("Y-m-d H:i:s")."\r\n\r\n");

        $url = Config::get('hh_parser.payed_url').$this->page;

        $data = Curl::forge()->getUrl($url,$this->cookieFile);
        $this->html = $data['content'];
        $this->getCountPages();

        for($i = 0; $i <= $this->maxPages ;$i++){
            $list = $this->getLinks();

            foreach($list as $val){
                print_r('item free'.date("Y-m-d H:i:s")."\r\n");
                $this->parsePayedList($val);
                $this->runAdd();

                sleep(rand(20,80));
            }
            if($this->countRequests < Config::get('maxRequests')){
                $this->page = $i;
            } else {
                $this->page = 0;
            }
            $this->goFree();

        }


        return $result;

    }

    /**
     * @return array
     */
    private function goFree($back = false){
        $result = [];
        echo('next free page '.date("Y-m-d H:i:s")."\r\n\r\n");

        $url = Config::get('hh_parser.free_url').$this->page;

        $data = Curl::forge()->getUrl($url,$this->cookieFile);
        $this->html = $data['content'];
        $this->getCountPages();

        for($i = 0; $i <= $this->maxPages ;$i++){
            $list = $this->getLinks();

            foreach($list as $val){
                print_r('item free'.date("Y-m-d H:i:s")."\r\n");
                $this->parseFreeList($val);
                $this->runAdd();

                sleep(rand(20,80));
            }
            if($this->countRequests < Config::get('maxRequests')){
                $this->page = $i;
            } else {
                $this->page = 0;
            }
            $this->goFree();

        }


        return $result;
    }

    /**
     *
     */
    private function getCountPages(){
        $this->maxPages = substr_count($this->html,'pager-page');
    }

    /**
     * @return mixed
     */
    private function getLinks(){
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
        unset($this->data);

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

        $this->data['hash'] = md5(join('',$this->data));
    }

    /**
     * @param $item
     */
    private function parsePayedList($item){

        var_dump('parsePayedList');
        $this->checkLogin();
        $url = Config::get('hh_parser.payed_item_url').$item;
        $data = Curl::forge()->getUrl($url,$this->cookieFile);
        $html = $data['content'];


        preg_match('#birthDate" content="(.*)">#Uis',$html,$mch);
        $this->data['birthDate'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#itemprop="gender">(.*)</strong>#Uis',$html,$mch);
        $this->data['sex'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#itemprop="addressLocality">(.*)</strong>#Uis',$html,$mch);
        $this->data['addressLocality'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<span style="color:#702785">(.*)</span>#Uis',$html,$mch);
        $this->data['addressLocality'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#itemprop="jobTitle">(.*)</div>#Uis',$html,$mch);
        $this->data['jobTitle'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#resume__position__salary">(.*)</div>#Uis',$html,$mch);
        $this->data['zp'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<div class="resume__position__specialization">(.*)<ul>#Uis',$html,$mch);
        $this->data['specialization'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#resume__position__specialization_item">(.*)</li>#Uis',$html,$mch);
        $this->data['specializationType'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#<div>Занятость:(.*)</div>#Uis',$html,$mch);
        $this->data['employment'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#itemtype="http://schema.org/Country"><span itemprop="name">(.*)</span>#Uis',$html,$mch);
        $this->data['country'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#Разрешение на работу:(.*)</div>#Uis',$html,$mch);
        $this->data['workPermit'] = (!empty($mch[1]) ? trim($mch[1]) : '');

        preg_match('#Желательное время в пути до работы: <span style="text-transform:lowercase">(.*)</span></div>#Uis',$html,$mch);
        $this->data['travelTime'] = (!empty($mch[1]) ? trim($mch[1]) : '');
        $this->data['hash'] = md5(join('',$this->data));
    }

    /**
     *
     */
    private function login(){
        $postData=[
            'username'=>Config::get('hh_parser.username'),
            'password'=>Config::get('hh_parser.password'),
        ];

        $url = Config::get('hh_parser.login_url');
        Curl::forge()->login($url,$postData,$this->cookieFile);
    }
}