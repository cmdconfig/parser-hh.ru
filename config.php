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

        'payed_url'=>'http://hh.ru/search/resume?text=&logic=normal&pos=full_text&exp_period=all_time&specialization=13&area=113&relocation=living_or_relocation&salary_from=&salary_to=&currency_code=RUR&education=none&age_from=&age_to=&gender=unknown&order_by=publication_time&search_period=0&items_on_page=100',
        'payed_item_url'=>'http://hh.ru/vacancy/',

        'login_url'=>'https://hh.ru/account/login',
        'username'=>'cmdconfig@gmail.com',
        'password'=>'zse4rfvcxz',

    ],
    'maxRequests'=>100


];
 
  