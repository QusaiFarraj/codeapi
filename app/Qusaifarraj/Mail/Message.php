<?php


namespace Qusaifarraj\Mail;

/**
* 
*/
class Message{
    
    protected $mailer;

    public function __construct($mailer){
        $this->mailer = $mailer;
    }

    public function to($address, $name){
        $this->mailer->addAddress($address, $name);
    }

    public function subject($subject){
        $this->mailer->Subject = $subject;
    }

    public function body($body){
        $this->mailer->Body = $body;
        $this->mailer->AltBody = "Alternative body: " . $this->mailer->Subject;
    }
}