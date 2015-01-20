<?php
return [

    'db'=>[
        'fond_spark'=>[
            'driver'=>'mysql',
            'url' =>'62.117.117.140',
            'user' =>'asm',
            'pass' =>'vKY2erdPu23hSnjj',
            'port' => '3309',
            'dbname'=>'',
            'fond_spark'=>'db_robot.fond_spark'
        ],

    ],


    'proxy'=>[
        'ip'=>[
            ['ip'=>'180.184.97.213:3128','user'=>'','pass'],
            ['ip'=>'125.217.162.136:8080','user'=>'','pass'],
            ['ip'=>'118.98.73.122:80','user'=>'','pass'],
            ['ip'=>'188.138.48.142:8080','user'=>'','pass'],
            ['ip'=>'186.42.121.149:80','user'=>'','pass'],
            ['ip'=>'186.154.241.252:80','user'=>'','pass']

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
        'free_url'=>'http://hh.ru/search/vacancy?clusters=true&specialization=13&area=56&page=',
        'free_item_url'=>'http://hh.ru/vacancy/',
    ]

];
 
  