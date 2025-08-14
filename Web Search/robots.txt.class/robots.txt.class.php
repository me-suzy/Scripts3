<?php
/**
 * @package None
 * @version 1.0
 * @copyright (C) 2005 by Toon Goedhart
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @tables none
 * @author Toon Goedhart <toon.goedhart@gmail.com>
 * @notes
 * Support for proxy servers is given through the Snoopy package.
 * If you need it, download it from http://snoopy.sourceforge.net/.
 * 
 * Some sample code, standard way of getting the robots.txt file:
 * --------------------------------------------------------------
 * <?php
 * require_once( "robots.txt.class.php" );
 * 
 * $r = new RobotsTXT();
 * $r->setRobotsURL( "http://www.microsoft.com/robots.txt" );
 * $robots = $r->execute( "googlebot" );
 * print_r( $robots );
 * ?>
 * 
 * or:
 * ---
 * <?php
 * require_once( "robots.txt.class.php" );
 * 
 * $r = new RobotsTXT();
 * $r->setRobotsContents( "User-agent: *\nDisallow: /secret\n\nUser-agent: googlebot\nDisallow: /cgi-bin/" );
 * $robots = $r->execute( "googlebot" );
 * print_r( $robots );
 * ?>
 * 
 * Get robots.txt via Snoopy:
 * --------------------------
 * <?php
 * require_once( "robots.txt.class.php" );
 * 
 * $r = new RobotsTXT();
 * $r->setSnoopy( "snoopy.class.php", "10.0.0.5", "8080" );
 * $r->setRobotsURL( "http://www.microsoft.com/robots.txt" );
 * $robots = $r->execute( "googlebot" );
 * print_r( $robots );
 * ?>
 *
 **/


/**
 * Class to parse and check the robots.txt file on a website.
 * 
 * There are two ways of supplying the robots.txt file:
 * 1. Give the class the URL of the file
 * 2. Supply the contents of the file
 * 
 * The class has a preference for the first method, that is:
 * if you supply both the URL and the contents the class
 * will fetch the file specified in the URL and parse it's
 * contents.
 **/
class RobotsTXT {
	
	// The content of the robots.txt file to be parsed
	var $RobotsContents;
	
	// The URL to the robots.txt file
	var $RobotsURL;
	
	// Array holding the complete parsed contents of
	// the robots.txt file. The structure is:
	// array(
	//   [*] => array(
	//            [files] => array(
	//                         "secret.txt"
	//                         "passwords.txt"
	//                       )
	//            [dirs] =>  array (
	//                         "/cgi-bin/"
	//                         "/images/"
	//                       )
	//          )
	//   [googlebot] => array(
	//            [files] => array(
	//                         "secret.txt"
	//                         "passwords.txt"
	//                       )
	//            [dirs] =>  array (
	//                         "/cgi-bin/"
	//                         "/images/"
	//                       )
	//          )
	// )
	var $RobotsComplete;
	
	// Snoopy class
	var $Snoopy;
	var $useSnoopy;
	
	
	/**
	 * Initializes the class
	 * 
	 * @access public
	 * @return void
	 **/
	function RobotsTXT() {
		$this->RobotsContents = "";
		$this->RobotsURL = "";
		$this->RobotsComplete = array();
		$this->useSnoopy = FALSE;
	}
	
	
	/**
	 * Sets up Snoopy for use via a proxy.
	 * Even if you don't use a proxy you can still
	 * use Snoopy. Just set the $port and $host to "".
	 * 
	 * @access public
	 * @param string $snoopyPath The complete path to the Snoopy script
	 * @param string $host The proxy host (e.g. 10.0.0.5)
	 * @param string $port The proxy port (e.g. 8080)
	 * @return void
	 **/
	function setSnoopy( $snoopyPath, $host="", $port="" ) {
		require_once( $snoopyPath );
		
		$this->Snoopy = new Snoopy();
		$this->Snoopy->proxy_host = $host;
		$this->Snoopy->proxy_port = $port;
		
		$this->useSnoopy = TRUE;
	}
	
	
	/**
	 * Sets the proxy host and port.
	 * 
	 * @access public
	 * @param string $host The proxy host (e.g. 10.0.0.5)
	 * @param string $port The proxy port (e.g. 8080)
	 * @return void
	 **/
	function setProxy( $host="", $port="" ) {
		if ( $this->useSnoopy ) {
			$this->Snoopy->proxy_host = $host;
			$this->Snoopy->proxy_port = $port;
		}
	}
	
	
	/**
	 * Sets the URL from which the robots.txt file
	 * is to be read and parsed.
	 * 
	 * This should be the complete URL including the
	 * protocol and filename.
	 * 
	 * e.g. http://www.microsoft.com/robots.txt
	 * 
	 * @access public
	 * @param string $theURL Complete URL to the robots.txt file
	 * @return boolean TRUE on success, FALSE if URL is invalid
	 **/
	function setRobotsURL( $theURL ) {
		if ( $this->isURL( $theURL )) {
			$this->RobotsURL = $theURL;
			return TRUE;
			
		} else {
			return FALSE;
		}
	}
	
	
	/**
	 * Sets the contents of the $RobotsContents var.
	 * 
	 * @access public
	 * @param string $Contents Contents of the robots.txt file
	 * @return TRUE
	 **/
	function setRobotsContents( $Contents ) {
		$this->RobotsContents = $Contents;
		
		// Reset $RobotsURL to force parsing of the supplied contents
		$this->RobotsURL = "";
		
		return TRUE;
	}
	
	
	/**
	 * Tests if a string is a URL.
	 * Copied from http://www.truerwords.net/articles/ut/urlactivation.html
	 * 
	 * @access public
	 * @param string $URL The string that is to be tested
	 * @return boolean TRUE if the string is a URL
	 **/
	function isURL( $URL ) {
		return eregi( "(^|[ \t\r\n])((ftp|http|https|gopher|mailto|news|nntp|telnet|wais|file|prospero|aim|webcal):(([A-Za-z0-9$_.+!*(),;/?:@&~=-])|%[A-Fa-f0-9]{2})+(#([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;/?:@&~=%-]*))?)" , $URL );
	}
	
	
	/**
	 * Will execute the retrieval of the file (if needed) and
	 * parse the content. If $AgentName is specified and found
	 * then it will return an array with the files and directories
	 * that are disallowed for that useragent including useragent "*".
	 * If $AgentName is not specified it will return the files and
	 * directories for useragent "*".
	 * 
	 * The contents of the robots.txt file is stored in the variable
	 * $RobotsComplete. The output is structured in the same way as
	 * the $RobotsComplete variable with only the 'files' and 'dirs'
	 * as keys.
	 * 
	 * @access public
	 * @param string $AgentName The name of the agent to check for
	 * @return array Array with disallowed files and directories or FALSE on failure
	 **/
	function execute( $AgentName="" ) {
		$agent = strtolower( $AgentName );
		
		if ( $this->RobotsURL != "" ) {
			$retVal = $this->getFileFromURL();
			if ( !$retVal ) {
				return FALSE;
			}
		}
		
		$retVal = $this->parseRobotsFile();
		if ( !$retVal ) {
			return FALSE;
		}
		
		$returnArray = array();
		
		/**
		 * The specification of robots.txt does not define adding
		 * two or more sets of exclusions together. Instead is uses
		 * a "fallback" mechanism where a spider has to look for
		 * it's own ID. If that is not present it falls back to
		 * User-agent "*".
		 **/
		if ( isset( $this->RobotsComplete[$agent] )) {
			$Disallowed = $this->RobotsComplete[$agent];
			
		} else if ( isset( $this->RobotsComplete["*"] )) {
			$Disallowed = $this->RobotsComplete["*"];
			
		} else {
			return $returnArray;
		}
		
		if ( isset( $Disallowed["files"] )) {
			while ( list( $key, $value ) = each( $Disallowed["files"] )) {
				$returnArray["files"][] = $value;
			}
		}
		
		if ( isset( $Disallowed["dirs"] )) {
			while ( list( $key, $value ) = each( $Disallowed["dirs"] )) {
				$returnArray["dirs"][] = $value;
			}
		}

		return $returnArray;
	}
	
	
	/**
	 * Parses the contents of $RobotsContens and fills the
	 * $RobotsComplete variable.
	 * 
	 * @access public
	 * @return FALSE on error.
	 **/
	function parseRobotsFile() {
		$robot = str_replace( "\r", "", $this->RobotsContents );
		$robot = explode( "\n", $robot );
		
		$userAgent = "";
		$disallowArray = array();
		
		while ( list( $key, $line ) = each( $robot )) {
			if ( preg_match( '/^user-agent:\\s*([^\\r\\n#]+)/i', $line, $match )) {
				$userAgent = $match[1];
			}
			
			if (preg_match( '/^disallow:\\s*([^\\r\\n#]+)/im', $line, $match )) {
				$Disallow = trim( $match[1] );
				
				if (( $Disallow != "" ) and ( $userAgent != "" )) {
					if ( $this->fileType( $Disallow, "F" )) {
						$disallowArray[$userAgent]["files"][] = $Disallow;
						
					}
					if ( $this->fileType( $Disallow, "D" )){
						$disallowArray[$userAgent]["dirs"][] = $Disallow;
					}
				}
			}
		}
		
		$this->RobotsComplete = $disallowArray;
		return TRUE;
	}
	
	
	/**
	 * Retrieves the file robots.txt from the URL specified
	 * in $RobotsURL and sets the var $RobotsContents.
	 * 
	 * @access public
	 * @return FALSE on error
	 **/
	function getFileFromURL() {
		if ( $this->useSnoopy ) {
			// Use Snoopy to get the file
			$contents = $this->Snoopy->fetch( $this->RobotsURL );
			if ( $contents !== FALSE ) {
				$contents = $this->Snoopy->results;
			}
			
			
		} else {
			// Use standard method
			$contents = file_get_contents( $this->RobotsURL );
		}
		
		if ( $contents === FALSE ) {
				return FALSE;
			
		} else {
			$this->RobotsContents = $contents;
			return TRUE;
		}
	}
	
	
	/**
	 * Checks if $theString is of the requested type.
	 * 
	 * Important definitions in the robots exclusion standard:
	 * 1. If the string ends in a slash it's a directory
	 * 2. If the string ends in an extension it's a file
	 * 3. Otherwise it is both a file and a directory
	 * 
	 * See http://www.searchengineworld.com/robots/robots_tutorial.htm
	 * for more info.
	 * 
	 * @access public
	 * @param string $theString Filename to check
	 * @param string $theType Type to report
	 * @return boolean TRUE or FALSE
	 **/
	function fileType( $theString, $theType ) {
		$type = "";
		
		if ( substr( $theString, -1 ) == "/" ) {
			$type = "D";
		}
		
		if ( $type == "" ) {
			$parts = pathinfo( $theString );
			if (( isset( $parts["extension"] )) and ( $parts["extension"] != "" )) {
				$type = "F";
			} else {
				$type = "B";
			}
		}
		
		switch ( $type ) {
			case "B":
				return TRUE;
				break;
		
			case "F":
			case "D":
				if ( $type == $theType ) {
					return TRUE;
				} else {
					return FALSE;
				}
				break;
		}
	}
}

?>
