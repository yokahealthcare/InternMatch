<?php

use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    private $mail;
    private $email;

    public function __construct($input_email)
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
        $this->mail->Username = 'erwinwingyonata@gmail.com';
        $this->mail->Password = 'qleltgvmdoaweefv';

        // Sender and recipient settings
        $this->mail->setFrom('erwinwingyonata@gmail.com', 'Erwin Yonata');

        $this->email = $input_email;
    }
}