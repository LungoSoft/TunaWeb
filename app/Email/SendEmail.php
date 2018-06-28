<?php

namespace Tuna\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Tuna\Email\Exceptions\ConfigFileNotExist;

class SendEmail 
{
    public static $config;

    public static function send($to, $subject, $message, $nombre = '')
    {
        $mail = new PHPMailer(true);

        $mail->addCustomHeader('Content-Type: text/html; charset=UTF-8');
        $mail->CharSet = 'UTF-8';

        //Server settings
        $mail->SMTPDebug = static::$config['debug'];
        //$mail->isSMTP();
        $mail->Host = static::$config['host'];
        $mail->SMTPAuth = static::$config['smtp_auth'];                // Enable SMTP authentication
        $mail->Username = static::$config['username'];                 // SMTP username
        $mail->Password = static::$config['password'];                 // SMTP password
        $mail->SMTPSecure = static::$config['smtp_secure'];            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = static::$config['port'];                         // TCP port to connect to

        //Recipients
        if($nombre)
            $mail->setFrom(static::$config['email'], $nombre);
        else
            $mail->setFrom(static::$config['email']);
        $mail->addAddress($to);     // Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    }

    public static function init($dir)
    {
        if( !file_exists($dir.'/config/mail.php') )
            throw new ConfigFileNotExist("No exist a $dir/config/mail.php file for config");
        
        static::$config = include $dir.'/config/mail.php';
    }
}
