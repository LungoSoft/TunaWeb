<?php

namespace Tuna\Http\Controllers;

class SendEmailController
{
    public function postIndex()
    {
        //SendEmail::send('to@email.com', 'Subject', 'Mensaje', 'From..');
        echo 'I will send an email to <strong>'.$_POST['nombre'].' - '.$_POST['correo'].'</strong> con asunto <strong>'.$_POST['asunto'].'</strong> '.
        'y el mensaje <strong>'.$_POST['mensaje'].'</strong>';
    }
}