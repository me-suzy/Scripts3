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
 * @version $Id: FileItem.class.php,v 1.6 2005/10/19 15:25:30 slizardo Exp $
 * @package poop.commons.fileupload
 */

import('poop.io.File');

interface FileItem {

	/**
	 * Determines whether or not a <code>FileItem</code> instance represents
	 * a simple form field.
	 *
	 * @return boolean
	 */
	public function isFormField();

	/**
	 * Specifies whether or not a <code>FileItem</code> instance represents
	 * a simple form field.
	 *
	 * @param boolean $state
	 */
	public function setFormField($state);

	/**
	 * Returns the original filename in the client's filesystem, as provided by
	 * the browser (or other client software). In most cases, this will be the
	 *
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the name of the field in the multipart form corresponding to
	 * this file item.
	 *
	 * @return string
	 */
	public function getFieldName();

	/**
	 * Sets the field name used to reference this file item.
	 *
	 * @param string $name
	 */
	public function setFieldName($name);

	/**
	 * Returns the contents of the file item as a String, using the default
	 * character encoding.	
	 *
	 * @return string
	 */
	public function getString();

	/**
	 * Returns the content type passed by the browser or <code>null</code> if
	 * not defined.
	 *
	 * @return string
	 */
	public function getContentType();

	/**
	 * Provides a hint as to whether or not the file contents will be read
	 * from memory.
	 *
	 * @return boolean
	 */
	public function isInMemory();

	/**
	 * Returns the size of the file item.
	 *
	 * @return float
	 */
	public function getSize();

	/**
	 * A convenience method to write an uploaded item to disk.
	 *
	 * @param File $file
	 */
	public function write(File $file);

	/**
	 * Deletes the underlying storage for a file item, including deleting any
	 * associated temporary disk file. Although this storage will be deleted
	 */
	public function delete();

	/**
	 * Returns an {@link java.io.InputStream InputStream} that can be
	 * used to retrieve the contents of the file.
	 *
	 * @return InputStream
	 */
	public function getInputStream();

	/**
	 * Returns an {@link java.io.OutputStream OutputStream} that can
	 * be used for storing the contents of the file.
	 *
	 * @return OutputStream
	 */
	public function getOutputStream();

	/**
	 * Returns the contents of the file item as an array of bytes.
	 *
	 * @return mixed
	 */
	public function get();
}

?>
