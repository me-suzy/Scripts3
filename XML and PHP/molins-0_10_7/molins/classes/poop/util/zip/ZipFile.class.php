<?ph

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: ZipFile.class.php,v 1.5 2005/10/12 21:01:05 slizardo Exp $
 * @package poop.util.zip
 */

import('poop.io.File');

class ZipFile extends File  {

	private $zipResource;

	public $zipFileinfo;

	private $zipFiles;

	/**
	 * @param string $path
	 */
	public function __construct($path) {
		parent::__construct($path);
		$this->zipFileinfo=array();
		$this->zipFiles=array();
	}

	public function unzip() {

	}

	/**
	 * @return array
	 */
	public function getZipFiles() {
		return $this->zipFiles;
	}

	/**
	 * @param string $name
	 */
	public function extractFileToString($name) {
		ini_set("memory_limit","128M");
		if(count($this->zipFiles)==0) {
			$this->listFiles();
		}
		gzrewind($this->zipResource);
		foreach ($this->zipFiles as $key=>$file) {
			if($file['filename']==$name) {
				if($this->zipFiles[$key+1]) {
					$offset = abs($this->zipFiles[$key+1]['offset']-$file['compressed_size']);
				} else {
					$offset=abs($this->zipFileinfo['offset']-$file['compressed_size']);
				}
				gzseek($this->zipResource,$offset);
				$a = fread($this->zipResource,$file['compressed_size']);
				if($file['compression'] != 0) {
					return gzinflate($a,$file['size']);
				} else {
					return $a;
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function listFiles() {
		$this->openZip();
		$result=array();
		$cont=0;
		$tmp_size = filesize($this->getAbsolutePath());
		gzseek($this->zipResource, $tmp_size-18);
		$tmp_binary_data = gzread($this->zipResource, 18);
		$tmpData = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $tmp_binary_data);
		$this->zipFileinfo['entries'] = $tmpData['entries'];
		$this->zipFileinfo['disk_entries'] = $tmpData['disk_entries'];
		$this->zipFileinfo['offset'] = $tmpData['offset'];
		$this->zipFileinfo['size'] = $tmpData['size'];
		$this->zipFileinfo['disk'] = $tmpData['disk'];
		$this->zipFileinfo['disk_start'] = $tmpData['disk_start'];
		gzrewind($this->zipResource);
		if (gzseek($this->zipResource, $this->zipFileinfo['offset']))
		{
			return 2;
		}
		for ($i=0; $i<$this->zipFileinfo['entries']; $i++)
		{
			$this->zipFiles[$i]=$this->readFileHeader();
		}
	}

	/**
	 * @param string $openFormat
	 */
	private function openZip($openFormat="r") {
		$this->zipResource=gzopen($this->getAbsolutePath(),"r");
	}

	private function closeZip() {
		return gzclose($this->zipResource);
	}

	public function readFileHeader() {
		// ----- Read the 4 bytes signature
		$tmp_binary_data = @gzread($this->zipResource, 4);
		$tmpData = unpack('Vid', $tmp_binary_data);
		// ----- Check signature
		if ($tmpData['id'] != 0x02014b50)
		{
			return 0;
		}

		$tmp_binary_data = gzread($this->zipResource, 42);
		// ----- Look for invalid block size
		if (strlen($tmp_binary_data) != 42)
		{
			return 0;
		}
		// ----- Extract the values
		$tmpData = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $tmp_binary_data);

		// ----- Get filename
		if ($tmpData['filename_len'] != 0)
		$tmpData['filename'] = gzread($this->zipResource, $tmpData['filename_len']);
		else
		$tmpData['filename'] = '';

		// ----- Get extra
		if ($tmpData['extra_len'] != 0)
		$tmpData['extra'] = gzread($this->zipResource, $tmpData['extra_len']);
		else
		$tmpData['extra'] = '';

		// ----- Get comment
		if ($tmpData['comment_len'] != 0)
		$tmpData['comment'] = gzread($this->zipResource, $tmpData['comment_len']);
		else
		$tmpData['comment'] = '';
		// ----- Recuperate date in UNIX format
		if ($tmpData['mdate'] && $tmpData['mtime'])
		{
			$tmp_hour = ($tmpData['mtime'] & 0xF800) >> 11;
			$tmp_minute = ($tmpData['mtime'] & 0x07E0) >> 5;
			$tmp_seconde = ($tmpData['mtime'] & 0x001F)*2;
			$tmp_year = (($tmpData['mdate'] & 0xFE00) >> 9) + 1980;
			$tmp_month = ($tmpData['mdate'] & 0x01E0) >> 5;
			$tmp_day = $tmpData['mdate'] & 0x001F;
			$tmpData['mtime'] = mktime($tmp_hour, $tmp_minute, $tmp_seconde, $tmp_month, $tmp_day, $tmp_year);
		}else{
			$tmpData['mtime'] = time();
		}
		$tmpData['status'] = 'ok';
		// ----- Look if it is a directory
		if (substr($tmpData['filename'], -1) == '/')
		{
			$tmpData['external'] = 0x00000010;
		}
		return $tmpData;
	}
}

?>
