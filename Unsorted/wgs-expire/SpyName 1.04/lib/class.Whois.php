<?
/*
************************************************************************
* © www.webguystudios.com All rights reserved.
*
* This is a standard copyright header for all source code appearing
* at www.webguystudios.com. This application/class/script may be redistributed,
* as long as the above copyright remains intact. 
* Comments to contact@www.webguystudios.com 
************************************************************************
*/

/**
 * @title Whois wrapper for most global TLDs
 * @author C.Small
 * @version 1.4 - Timeout and whois_server properties added.
 * @version 1.3 - Temporary fix for .name,.pro domains
 * @version 1.2 - Error catching for .tv domains
 * @version 1.1 - Converted to php
*/

Class Whois
{
	var $whois_server;
	var $timeout = 30;

	function lookup($domain)
	{
		$result = "";
		$parts  = array();
		$host   = "";
		
		// .tv don't allow access to their whois
		if (strstr($domain,".tv"))
		{
			$result = "'.tv' domain names require you to have an account to do whois searches.";
		// New domains fix (half work, half don't)
		} elseif (strstr($domain,".name") || strstr($domain,".pro") >0){
			$result = ".name,.pro require you to have an account to do whois searches.";
		} else{
			if (empty($this->whois_server))
			{
				$parts    = explode(".",$domain);
				$testhost = $parts[sizeof($parts)-1];
				$whoisserver   = $testhost . ".whois-servers.net";
				$this->host     = gethostbyname($whoisserver);
				$this->host     = gethostbyaddr($this->host);
			
				if ($this->host == $testhost)
				{
					$this->host = "whois.internic.net";
				}
				flush();
			}
			$whoisSocket = fsockopen($this->host,43, $errno, $errstr, $this->timeout);
			
			if ($whoisSocket)
			{
				fputs($whoisSocket, $domain."\015\012");
				while (!feof($whoisSocket))
				{
					$result .= fgets($whoisSocket,128) . "<br>";
				}
				fclose($whoisSocket);
			}
		}
		return $result;
	}
}
?>
