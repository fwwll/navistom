<?php

class Notification {
	
	private static $mailer 					= null;
	private static $sendAdminAsUser			= false;
	
	public static $mailerCharset			= 'UTF-8';
	public static $mailerDefaultFrom		= 'navistom@navistom.com';
	public static $mailerDefaultFromName	= 'NaviStom.com';
	
	public static $newMessageTpl			= 'email-mess-new-complete.tpl';
	public static $newMessageSubject		= 'Новое сообщение c NaviStom.com';
	
	public static $newAdminMessageTpl		= 'email-mess-admin-new-complete.tpl';
	public static $newAdminMessageSubject	= 'Новое сообщение c NaviStom.com';
	
	public static $isBCC					= true;
	public static $BCCEmail					= 'navistom@gmail.com';
	public static $BCCName					= 'NaviStom.com';
	
	public static $defaultInputFileName		= 'notification-attach';
	
	/**
	 * Отправляет уведомление о новом сообщении из сайта на почту пользователя
	 *
	 * @param array $user_from
	 * @param array $user_to
	 * @param string $message
	 * @param array $ad
	 */
	public static function newMessage($user_from, $user_to, $message, $ad, $attachments = null) {
		$mailer = Notification::Mailer();
		
		if ($user_from['contact_email'] != null and Str::get($user_from['contact_email'])->isEmail()) {
			$mailer->SetFrom($user_from['contact_email'], $user_from['name']);
		}
		else {
			$mailer->SetFrom($user_from['email'], $user_from['name']);
		}
		
		$mailer->AddAddress($user_to['email']);
		$mailer->Subject = self::isAdmin() ? self::$newAdminMessageSubject : self::$newMessageSubject;
		
		self::renderHTMLMessage(self::isAdmin() ? self::$newAdminMessageTpl : self::$newMessageTpl, $message, array(
			'user_from'	=> $user_from,
			'user_to'	=> $user_to,
			'ad'		=> $ad,
		));
		
		Notification::addAttachments($attachments);
		
		Notification::send();
	}
	
	/**
	 * Отправка резюме пользователя на почту вакансии
	 *
	 * @param array $user_from
	 * @param array $user_to
	 * @param array $vacancy
	 * @param array $attachment
	 */
	public function sendResumeToVacancy($user_from, $user_to, $message, $vacancy, $attachment) {
		$mailer = Notification::Mailer();
		
		if ($user_from['contact_email'] != null and Str::get($user_from['contact_email'])->isEmail()) {
			$mailer->SetFrom($user_from['contact_email'], $user_from['name']);
		}
		else {
			$mailer->SetFrom($user_from['email'], $user_from['name']);
		}
		
		$mailer->AddAddress($user_to['email']);
		$mailer->Subject = 'Новое резюме с NaviStom на рассмотрение';
		
		self::renderHTMLMessage('email-mess-new-resume-complete.tpl', $message, array(
			'user_from'	=> $user_from,
			'user_to'	=> $user_to,
			'ad'		=> $vacancy,
		));
		
		$mailer->AddStringAttachment($attachment['file'], $attachment['name'], 'base64', 'application/pdf');
		
		Notification::send();
	}
	
	public static function Mailer() {
		Notification::mailerInit();
		
		return self::$mailer;
	}
	
	private static function mailerInit() {
		if (!is_object(self::$mailer)) {
			include_once(LIBS.'phpmailer/class.phpmailer.php');
		
			self::$mailer 		= new PHPMailer(true);
			
			self::$mailer->From 	= self::$mailerDefaultFrom;
			self::$mailer->FromName = self::$mailerDefaultFromName;
			self::$mailer->CharSet	= self::$mailerCharset;
			
			self::$mailer->IsHTML(true);
			
			if(self::$isBCC and self::$BCCEmail != null) {
				self::$mailer->AddBCC(self::$BCCEmail, self::$BCCName);
			}
		}
	}
	
	public static function send() {
		if (is_object(self::$mailer)) {
			if (self::$mailer->Send()) {
				self::sendHandler();
				self::mailerClear();
				
				return true;
			}
			else {
				self::errorHandler();
				
				return false;
			}
		}
	}
	
	public function addAttachments($attach = null) {
		if (is_array($attach)) {
			self::$mailer->AddAttachment($attach['file'], $attach['name']);
		}
		else {
			if ($_FILES[self::$defaultInputFileName]['name'] != '') {
				self::$mailer->AddAttachment($_FILES[self::$defaultInputFileName]['tmp_name'], $_FILES[self::$defaultInputFileName]['name']);
			}
		}
	}
	
	private static function mailerClear() {
		if (self::$mailer) {
			self::$mailer->ClearAddresses();
			self::$mailer->ClearAttachments();
			self::$mailer->IsHTML(false);
		}
	}
	
	private static function date() {
		return date('Y-m-d H:i:s');
	}
	
	private function isAdmin() {
		return (self::$sendAdminAsUser or !User::isAdmin()) ? false : true;
	}
	
	private static function renderHTMLMessage($tpl, $message, $data) {
		if (is_object(self::$mailer)) {
			if ($tpl != null and is_array($data)) {
				$message = Registry::get('twig')->render($tpl, array_merge(array(
					'date'		=> self::date(),
					'message'	=> nl2br($message),
				), $data));
			}
			
			self::$mailer->MsgHTML($message);
		}
		else {
			return false;
		}
	}
	
	private function sendHandler() {
		
	}
	
	private function errorHandler() {
		
	}
}