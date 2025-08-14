<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: MailAppender.class.php,v 1.7 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */
	
import('poop.logging.Appender');
import('poop.mail.MimeMessage');
import('poop.mail.Transport');
	
class MailAppender extends Appender {

	private $mail_from;
	private $mail_to;

	public function init() {
		$this->mail_from = $this->getProperty('mail.from');
		$this->mail_to = $this->getProperty('mail.to');		
	}

	/**	
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function log($level, $message, $exception = null) {	
		$from = new InternetAddress($this->mail_from);
		$to = new InternetAddress($this->mail_to);
		
		$mimeMessage = new MimeMessage();
		$mimeMessage->setFrom($from);
		$mimeMessage->addTo($to);
		$mimeMessage->setSubject('Molins Log');
		$mimeMessage->setBody($message);
		
		Transport::send($mimeMessage);
	}
}
	
?>
