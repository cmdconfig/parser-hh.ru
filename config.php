<?php
return [

    'db'=>[
        'dsn'=>'mysql:host=ps-st.ru;dbname=pupshev',
        'user'=>'root',
        'password'=>'g47ij0g47ij0970',
        'sql_char'=>'utf8'
    ],

    'proxy'=>[
        'ip'=>[
            ['ip'=>'180.184.97.213:3128','user'=>'','pass'],

        ],
        'list'=>[
            'server'=>'http://proxy-ip-list.com/download/free-proxy-list.txt',
            'file'=>'free-proxy-list.txt'
        ],
        'config' =>[
            'maxInt' => 3
        ],
    ],

    'hh_parser'=>[
        'free_url'=>'http://hh.ru/search/vacancy?clusters=false&specialization=13&area=113&items_on_page=100&page=',
        'free_item_url'=>'http://hh.ru/vacancy/',
    ]


];
 
  