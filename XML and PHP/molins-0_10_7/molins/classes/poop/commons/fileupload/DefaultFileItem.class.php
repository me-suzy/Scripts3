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
 * @version $Id: DefaultFileItem.class.php,v 1.5 2005/10/19 15:25:30 slizardo Exp $
 * @package poop.commons.fileupload
 */

import('poop.io.File');
import('poop.commons.fileupload.FileItem');

class DefaultFileItem implements FileItem {

	private $name;
	private $fieldName;
	private $value;
	private $contentType;
	private $inMemory;
	private $size;

	private $isFormField;
	
	/**
	 * Determines whether or not a <code>FileItem</code> instance represents
	 * a simple form field.
	 *
	 * @return boolean
	 */
	public function isFormField() {
		return $this->isFormField;
	}

	/**
	 * Specifies whether or not a <code>FileItem</code> instance represents
	 * a simple form field.
	 *
	 * @param boolean $isFormField
	 */
	public function setFormField($isFormField) {
		$this->isFormField = $isFormField;
	}

	/**
	 * Sets the item name.
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the original filename in the client's filesystem.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the field name used to reference this file item.
	 *
	 * @param string $fieldName
	 */
	public function setFieldName($fieldName) {
		$this->fieldName = $fieldName;
	}

	/**
	 * Returns the name of the field in the multipart form corresponding to
	 * this file item.
	 *
	 * @return string
	 */
	public function getFieldName() {
		return $this->fieldName;
	}

	/**
	 * Returns the contents of the file as a String, using the default
	 * character encoding.
	 *
	 * @return string
	 */
	public function getString() {
		return $this->value;
	}

	/**
	 * Sets the item value.
	 *
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * Sets the content type for this file.
	 *
	 * @param string $contentType
	 */
	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}

	/**
	 * Returns the content type passed by the browser or <code>null</code> if
	 * not defined.
	 *
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}

	/**
	 * Provides a hint as to whether or not the file contents will be read
	 * from memory.
	 *
	 * @return boolean
	 */
	public function isInMemory() {
		return $this->inMemory;
	}

	/**
	 * Returns the size of the file.
	 *
	 * @param float $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}
	
	/**
	 * Returns the size of the file.
	 *
	 * @return float
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * A convenience method to write an uploaded item to disk.
	 *
	 * @param File $file
	 */
	public function write(File $file) {
	}

	/**
	 * Deletes the underlying storage for a file item, including deleting any
	 * associated temporary disk file. Although this storage will be deleted
	 */
	public function delete() {
	}

	/**
	 * Returns an {@link poop.io.InputStream InputStream} that can be
	 * used to retrieve the contents of the file.
	 *
	 * @return InputStream
	 */
	public function getInputStream() {
	}

	/**
	 * Returns an {@link poop.io.OutputStream OutputStream} that can
	 * be used for storing the contents of the file.
	 *
	 * @return OutputStream
	 */
	public function getOutputStream() {
	}

	/**
	 * Returns the contents of the file as an array of bytes.
	 *
	 * @return mixed
	 */
	public function get() {
		return $this->value;
	}
}

?>
