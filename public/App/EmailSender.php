<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    public $mail;
    private $email = "erwinwingyonata@gmail.com";

    public function __construct()
    {
        // define the private variable
        $this->mail = new PHPMailer(true);
        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = 465;
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Your Gmail credentials
        $this->mail->Username = $this->email;
        $this->mail->Password = 'qleltgvmdoaweefv';

        // Sender and recipient settings
        $this->mail->setFrom($this->email, 'Erwin Yonata');
    }
}