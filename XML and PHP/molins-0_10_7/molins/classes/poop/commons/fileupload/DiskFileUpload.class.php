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
 * @version $Id: DiskFileUpload.class.php,v 1.7 2005/10/19 15:25:30 slizardo Exp $
 * @package poop.commons.fileupload
 */

import('poop.http.HttpRequest');
import('poop.commons.fileupload.FileUploadBase');
	
class DiskFileUpload extends FileUploadBase {

	/**
	 * Processes an <a href="http://www.ietf.org/rfc/rfc1867.txt">RFC 1867</a>
	 * compliant <code>multipart/form-data</code> stream. If files are stored
	 * on disk, the path is given by <code>getRepository()</code>.
	 *
	 * @param HttpRequest $request
	 * @param float $sizeThreshold
	 * @param float $sizeMax
	 * @param string $repositoryPath
	 * @return IList
	 */
	public function parseRequest(HttpRequest $request, $sizeThreshold = null, $sizeMax = null, $repositoryPath = null) {
		$this->setSizeThreshold($sizeThreshold);
		$this->setSizeMax($sizeMax);
		$this->setRepositoryPath($repositoryPath);
		
		return parent::parseRequest($request);
	}

	/**
	 * Sets the size threshold beyond which files are written directly to disk.
	 * @param float $sizeThreshold
	 */
	public function setSizeThreshold($int) {
	}

	/**
	 * Sets the location used to temporarily store files that are larger
	 * than the configured size threshold.
	 *
	 * @param string $repositoryPath
	 */
	public function setRepositoryPath($path) {
	}
}
	
?>
