<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: News.class.php,v 1.4 2005/09/28 16:28:21 slizardo Exp $
 * @package sample
 */
	
import('poop.dbobjects.DbObject');

class News extends DbObject {

	public function setCategoryId($categoryId) {
		$this->setInt('category_id', $categoryId);
	}
	
	public function getCategoryId() {
		return $this->getInt('category_id');
	}

	public function setTitle($title) {
		$this->setString('title', $title);
	}

	public function getTitle() {
		return $this->getString('title');
	}

	public function setCreationDate($creationDate) {
		$this->setDate('creation_date', $creationDate);
	}

	public function getCreationDate() {
		return $this->getDate('creation_date');
	}

	public function setAuthor($author) {
		$this->setString('author', $author);
	}

	public function getAuthor() {
		return $this->getString('author');
	}

	public function setDetails($details) {
		$this->setString('details', $details);
	}

	public function getDetails() {
		return $this->getString('details');
	}
}
	
?>
