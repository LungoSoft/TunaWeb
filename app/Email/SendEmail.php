<?php

namespace Tuna\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendEmail 
{
    public static $config;

    public static function send($to, $subject, $message)
    {
        $mail = new PHPMailer(true);

        $mail->addCustomHeader('Content-Type: text/html; charset=UTF-8');
        $mail->CharSet = 'UTF-8';

        //Server settings
        $mail->SMTPDebug = SendEmail::$config['debug'];
        //$mail->isSMTP();
        $mail->Host = SendEmail::$config['host'];
        $mail->SMTPAuth = SendEmail::$config['smtp_auth'];                              // Enable SMTP authentication
        $mail->Username = SendEmail::$config['username'];                 // SMTP username
        $mail->Password = SendEmail::$config['password'];                    // SMTP password
        $mail->SMTPSecure = SendEmail::$config['smtp_secure'];                          // Enable TLS encryption, `ssl` also accepted
        $mail->Port = SendEmail::$config['port'];                                  // TCP port to connect to

        //Recipients
        if($nombre)
            $mail->setFrom(SendEmail::$config['email'], $nombre);
        else
            $mail->setFrom(SendEmail::$config['email']);
        $mail->addAddress($to);     // Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    }
}
