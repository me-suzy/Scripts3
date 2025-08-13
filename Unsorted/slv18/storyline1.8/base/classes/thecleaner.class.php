<?
class TheCleaner{

	function thecleaner() {			
	}

	function stripper($var) {
		$var = preg_replace("!</?html>|<head>.*?</head>|</?body.*?>!is", "", $var);
		return $var;
	}

	function preva($var) {			// showing anything but the chapters
		$var = urldecode($var);
		$var = strip_tags($var);
		$var = trim($var);
		$var = stripslashes($var);
		return $var;
	}	

	function inputv($var) {			// for the input fields
		$var = strip_tags($var);
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var, ENT_QUOTES);
		return $var;
	}

	function textv($var) {			// for the textarea fields
		$var = strip_tags($var,"<i><u><b><center>");
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var, ENT_QUOTES);
		return $var;
	}

	function prevb($var) {			// showing the chapters
		$var = strip_tags($var,"<i><u><b><center>");
		$var = trim($var);
		$var = stripslashes($var);
		$var = nl2br($var);
		return $var;
	}		

	function trans($var) {			// hidden field transfer
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var, ENT_QUOTES);
		return $var;
	}

	function subm($var) {			// into the database
		$var = str_replace(array("&gt;", "&lt;", "&quot;", "&amp;", "&#039;"), array(">", "<", "\"", "&","\'"), $var);
		return $var;
	}

}
?>