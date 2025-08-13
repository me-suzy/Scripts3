<?

//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************


// this is a global function that sets up the template for each user page and returns the object
function start_template()
{
	global $_PHPLIB, $session_id;
	
	// start a new template
	$template = new Template($_PHPLIB["basedir"] . "templates/");
	
	// set the session id
	$template->set_var(array(
		"SESSION_ID"	=> 	$session_id,
		"WWW_DIR"		=>	$_PHPLIB["webdir"],
		"PHP_SELF"		=>	$PHP_SELF
		));
	
	return $template;
}

// ENCRYPTION FUNCTIONS
// encrypt data (TripleDES)
function enc($toenc)
{
    $td = mcrypt_module_open (MCRYPT_TripleDES, "", MCRYPT_MODE_ECB, "");
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size ($td), MCRYPT_RAND);
	$encdata = mcrypt_ecb (MCRYPT_TripleDES,(Keysize), $toenc, MCRYPT_ENCRYPT, $iv);
	$blah=bin2hex($encdata);
    return $blah;
}

// decrypt data (TripleDES)
function dec($todec)
{
    $td = mcrypt_module_open (MCRYPT_TripleDES, "", MCRYPT_MODE_ECB, "");
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size ($td), MCRYPT_RAND);
	$decdata = mcrypt_ecb (MCRYPT_TripleDES,(Keysize), hex2bin($todec), MCRYPT_DECRYPT,$iv);
	return $decdata;
}

// convert hexidecimal string to binary (encryption function)
function hex2bin($data) 
{
	$len = strlen($data);
	return pack("H" . $len, $data);
}

// return the current date ( MM-DD-YYYY )
function today()
{
	return date("m-d-Y");
}

// return the current time 24HR ( HH:MM:SS )
function now()
{
	return date("H:i:s");
}

// format $s to be outputted in JS for the loader files
function jsformat($s)
{
	$s = addslashes($s);
	$s = trim($s);
	
	return $s;
}

// format a textarea field for JS loaders
function textarea_format($s)
{
	$s = addslashes($s);
	
	$s = str_replace("\n","\\n",$s);
	$s = str_replace("\r","",$s);
	$s = trim($s);
	
	return $s;
}

?>