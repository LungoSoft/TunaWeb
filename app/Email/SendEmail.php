<?php

namespace Tuna\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendEmail 
{
    public static $email;

    public static function send($to, $subject, $message)
    {
        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.xyz.com';
        $mail->SMTPAuth = true;                              // Enable SMTP authentication
        $mail->Username = SendEmail::$email;                 // SMTP username
        $mail->Password = 'secret';                    // SMTP password
        $mail->SMTPSecure = 'none';                          // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom(SendEmail::$email);
        $mail->addAddress($to);     // Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    }
}
