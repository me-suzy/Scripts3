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
 * @version $Id: FileUploadBase.class.php,v 1.7 2005/10/19 15:25:30 slizardo Exp $
 * @package poop.commons.fileupload
 */

import('poop.util.IList');
import('poop.util.ArrayList');
import('poop.commons.fileupload.DefaultFileItem');
 
abstract class FileUploadBase {

	const CONTENT_TYPE = 'Content-type';
	const MULTIPART = 'multipart/';

	private $sizeMax = -1;
	
	/**
	 * Utility method that determines whether the request contains multipart
	 * content.
	 *
	 * @param HttpRequest $request
	 * @return boolean
 	 */	 
	public static function isMultipartContent(HttpRequest $request) {
		$contentType = $request->getHeader(self::CONTENT_TYPE);
		if($contentType == null) {
			return false;
		}
		if($contentType->startsWith(self::MULTIPART)) {
			return true;
		}

		return false;
	}

	/**
	 * Processes an <a href="http://www.ietf.org/rfc/rfc1867.txt">RFC 1867</a>
	 * compliant <code>multipart/form-data</code> stream. If files are stored
	 * on disk, the path is given by <code>getRepository()</code>.
	 *	
	 * @param HttpRequest $request
	 * @return IList
 	 */	 
	public function parseRequest(HttpRequest $request) {
		if($request == null) {
			throw new NullPointerException('request parameter');
		}

		$fileItems = new ArrayList();
		$contentType = $request->getHeader(self::CONTENT_TYPE);

		if($contentType == null || !$contentType->startsWith(self::MULTIPART)) {
			throw new InvalidContentTypeException();
		}

		foreach($_POST as $key => $value) {
			$fileItem = new DefaultFileItem();
			$fileItem->setFormField(true);
			$fileItem->setFieldName($key);
			$fileItem->setValue($value);

			$fileItems->add($fileItem);
		}

		foreach($_FILES as $key => $file) {
			$fileItem = new DefaultFileItem();
			$fileItem->setFormField(false);
			$fileItem->setFieldName($key);			
			$fileItem->setContentType($file['type']);
			$fileItem->setName($file['name']);
			$fileItem->setSize($file['size']);
			$fileItem->setValue(file_get_contents($file['tmp_name']));

			$fileItems->add($fileItem);
		}

		return $fileItems;
	}

	/**
	 * Sets the maximum allowed upload size. If negative, there is no maximum.
	 *
	 * @param float $sizeMax
	 */
	public function setSizeMax($sizeMax) {
		$this->sizeMax = $sizeMax;
	}	

	/**
	 * Returns the maximum allowed upload size.
	 *
	 * @return float
	 */
	public function getSizeMax() {
		return $this->sizeMax;
	}
}
	
?>
