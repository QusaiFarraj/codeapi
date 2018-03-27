<?php


namespace Qusaifarraj\Mail;

/**
* 
*/
class Mailer {

    protected $view;
    protected $mailer;


    public function __construct($view, $mailer){

        $this->view = $view;
        $this->mailer = $mailer;
    }

    public function send($template, $data, $callback){

       $message = new Message($this->mailer);

        $this->view->appendData($data);
        $message->body($this->view->render($template));
        
        call_user_func($callback, $message);

        // now send email
        if (!$this->mailer->send()){
            echo "Mailer Error: " . $this->mailer->ErrorInfo;
        } else {
            echo "email sent";
        }
    }
}