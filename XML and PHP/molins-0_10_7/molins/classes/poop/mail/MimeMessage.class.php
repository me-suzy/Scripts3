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
 * @version $Id: MimeMessage.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mail
 */

import('poop.mail.InternetAddress');

class MimeMessage {
	private $to;
	private $from;	
	private $replyTo;
	private $cc;
	private $bcc;

	private $subject;
	private $body;
	
	public function __construct() {
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();		
	}

	/**
	 * @param InternetAddress $to
	 */
	public function addTo(InternetAddress $to) {
		array_push($this->to, $to->__toString());
	}

	/**
	 * @return InternetAddress
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @param InternetAddress $from
	 */
	public function setFrom(InternetAddress $from) {
		$this->from = $from;
		if(is_null($this->replyTo)) {
			$this->replyTo = $this->from;
		}
	}

	/**
	 * @return InternetAddress
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @param InternetAddress $replyTo
	 */
	public function setReplyTo(InternetAddress $replyTo) {
		$this->replyTo = $replyTo;
	}

	/**
	 * @return InternetAddress
	 */
	public function getReplyTo() {
		return $this->replyTo;
	}

	/**
	 * @param InternetAddress $cc
	 */
	public function addCC(InternetAddress $cc) {
		array_push($this->cc, $cc->__toString());
	}

	/**
	 * @return InternetAddress
	 */
	public function getCC() {
		return $this->cc;
	}

	/**
	 * @param InternetAddress $bcc
	 */
	public function addBCC(InternetAddress $bcc) {
		array_push($this->bcc, $bcc->__toString());
	}

	/**
	 * @return InternetAddress
	 */
	public function getBCC() {
		return $this->bcc;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @param string $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}
}

?>
