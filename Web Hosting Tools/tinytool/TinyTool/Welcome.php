<?php 
include 'config.php';
include '/usr/local/cpanel/Cpanel/Accounting.php.inc';
$WHM_version=showversion($whm_host,$whm_user,$whm_accesshash,$whm_usessl);
$WHM_packages = listpkgs($whm_host,$whm_user,$whm_accesshash,$whm_usessl);
extract($WHM_packages);
if($_GET['id'])
	{
	$item_number = $_GET['id'];
	$db = mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
	mysql_select_db($db_name) or die ('Could not SELECT database because: ' . mysql_error());
	$sql = "SELECT * from $db_table WHERE item_number='$item_number'";
	$result = mysql_query($sql,$db);
	$subscriber = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_close($db);
	extract($subscriber);
	$receipt_box = null;
	}
elseif($_POST['item_number'])
	{
	$db = mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
	mysql_select_db($db_name) or die ('Could not SELECT database because: ' . mysql_error());
	$sql = "SELECT * from $db_table WHERE item_number='".$_POST['item_number']."'";
	$result = mysql_query($sql,$db);
	$subscriber = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_close($db);
	extract($subscriber);
	foreach ($_POST as $key => $value)
		{
		if (get_magic_quotes_gpc()) $value = stripslashes ($value);
		if (!eregi("^[_0-9a-z-]{1,30}$",$key))
			{
			unset ($key); 
			unset ($value); 
			}
		if ($key != '') 
			{
			$subscr_vars[$key] = $value; 
			unset ($_POST); 
			}
		}
	extract($subscr_vars);
$receipt_box = <<<content
<div class="box">
<h5>
Thank you {$address_name} for your payment. <br />Your transaction has been completed, and a receipt for your purchase has been emailed to <i>{$payer_email}</i>. You may log into your PayPal account at <a href="http://www.paypal.com" target="_blank">www.paypal.com</a> to view details of this transaction. <br /><br />Thank you for selecting Power Hosting At Basic Prices! 
</h5>
</div>
content;
$initial_passwd = <<<content
CPanel Password: <i>{$init_pass}</i>
<h5 id="caution" align="center">
For your security, we suggest you access CPanel soon and change your password. <br /><span id="small">This page has been generated dynamically and will display your initial startup password.<br />Once you change your password it remains invisible to everyone.</span>
</h5>
content;
$table_passwd = <<<content
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Password</td>
          <td bgcolor="ivory">{$init_pass} </td>
content;
	}
else
	{
	$scrub_log=fopen("scrub_log.php", "a+");
					fwrite($scrub_log, date("D d-M-y g:i:s a T")." :: IP=". $_SERVER['REMOTE_ADDR'] . "\n");
					fclose($scrub_log);
					chmod("scrub_log.php", 0777);
	mail($business,"SCRUB:","new append to scrub_log");
	header("Location: Order.php"); 
	exit; 
	}
//
$hypertext = <<<content
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<title>New Account Information</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="generator" content="BBEdit 6.5" />
    <meta name="generator" content="HTML Tidy for Mac OS, see www.w3.org" />
<script type="text/javascript" language="Javascript">
        <!--
        function obfuscate(name,domain)
                {
                var scheme = new Array('m','a','i','l','t','o',':');
                var directive = '<a href="' + scheme.join('') + name + '&#64;' + domain + '">' + name + '&#64' + domain + '<\/a>';
                document.write(directive); 
                } 
         -->
    
</script>
<style type="text/css">
	<!-- 
	body	
	{
		background-color: white;
		color: black;
		font-size: 100%;
		margin: 2em;
		padding: 2em;
		border: thin solid maroon;
	}
	
    #t1    
    {
    	color: white;
    	font-size: 100%;
    	line-height: 150%;
    	font-weight: 600;
    	font-family: verdana, arial, geneva, tahoma, sans-serif;
    	font-style: normal;
    	font-variant: normal;
    	background-color: maroon;
    }

	tt { color: maroon; }
	p { margin: 0 1em 0 1em; }
	h4 { margin: 0 1em 0 1em; }
	dl { margin: 0 1em 0 3em; }
	ul	
	{
		margin: 0 1em 0 3em;
		text-transform: uppercase;
	}
	#small { font-size: 80%; }
	
	#caution	
	{
		background-color: yellow;
		border-color: black;
		border-width: 1px;
		border-style: dotted;
		font-weight: 500;
		margin: 0;
		padding: 1ex;
	}
	
	.box	
	{
		width: auto;
		margin: 1em;
		padding: 1em 1em 0em 1em;
		border-color: maroon;
		border-width: medium;
		border-style: solid;
		background-color: ivory;
	}
	
	 --> 
</style>
  </head>

  <body>
  
{$receipt_box}

<p>
This page has important account information so we emailed a copy to <i>{$payer_email}</i>. You can re-display this page in your browser by typing <br /><a href=" http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?id={$item_number}" target="_blank">http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?id={$item_number}</a> <br />into the address or location bar. 
</p>

<h5 id="caution" align="center">
For security reasons <u>never</u> allow anyone to have or to view your ID number or your password.
</h5>

      <table border="1" align="center" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="2" id="t1">&nbsp;&nbsp;Account Quotas</td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Domain </td>
          <td bgcolor="ivory">{$option_selection1} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">UserName</td>
          <td bgcolor="ivory">{$option_selection2} </td>
          {$table_passwd}
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Disk Space</td>
          <td bgcolor="ivory">{${$whm_name}[2]} MegaBytes</td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Bandwidth Limit</td>
          <td bgcolor="ivory">{${$whm_name}[10]} MegaBytes</td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Server Path</td>
          <td bgcolor="ivory">/home/{$option_selection2}/public_html/ </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">CGI Access</td>
          <td bgcolor="ivory">/home/{$option_selection2}/public_html/cgi-bin </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Ftp Accounts</td>
          <td bgcolor="ivory">{${$whm_name}[5]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Email Accounts</td>
          <td bgcolor="ivory">{${$whm_name}[7]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Email Lists</td>
          <td bgcolor="ivory">{${$whm_name}[8]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max SQL Databases</td>
          <td bgcolor="ivory">{${$whm_name}[6]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Sub Domains</td>
          <td bgcolor="ivory">{${$whm_name}[9]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Park Domains</td>
          <td bgcolor="ivory">{${$whm_name}[12]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Max Addon Domains</td>
          <td bgcolor="ivory">{${$whm_name}[13]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">cPanel Theme</td>
          <td bgcolor="ivory">{${$whm_name}[4]} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">IP Address</td>
          <td bgcolor="ivory">{$IP_none} </td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Package</td>
          <td bgcolor="ivory">{$item_name} </td>
        </tr>
      </table>
<br />
<p>
To host your domain at Power-Hosting-At-Basic-Prices, you must first set your domain's <i><u>nameservers</u></i> to:
</p>

<p align="center">
<b>ns1.phabp.com</b>
</p>
<p align="center">
<b>ns2.phabp.com</b>
</p>

<p>
You make that change with your domain name <i><u>registrar</u></i>. Your registrar is the company where you "bought" or registered <i>{$option_selection1}</i>. It may take up to 72 hours for your new nameserver address to propagate, or spread, throughout all the world's computers. Please be patient.
</p>
<br />
<p>
<i>{$option_selection1}</i> will soon be world-wide accessible at:
</p>

<p align="center">
<b>http://www.{$option_selection1}/</b>
<br />or simply<br />
<b>http://{$option_selection1}/</b>
</p>

<p>Until then your website may be viewed at http://{$_SERVER['HTTP_HOST']}/~{$option_selection2}/.
</p>

<br />
<hr />
<br />

<h4>CPanel Logon name: <i>{$option_selection2}</i>
<br />
{$initial_passwd}</h4>

<br />
<p>
You'll access and control your website content with CPanel (Trademark of CPanel.net). To logon to your Cpanel you will need <b><i>{$option_selection2}</i></b>, the username you created when you subscribed. You will also need the CPanel password printed above.
</p>
<br />
<p>
CPanel is very important. Through it you control your email functions, database (MySQL) functions, file uploading and editing, statistics and everything else that creates or enhances your website. You'll find complete instructions and detailed CPanel documentation at <a href="http://www.cpanel.net/docs/cp/index.html" target="_blank">http://www.cpanel.net/docs/cp/index.html</a>.
</p>
<br />
<p>
To access the {$option_selection1} CPanel from your browser, type this URL into the address or location bar:<br />
<b>http://{$option_selection1}/cpanel</b> <br />then provide your CPanel logon name and password.
</p>
<br />

<br />
<hr />
<br />

<h4>Email Address: <i>{$option_selection2}@{$option_selection1}</i>
</h4>

<br />
<p>
Your first mailbox has already been setup. Your default email address is <b><i>{$option_selection2}@{$option_selection1}</i></b> but you can have <b>{${$whm_name}[7]}</b> addresses. You create those email addresses in CPanel. Your default address, <i>{$option_selection2}@{$option_selection1}</i>, is set to accept all mail sent to your domain even if it is not addressed to an existing mailbox. In other words, wrongly addressed mail destined for anyone at <i>{$option_selection1}</i> will still be delivered to that domain. Change or disable this feature in your CPanel.
</p>
<br />
<p>
Our best advice is to keep <b><i>{$option_selection2}@{$option_selection1}</i></b> completely secure and undisclosed. Otherwise it will begin to receive Unsolicited Commercial Email (UCE), called junk mail or SPAM. 
</p>
<br />

<p>You should create several other email addresses so you can have one that's publicly displayed (which <u>will</u> get SPAM). Keep other email addresses private for friends and family, and for newsletters and contacts that won't generally release that address to SPAMmers. (Read their Privacy Statement.) If SPAM becomes a problem on one of your addresses (other than the default address), simply delete that mailbox.
</p>
<br />

<br />
<hr />
<br />

<h4>Support Form: <i>place link to form here</i>
</h4>

<br />
<p>
If you have comments or questions about your service please contact Power-Hosting-At-Basic-Prices. For your convenience we provide a Quick Contact Form on our Help Page at [URL]. 
</p>
<br />

<p>
You can also contact us by email at <script type="text/javascript" language="Javascript">obfuscate('support', 'phabp.com')</script>
</p>
<br />

</body>
</html>
content;
print $hypertext;
//
$plaintext=html2text($hypertext);
$boundary="+_+_+_".time()."_+_+_+";
$to="$address_name <$payer_email>";
$subject="$option_selection1 Account Information";
$from_address="$receiver_email";
$from_name="Service Department";
$header ="From: $from_name <$from_address>\n";
$header.="Reply-to: <$from_address>\n";
$header.="Return-path: <$from_address>\n";
$header.="To: $to\n";
$header.="X-Mailer: TinyTool MIME Mail ( http://www.teatoast.com/TinyTool/ )\n";
$header.="MIME-Version: 1.0\n";
$header.="Content-Type: multipart/alternative;\n boundary=\"$boundary\"\n\n";
//
$body="This is a MIME-encoded message\n\n".
			"--".$boundary."\nContent-Type: text/plain;\n charset=\"iso-8859-1\"\n".
			"Content-Transfer-Encoding: 7bit\n\n".
			$plaintext."\n\n".
			"--".$boundary."\nContent-Type: text/html;\n charset=\"iso-8859-1\"\n".
			"Content-Transfer-Encoding: 7bit\n\n".
			$hypertext."\n\n".
			"--".$boundary."--\n\n";
//
$result=mail($to, $subject, $body, $header);
//
exit;
//
function html2text($html)
	{
	$width = 70;
	$hr = str_pad($hr, $width, "+-");
	//
	$search = array(
	        "/\r/",										// Non-legal carriage return
	        "/[\n\t]+/",								// Newline and tab
	        '/<script[^>]*>.*?<\/script>/i',// <script> and </script>
	        '/<!-- .+? -->/',							// HTML Comments
	        '/<\/title>/i',
	        '/<h[123456][^>]*>(.+?)<\/h[123456]>/ie',	// H1 - H6
	        '/<\/p>/i',									// <p> or </p>
	        '/<br[^>]*>/i',							// <br>
	        '/<\/ul>/i',									// </ul>
	        '/<\/ol>/i',									// </ol>
	        '/<\/dl>/i',									// </dl>
	        '/<\/dt>/i',         				        // </dt>
	        '/<dd[^>]*>/i',							// <dd>
	        '/<\/dd>/i',								// </dd>
	        '/<li[^>]*>/i',							// <li>
	        '/<hr[^>]*>/i',							// <hr>
	        '/(<table[^>]*>|<\/table>)/i',	// <table> or </table>
	        '/<\/tr>/i',									// 	</tr>
	        '/<\/td>/i',								// </td>
	        '/&nbsp;/i',
	        '/&quot;/i',
	        '/&gt;/i',
	        '/&lt;/i',
	        '/&amp;/i',
	        '/&copy;/i',
	        '/&trade;/i',
	        '/&reg;/i',
	        '/&bull;/'
	    );
	//
	$replace = array(
	        "",									// Non-legal carriage return
	        "",									// Newline and tab
	        "",									// <script>s -- which strip_tags supposedly has problems with
	        "",									// Comments -- which strip_tags might have problem a with
	        "\n",
	        "ucwords(\"\n\n\\1\n\n\")", 		// H1 - H6
	        "\n",								// <p> or </p>
	        "\n",								// <br>
	        "\n",								// <ul> or </ul>
	        "\n",								// <ol> or </ol>
	        "\n",								// </dl>
	        "\n",								// </dt>
	        "\t\t",							// <dd>
	        "\n",								// </dd>
	        "\t*\t",							// <li>
	        "\n".$hr."\n",				// <hr>
	        "\n\n",							// <table> or </table>
	        "\n",								// 	</tr>
	        "\t\t\t\t\t",					// </td>
	        ' ',									// &nbsp;
	        '"',								// &quot;
	        '>',								// &gt;
	        '<',								// &lt;
	        '&',								// &amp;
	        '(c)',								// &copy;
	        '(tm)',							// &trade;
	        '(R)',								// &reg;
	        '*'									// &bull;
	    );
	//
	$text = trim(stripslashes($html));
	$text = preg_replace($search, $replace, $text);
	$text = strip_tags($text);
	$text = preg_replace("/\n[[:space:]]+/", "\n\n", $text);
	$text = preg_replace("/[\n]{3,}/", "\n\n", $text);
	$text = wordwrap($text, $width);
	return $text;
	}
?>
