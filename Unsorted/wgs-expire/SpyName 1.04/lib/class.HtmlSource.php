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

/*
 * Html Source class - api for getting/posting to websites, including:
 * - Cookie handling
 * - Variable handlinig for both post and get vars
 * - Response header stripping.
 * - Support for all header information like referer, user-agent etc.
 * cookie and variable handling.
 * HTTP 1.0 specs - http://www.w3.org/Protocols/rfc1945/rfc1945]
 * Easier to read version at http://www.ics.uci.edu/pub/ietf/http/rfc1945.html
 * @author C.Small
 * @version 1.2 Error checking for socket connecting.
 * @version 1.1 Added PEAR style comments
 * @version 1.0
 * @access public
 */

class HtmlSource
{
	/*
	 * The host to connect to, either with or without 'www' - a 302 error may 
	 * occur when a 'www' is needed. 
	 * @access public
	 * @type string
	 */
	var $host;
	/*
	 * The port to use, default is 80. 
	 * @access public
	 * @type integer
	 */
	var $port = 80;
	/*
	 * Page to get, including a "/", e.g. "/pages/somepage.html". 
	 * @access public
	 * @type string
	 */
	var $page;
	/*
	 * (Readonly) Contains the request that is being issued, useful for debugging. 
	 * @access public
	 * @type string
	 */
	var $request;
	/*
	 * Http version to use, currently a choice between 1.0 and 1.1. Default is 1.0. 
	 * @access public
	 * @type string
	 */
	var $httpversion = "1.0";
	/*
	 * Method to use, either "GET" or "POST", "GET" is default. 
	 * @access public
	 * @type string
	 */
	var $method = "GET";
	/*
	 * Time in seconds to wait for a respons from the connection.
	 * This may or may not work depending on your php version, some versions of
	 * 4 have a bug in fsockopen's timeout.
	 * @access public
	 * @type integer
	 */
	var $timeout = 30;
	/*
	 * Set this to true if you wish to strip all html tags from the source. 
	 * @access public
	 * @type boolean
	 */
	var $striptags;
	/*
	 * Set this to true if you want the page being requested to be returned as html 
	 * source code, with special html characters encoded. 
	 * @access public
	 * @type boolean
	 */
	var $showsource;
	/*
	 * Whether to strip the Response header out of the resulting page or not
	 * @access public
	 * @type string
	 */
	var $strip_responseheader = true;
	/*
	 * Describes what content can be returned by the server,e.g. image/jpeg or * /* for anything.
	 * @access public
	 * @type string
	 */
	var $accept;
	/*
	 * Tells the server what type of encoding is accepted, for example "gzip,deflate" is quite common. 
	 * @access public
	 * @type string
	 */
	var $accept_encoding;
	/*
	 * The language type that can be sent back by the server, e.g. "en-gb". 
	 * @access public
	 * @type string
	 */
	var $accept_language;
	/*
	 * Set this property if you are trying to access a user/password restricted zone. Use 
	 * the format "username:password", this will then be sent, base64 encoded. A 401 error 
	 * message will be displayed if the username/password is incorrect. 
	 * @access public
	 * @type string
	 */
	var $authorization;
	/*
	 * This property is used inconjunction with POSTing variables and their values. 
	 * The content length is automatically worked out for you when you set the 'method' 
	 * property to 'POST', based on the POST variables you have added; you can override this 
	 * using this property. 
	 * @access public
	 * @type string
	 */
	var $content_length;
	/*
	 * Used to indicate what kind of content is being sent. This is most commonly used when 
	 * POSTing a page, the content-type is 'application/x-www-form-urlencoded', this is 
	 * automatically added for you when you set the method='POST'. 
	 * @access public
	 * @type string
	 */
	var $content_type;
	/*
	 * Indicates the date which you are sending the request, an optional header. 
	 * The format of the date should be: Tue, 15 Nov 1994 08:12:31 GMT. You can do 
	 * this easily using strftime() function. 
	 * @access public
	 * @type string
	 */
	var $date;
	/*
	 * The referer that the page is coming from. 
	 * @access public
	 * @type string
	 */
	var $referer;
	/*
	 * The browser or application used for displaying the page sent back. An example is
	 * 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', for IE 6 on Windows 2000. 
	 * @access public
	 * @type string
	 */
	var $useragent;
	
	/*
	 * Cookies to use
	 * @access private
	 * @type array
	 * @see addCookie()
	 */
	var $cookies = array();
	/*
	 * Get vars to use
	 * @access private
	 * @type array
	 * @see addGetVar()
	 */
	var $getvars = array();
	/*
	 * Post vars to use
	 * @access private
	 * @type array
	 * @see addPostVar()
	 */
	var $postvars = array();
	
	/*
	 * Adds a POST method variable to be sent
	 * @access public
	 * @param string $name Variable name
	 * @oaram string $value Value of the variable
	 * @return void
	 */
	function addPostVar($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->postvars[] =$name."=".$value;
		}
	}
	/*
	 * Adds a GET method variable to be sent
	 * @access public
	 * @param string $name Variable name
	 * @oaram string $value Value of the variable
	 * @return void
	 */
	function addGetVar($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->getvars[] = $name."=".$value;
		}
	}
	/*
	 * Adds a Cookie name and value to be sent
	 * @access public
	 * @param string $name Variable name
	 * @oaram string $value Value of the variable
	 * @return void
	 */
	function addCookie($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->cookies[] = $name."=".$value;
		}
	}
	/*
	 * Returns the HTML source from the given host, using the request that is constructed
	 * from the various class properties
	 * @access public
	 * @return void
	 */
	function getSource()
	{
		// Error check
		if (empty($this->httpversion))
		{
			$this->httpversion = "1.0";
		}
		if (empty($this->method))
		{
			$this->method = "GET";
		}
		
		// Make GET variables
		$vars   = "";
		$cookiehead = "";
		if (sizeof($this->getvars) >0 && $this->method == "GET")
		{
			$vars  = "?";
			$vars .= join($this->getvars,"&");
			// Knock last '&' off
			// Remove this..?
			if (sizeof($this->getvars) >1)
			{
				$vars = substr($vars,0,strlen($vars) -1);
			}
		}
		// Make POST variables
		if (sizeof($this->postvars) >0 && $this->method == "POST")
		{
			$vars  = "\r\n";
			$strpostvar = join($this->postvars,"&");
			$vars .= $strpostvar;
			$vars .= "\r\n";
		}
		
		// Make Cookies
		if (sizeof($this->cookies) >0)
		{
			$cookiehead  = "Cookie: ";
			$cookiehead .= join($this->cookies,"; ");
			$cookiehead .= "\r\n";
		}
		
		// Make up request. Host isn't strictly needed except IIS winges
		if ($this->method == "POST")
		{
			$this->content_length = strlen($strpostvar);
			$this->content_type = "application/x-www-form-urlencoded";
			
			$this->request  = $this->method." ".$this->page." HTTP/".$this->httpversion."\r\n";
			$this->request .= "Host: ".$this->host."\r\n";
			$this->request .= $cookiehead;
			$this->request .= $this->_makeRequest();
			$this->request .= $vars."\r\n";
		} else{
			$this->request  = $this->method." ".$this->page.$vars." HTTP/".$this->httpversion."\r\n";
			$this->request .= "Host: ".$this->host."\r\n";
			$this->request .= $cookiehead;
			$this->request .= $this->_makeRequest();
			$this->request .= "\r\n";
		}

		// Open socket to URL
		
		$sHnd = fsockopen ($this->host, $this->port, $errno, $errstr, $this->timeout);
		if ($sHnd)
		{
    		fputs ($sHnd, $this->request);
    		
    		// Get source
    		while (!feof($sHnd))
    		{
    			$result .= fgets($sHnd,128);
    		}
    		
    		// Strip header
    		if ($this->strip_responseheader)
    		{
    			$result = $this->_stripResponseHeader($result);
    		}
    		
    		// Strip tags
    		if ($this->striptags)
    		{
    			$result = strip_tags($result);
    		}
    		// Show the source only
    		if ($this->showsource && !$this->striptags)
    		{
    			$result = htmlentities($result);
    			$result = nl2br($result);
    		}
		} else {
			$result = "Unable to connect to ".$this->host." on port ".$this->port." ( ".$errstr.")";
		}
		return $result;
	}
	
	/*
	 * Assembles the http request to be sent to the host
	 * @access private
	 * @return string A string seperated by \r\n of various values constructed from the class properties
	 */
	function _makeRequest()
	{
		if (!empty($this->accept))
		{
			$result .= "Accept: ".$this->accept."\r\n";
		}
		if (!empty($this->accept_encoding))
		{
			$result .= "Accept-Encoding: ".$this->accept_encoding."\r\n";
		}
		if (!empty($this->accept_language))
		{
			$result .= "Accept-Language: ".$this->accept_language."\r\n";
		}
		if (!empty($this->authorization))
		{
			$result .= "Authorization: Basic ".base64_encode($this->authorization)."\r\n";
		}
		if (!empty($this->content_length))
		{
			$result .= "Content-length: ".$this->content_length."\r\n";
		}
		if (!empty($this->content_type))
		{
			$result .= "Content-type: ".$this->content_type."\r\n";
		}
		if (!empty($this->date))
		{
			$result .= "Date: ".$this->date."\r\n";
		}
		if (!empty($this->referer))
		{
			$result .= "Referer: ".$this->referer."\r\n";
		}
		if (!empty($this->useragent))
		{
			$result .= "User-Agent: ".$this->useragent."\r\n";
		}
		
		return $result;
	}
	/*
	 * Removes the Response header that's returned from a host after a request is made
	 * @access private
	 * @param string $source The html, including the response header, to be stripped
	 * @return string The source without the response header
	 */
	function _stripResponseHeader($source)
	{
		$headerend = strpos($source,"\r\n\r\n");
		if (is_bool($headerend))
		{
			$result = $source;
		} else{
			$result = substr($source,$headerend+4,strlen($source) - ($headerend+4));
		}
		return $result;
	}

}
?>
