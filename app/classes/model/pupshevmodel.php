<?php
/**
 * Title
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 18:42
 */
 
 

namespace Model;


use Core\Config;

abstract class PupshevModel{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var \PDO
     */
    private $db;


    function __construct(){
        $this->connectToBase();
    }

    private function connectToBase(){
        try {
            $this->db = new \PDO(
                Config::get('db.dsn'),
                Config::get('db.user'),
                Config::get('db.password')

            );

            $this->db->query ( 'SET character_set_connection = '.Config::get('db.sql_char') );
            $this->db->query ( 'SET character_set_client = '.Config::get('db.sql_char') );
            $this->db->query ( 'SET character_set_results = '.Config::get('db.sql_char') );
        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }

    /**
     *
     */
    private function addToBaseFree(){

        var_dump('addToBaseFree');
        $ins = $this->db->prepare("INSERT INTO parsers_free_data (
            vacancy,
            company_link,
            company_name,
            zp,
            city,
            experience,
            duties,
            demands,
            conditionTxt,
            offer,
            address,
            employmentType,
            datetime,
            hash
        ) VALUES (
            :vacancy,
            :company_link,
            :company_name,
            :zp,
            :city,
            :experience,
            :duties,
            :demands,
            :conditionTxt,
            :offer,
            :address,
            :employmentType,
            :datetime,
            :hash
        )");

        $ins->bindParam(':vacancy',$this->data['vacancy'],\PDO::PARAM_STR);
        $ins->bindParam(':company_link',$this->data['company_link'],\PDO::PARAM_STR);
        $ins->bindParam(':company_name',$this->data['company_name'],\PDO::PARAM_STR);
        $ins->bindParam(':zp',$this->data['zp'],\PDO::PARAM_STR);
        $ins->bindParam(':city',$this->data['city'],\PDO::PARAM_STR);
        $ins->bindParam(':experience',$this->data['experience'],\PDO::PARAM_STR);
        $ins->bindParam(':duties',$this->data['duties'],\PDO::PARAM_STR);
        $ins->bindParam(':demands',$this->data['demands'],\PDO::PARAM_STR);
        $ins->bindParam(':conditionTxt',$this->data['conditionTxt'],\PDO::PARAM_STR);
        $ins->bindParam(':offer',$this->data['offer'],\PDO::PARAM_STR);
        $ins->bindParam(':address',$this->data['address'],\PDO::PARAM_STR);
        $ins->bindParam(':employmentType',$this->data['employmentType'],\PDO::PARAM_STR);
        $ins->bindParam(':datetime',$this->data['datetime'],\PDO::PARAM_STR);
        $ins->bindParam(':hash',$this->data['hash'],\PDO::PARAM_STR);

        $ins->execute();
    }

    /**
     * @return bool
     */
    private function checkFreeBaseData(){
        var_dump('checkFreeBaseData');
        $sel =$this->db->prepare("SELECT id FROM parsers_free_data WHERE(hash = :hash)");
        $sel->bindParam(':hash',$this->data['hash'],\PDO::PARAM_STR);
        $sel->setFetchMode(\PDO::FETCH_ASSOC);

       $sel->execute();
        $result = $sel->fetch();

        var_dump($result);
        if(empty($result)){
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param bool $free
     */
    public function runAdd($free = true){
//        $this->parse();


//        die();
//        var_dump($this->data);
        if($free){
            if($this->checkFreeBaseData() == true){
                $this->addToBaseFree();
                var_dump('runAdd ok');
                unset($this->data);
            }
        }

    }


    /**
     * @return mixed
     */
    abstract function parse();



}