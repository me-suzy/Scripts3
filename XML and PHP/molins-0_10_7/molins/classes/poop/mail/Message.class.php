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
 * @version $Id: Message.class.php,v 1.4 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mail
 */

import('poop.mail.Folder');
 
abstract class Message {

	protected $folder;
	protected $msgnum;

	public function __construct() {
	}

	/**
	 * @param array $addresses
	 */
	abstract public function addFrom($addresses);

	/**
	 * @param RecipientType $type
	 * @param InternetAddress $address
	 */
	public function addRecipient(RecipientType $type, $address) {
	}

	/**
	 * @param RecipientType $type
	 * @param array $addresses
	 */
	abstract function addRecipients(RecipientType $type, $addresses);

	/**
	 * @return array
	 */
	public function getAllRecipients() {
	}

	/**
	 * @return mixed
	 */
	abstract public function getFlags() {
	}

	/**
	 * @return Folder
	 */
	public function getFolder() {
	}

	/**
	 * @return InternetAddress
	 */
	abstract public function getFrom();

	/**
	 * @return int
	 */
	public function getMessageNumber() {
	}

	/**
	 * @return string
	 */
	abstract public function getReceivedDate();
	
	/**
	 * @param RecipientType $type
	 * @return array
	 */
	abstract public function getRecipients(RecipientType $type);

	/**
	 * @return InternetAddress
	 */
	public function getReplyTo() {
	}

	/**
	 * @return string
	 */
	abstract public function getSentDate();

	/**
	 * @return string
	 */
	abstract public function getSubject();

	/**
	 * @return boolean
	 */
	public function isExpunged() {
	}

	/**
	 * @param Flag $flag
	 */
	public function isSet(Flag $flag) {
	}

	/**
	 * @param mixed $replyToAll
	 */
	abstract public function reply($replyToAll);

	public function setExpunged($expunged) {
	}

	/**
	 * @param Flag $flag
	 * @param mixed $set
	 */
	public function setFlag(Flag $flag, $set) {
	}

	/**
	 * @param InternetAddress $from
	 */
	abstract public function setFrom($from);

	/**
	 * @param int $msgnum
	 */
	protected function setMessageNumber($msgnum) {
	}

	/**
	 * @param string $str
	 */
	abstract public function setSubject($str);
}

?>
