<?php
/**
 * Title
 * Aautor: Petr Supe <cmdconfig@gmail.com
 * User: asm
 * Date: 03.01.15
 * Time: 19:35
 */
 
 

namespace Model;


class Curl {
    /**
     * @var string
     */
    private  $uAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)';

    /**
     * @return Curl
     */
    public static function forge(){
        return new self();
    }

    function __construct(){}


    /**
     * @param string $url
     * @param string $cookieFile
     * @return mixed
     */
    public function getUrl($url,$cookieFile = ''){
        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, $this->uAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        $content = curl_exec( $ch );
        $err = curl_errno( $ch );
        $errMsg = curl_error( $ch );
        $header = curl_getinfo( $ch );
        curl_close( $ch );

        $postData['agent_pass'] = '*********';
        $header['postData'] = $postData;
        $header['errNo'] = $err;
        $header['errMsg'] = $errMsg;
        $header['content'] = $content;
        $this->lastRequestData = $header;

        return $header;
    }

    /**
     * @param string $url
     * @param array $postData
     * @param string $cookieFile
     * @return mixed
     */
    public function login($url,$postData,$cookieFile = ''){
        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, $this->uAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        $content = curl_exec( $ch );
        $err = curl_errno( $ch );
        $errMsg = curl_error( $ch );
        $header = curl_getinfo( $ch );
        curl_close( $ch );

//        $postData['agent_pass'] = '*********';
//        $header['postData'] = $postData;
        $header['errNo'] = $err;
        $header['errMsg'] = $errMsg;
        $header['content'] = $content;
        $this->lastRequestData = $header;

        return $header;
    }
}