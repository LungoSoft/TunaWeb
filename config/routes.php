<?php

return [
    'views' => [
        '/' => ['get', 'principal.html'],
    ],

    'controllers' => [
        '/sendemail' => ['post', 'SendEmailController'],
    ],

];
