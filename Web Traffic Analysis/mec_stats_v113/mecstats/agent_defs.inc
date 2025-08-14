<?
//Written by Matt Toigo

//AGENT DEFINITIONS 1.04
//Key = Name, Element = Pattern

//BROWSER
$browser_def = array();
$browser_def['Internet Explorer'] = 'MSIE';
$browser_def['Internet Explorer 6'] = 'MSIE 6.0';
$browser_def['Internet Explorer 5'] = 'MSIE 5';
$browser_def['Internet Explorer 5.5'] = 'MSIE 5.5';
$browser_def['Mozilla X'] = 'Gecko';
$browser_def['Mozilla 1.4'] = 'rv:1.4';
$browser_def['Mozilla 1.5'] = 'rv:1.5';
$browser_def['Mozilla 1.6'] = 'rv:1.6';
$browser_def['Mozilla 1.7'] = 'rv:1.7';
$browser_def['Safari'] = 'Safari';
$browser_def['Netscape'] = 'Netscape';
$browser_def['Netscape 7'] = 'Netscape\/7';
$browser_def['Firefox'] = 'Firefox';
$browser_def['Firefox 0.8'] = 'Firefox\/0.8';
$browser_def['Firefox 0.9'] = 'Firefox\/0.9';
$browser_def['Konqueror'] = 'Konqueror';
$browser_def['Konqueror 3'] = 'Konqueror\/3';
$browser_def['Opera'] = 'Opera';
$browser_def['Opera 7'] = 'Opera\/7';

//OS
$os_def = array();
$os_def['Windows'] = 'Windows';
$os_def['Windows 2000 Pro'] = 'Windows NT 5.0';
$os_def['Windows XP'] = 'Windows NT 5.1';
$os_def['Windows 98'] = 'Windows 98';
$os_def['Windows NT 4.0'] = 'Windows NT 4.0';
$os_def['Mac'] = 'Mac';
$os_def['Mac OS X'] = 'Mac OS X';
$os_def['Linux'] = 'Linux';

//SEARCH ENGINES
$se_def = array();
$se_def['MSN'] = new SE('MSN', 'msnbot', 'msn.', 'http://www.msn.com', 'http://search.msn.com/msnbot.htm', 'q=');
$se_def['Yahoo!'] = new SE('Yahoo!', 'Yahoo', 'yahoo.', 'http://www.yahoo.com', 'http://help.yahoo.com/help/us/ysearch/slurp', 'p='); 
$se_def['IBM Almaden Crawler'] = new SE('IBM Almaden Crawler', 'almaden', '', '', 'http://www.almaden.ibm.com/cs/crawler', ''); 
$se_def['HTML Parser'] = new SE('HTML Parser', 'HTMLParser', '', '', '', ''); 
$se_def['AOL'] = new SE('AOL', '', 'aolsearch.aol.', 'http://www.aol.com', 'http://www.aol.com', '&query='); 
$se_def['Google'] = new SE('Google', 'Googlebot', 'google.', 'http://www.google.com', 'http://www.google.com/bot.html', 'q=');
$se_def['Sir Search'] = new SE('Sir Search', '', 'sirsearch.com', 'http://www.sirsearch.com', 'http://www.sirsearch.com', '');
$se_def['Dog Pile'] = new SE('Dog Pile', '', 'dogpile.', 'http://www.dogpile.com', 'http://www.dogpile.com', '');
$se_def['MetaCrawler'] = new SE('MetaCrawler', '', 'metacrawler.', 'http://www.metacrawler.com', 'http://www.metacrawler.com', '');
$se_def['Ask Jeeves'] = new SE('Ask Jeeves', '', 'web.ask.', 'http://www.ask.com', 'http://www.ask.com', '');
$se_def['Grub'] = new SE('Grub', 'grub', '', 'http://www.grub.org', '', '');

//$se_def[''] = new SE('', '', '', '', '', '', '');

?>