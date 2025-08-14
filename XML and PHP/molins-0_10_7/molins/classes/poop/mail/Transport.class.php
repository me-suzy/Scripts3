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
 * @version $Id: Transport.class.php,v 1.7 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.mail
 */

import('poop.mail.MimeMessage');
import('poop.mail.MailException');

class Transport {
	const MAIL_SEPARATOR = ', ';

	/**
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 */
	public function connect($host, $user, $password) {
	}

	/**
	 * @param string $message
	 * @param array $addresses
	 */
	public static function sendMessage($message, $recipients) {
	}

	/**
	 * @param MimeMessage $message
	 */
	public static function send(MimeMessage $message) {

		$to = implode(self::MAIL_SEPARATOR, $message->getTo());

		$buffer = new StringBuffer();
		$buffer->append('From: ')->append($message->getFrom()->__toString())->append(NL);
		$buffer->append('Reply-To: ')->append($message->getReplyTo()->__toString())->append(NL);
		$buffer->append('X-Mailer: Molins PHP/')->append(phpversion())->append(NL);
		$buffer->append('MIME-Version: 1.0')->append(NL);
		$buffer->append('Content-type: text/html; charset=iso-8859-1')->append(NL);
		if(count($message->getCC()) > 0) {
			$cc = implode(self::MAIL_SEPARATOR, $message->getCC());
			
			$buffer->append('Cc: ')->append($cc)->append(NL);
		}
		if(count($message->getBCC()) > 0) {
			$bcc = implode(self::MAIL_SEPARATOR, $message->getBCC());
			
			$buffer->append('Bcc: ')->append($bcc)->append(NL);
		}

		if(@mail($to, $message->getSubject(), $message->getBody(), $buffer->__toString()) == false) {
			throw new MailException(_('el correo no pudo ser enviado'));
		}	
	}

	public function close() {
	}
}
	
?>
