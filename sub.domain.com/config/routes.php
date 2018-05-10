<?php

return [
    'views' => [
        '/' => ['get', 'a.html'],
    ],

    'controllers' => [
        '/sendemail' => ['post', 'SendEmailController'],
    ],

];
