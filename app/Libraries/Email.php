<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Email
{
    protected $email;
    protected $subject;
    protected $message;
    public function setTo($email){
        $this->email=$email;
    }
    public function setSubject($subject):void{
        $this->subject=$subject;
    }
    public function setMessage($message){
        $this->message=$message;
    }
    public function send()
    {
        $mail = new PHPMailer();
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Timeout = 20;
            $mail->SMTPDebug = 0;
            //Set the hostname of the mail server
            $mail->Host = 'ssl://smtp.googlemail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "artps.nic@gmail.com";
            $mail->Password = "ARTPS@2021";
            $mail->setFrom('artps.nic@gmail.com', 'Welcome to my website ');
            $mail->addAddress($this->email, '');
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->message;
            $mail->CharSet = 'utf-8';
            return $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
