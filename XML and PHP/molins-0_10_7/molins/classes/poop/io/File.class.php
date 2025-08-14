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
 * @version $Id: File.class.php,v 1.6 2005/10/18 14:58:13 slizardo Exp $
 * @package poop.io
 */

class File implements Comparable {

	private $path;

	/**
	 * @param string $path
	 */
	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * @return boolean
	 */
	public function canRead() {
		return is_readable($this->path);
	}

	/**
	 * @return boolean
	 */
	public function canWrite() {
		return is_writable($this->path);
	}

	/**
	 * @param mixed $data
	 */
	public function createNewFile($data)
	{
		$fp=fopen($this->path,"w");
		$result=fputs($fp,$data,strlen($data));
		fclose($fp);
		return $result;
	}

	/**
	 * @param string $prefix
	 * @param string $suffix
	 * @param File $directory
	 */
	public static function createTempFile($prefix, $suffix, File $directory = null) {
	}

	public function delete() {
		return @unlink($this->path);
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
	}

	/**
	 * @return boolean
	 */
	public function exists() {
		return file_exists($this->path);
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath() {
		return realpath($this->path);
	}

	/**
	 * @return string
	 */
	public function getRelativePath() {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return basename($this->path);
	}

	/**
	 * @return int
	 */
	public function lastModified() {
		return filemtime($this->path);
	}

	/**
	 * @return int
	 */
	public function length() {
		return filesize($this->path);
	}

	/**
	 * @return boolean
	 */
	public function mkdir() {
		return mkdir($this->path, 0744);
	}

	/**
	 * @return boolean
	 */
	public function mkdirs() {
		return mkdir($this->path, 0744, true);
	}

	/**
	 * @param File $dest
	 */
	public function renameTo(File $dest) {
	}

	/**
	 * @return boolean
	 */
	public function isDirectory() {
		return is_dir($this->path);
	}

	/**
	 * @return boolean
	 */
	public function isFile() {
		return is_file($this->path);
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function compareTo($object) {
	}

	public function __toString() {
		return sprintf('File [%s]', $this->path);
	}
}

?>
