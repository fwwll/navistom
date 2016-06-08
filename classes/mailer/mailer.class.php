<?php

namespace Mailer;

use \PHPMailer, \Registry, \DB;


class Mailer {

    public $subject = 'Новые материалы на сайте NaviStom.com';

    protected $fromName = 'NaviStom.com';
    protected $fromEmail = 'navistom@navistom.com';

    protected $charset = 'UTF-8';

    private $message = null;
    private $mailer = null;

    private $itemsCount = 0;

    protected $log = false;


    public function __construct($subject = null, $debug = false, $log = false) {
        $this->mailer = new PHPMailer();
        $this->mailer->setLanguage('ru');

        $this->debug = $debug;
        $this->log = $log;

        if (isset($subject) and is_string($subject)) {
            $this->subject = $subject;
        }
    }

    public function buildMessage($templateUrl, $items ,$flag =1,$text='',$message_type='',$user_name='') {
        if (empty($templateUrl) or !is_array($items)) {
            return false;
        }
        if($flag){
			$this->itemsCount = count($items);
			$this->message = Registry::get('twig')->render($templateUrl, array(
				'items'	=> $items,
				'date'	=> date('Y-m-d')
			));
        }else{
			$this->message = Registry::get('twig')->render($templateUrl, array(
				'items'		  => $items,
				'date'		  => date('Y-m-d'),
				'text'		  =>$text,
				'message_type'=>$message_type,
				'user_name'   =>$user_name
			));
		} 
        return $this;
    }

    public function send( $email = null, $name = '', $userId = 0 ) {
        $this->mailer->setFrom($this->fromEmail, $this->fromName);
        $this->mailer->Subject = $this->subject;
        $this->mailer->CharSet = $this->charset;

        $this->mailer->addAddress($email, $name);

        $this->mailer->msgHTML($this->message);

        $this->mailer->send();

        if ($this->log) {
            $status = ($this->mailer->isError() ? 'error' : 'success');
            Log::set( $email, $userId, $status, $this->itemsCount, $this->mailer->ErrorInfo );
        }

        $this->mailer->ClearAllRecipients();

        return $this->mailer->isError();
    }

    public function logged($status = false) {
        $this->log = $status;
        return $this;
    }

    public function useSMTP() {
        $this->mailer->isSMTP();
        $this->mailer->Host = Registry::get('config')->SMTPHost;
        $this->mailer->Port = Registry::get('config')->SMTPPort;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = Registry::get('config')->subscribeEmail;
        $this->mailer->Password = Registry::get('config')->SMTPPassword;
    }
}

class Log {

    const table = 'subscribe_logs';

    public static function set( $email, $userId = 0, $status = 'success', $itemsCount = 0, $message = '' ) {
        if (empty($email)) {
            return false;
        }

        \Debug::log($email);

        DB::insert(self::table, array(
            'user_id'       => $userId,
            'email'         => $email,
            'status'        => $status,
            'items_count'   => $itemsCount,
            'message'       => $message
        ));

        return DB::lastInsertId();
    }

    public static function clear() {
        DB::truncate(self::table);
        return false;
    }
}