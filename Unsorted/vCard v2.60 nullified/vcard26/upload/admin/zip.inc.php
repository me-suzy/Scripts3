<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
/* Zip file creation class 
 * BASED ON "zip file creation class" article
 *          http://www.zend.com/zend/spotlight/creating-zip-files2.php
 * official ZIP file format: http://www.pkware.com/support/appnote.html
 */
define('IN_VCARD', true);
define('ZIPFILE',true);
define('ZIPFILE_VER', 1.0);
define('ZIPFILE_BUILD', '03.07.2002');
class zipfile  
{
	# Array to store compressed data
	# private string[]
	var $datasec      = array();
	var $ctrl_dir     = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset   = 0; 

	/**
	* @function add_file
	* @abstract Adds "file content" to archive
	* @access public
	* @param data  string - file contents
	* @param name  string - name of the file in the archive (may contains the path)
	* @result add file content to file
	*/
	function add_file($data, $name) {
	
	        $name = str_replace('\\', '/', $name);
	        $fr   = "\x50\x4b\x03\x04"; 
	        $fr   .= "\x14\x00";            // ver needed to extract 
	        $fr   .= "\x00\x00";            // gen purpose bit flag 
	        $fr   .= "\x08\x00";            // compression method 
	        $fr   .= "\x00\x00\x00\x00";    // last mod time and date 
	        // "local file header" segment
	        $unc_len = strlen($data);
	        $crc     = crc32($data);
	        $zdata   = gzcompress($data);
	        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
	        $c_len   = strlen($zdata);
	        $fr      .= pack('V', $crc);             // crc32
	        $fr      .= pack('V', $c_len);           // compressed filesize
	        $fr      .= pack('V', $unc_len);         // uncompressed filesize
	        $fr      .= pack('v', strlen($name));    // length of filename
	        $fr      .= pack('v', 0);                // extra field length
	        $fr      .= $name;
	
	        // "file data" segment 
	        $fr .= $zdata;
	
	        // "data descriptor" segment (optional but necessary if archive is not
	        // served as file)
		
		/* REMOVE to avoid Anti-Mac error
	        //$fr .= pack('V', $crc);                 // crc32
	        //$fr .= pack('V', $c_len);               // compressed filesize
	        //$fr .= pack('V', $unc_len);             // uncompressed filesize
		*/
		
	        // add this entry to array
	        $this -> datasec[] = $fr;
	        $new_offset        = strlen(implode('', $this->datasec));
	
	        // now add to central directory record
	        $cdrec = "\x50\x4b\x01\x02";
	        $cdrec .= "\x00\x00";                // version made by
	        $cdrec .= "\x14\x00";                // version needed to extract
	        $cdrec .= "\x00\x00";                // gen purpose bit flag
	        $cdrec .= "\x08\x00";                // compression method
	        $cdrec .= "\x00\x00\x00\x00";        // last mod time & date
	        $cdrec .= pack('V', $crc);           // crc32
	        $cdrec .= pack('V', $c_len);         // compressed filesize
	        $cdrec .= pack('V', $unc_len);       // uncompressed filesize
	        $cdrec .= pack('v', strlen($name) ); // length of filename
	        $cdrec .= pack('v', 0 );             // extra field length
	        $cdrec .= pack('v', 0 );             // file comment length
	        $cdrec .= pack('v', 0 );             // disk number start
	        $cdrec .= pack('v', 0 );             // internal file attributes
	        $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set
	
	        $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
	        $this -> old_offset = $new_offset;
	
	        $cdrec .= $name;
	
	        // optional extra field, file comment goes here
	        // save to central directory
	        $this -> ctrl_dir[] = $cdrec;
	}
	
	/**
	* @function file
	* @abstract Dumps out file
	* @access public
	* @result gzipped file
	*/
	function file() {
	
		$data    = implode('', $this -> datasec);
		$ctrldir = implode('', $this -> ctrl_dir);
		return
		$data .
		$ctrldir .
		$this -> eof_ctrl_dir .
		pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
		pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
		pack('V', strlen($ctrldir)) .           // size of central dir
		pack('V', strlen($data)) .              // offset to start of central dir
        	"\x00\x00";                             // .zip file comment length
	}

} // end of the 'zipfile' class
?>
