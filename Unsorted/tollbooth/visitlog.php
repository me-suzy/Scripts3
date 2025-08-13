<?
//Website Toll Booth Script

//This function extracts keywords used from a search engine URL
//Supported search engines are Yahoo, Google, Altavista, and AllTheWeb.com
function getkeywords($thereferrer){
$searchengines=Array("search.yahoo p", "google q", "altavista q", "alltheweb q", "search.msn q");
for($i=0;$i<count($searchengines);$i++){
$currsearch=split(" ", $searchengines[$i]);
if(strpos($thereferrer, $currsearch[0])!=false){
$searchqueries=split("&", split("?", $thereferrer[1]));
break;
}
 }
if($searchqueries){
for($i=0;$i<count($searchqueries);$i++){
if($searchqueries[$i][0]==$currsearch[1]){
$thekeywords=join(", ", split(" ", urldecode($searchqueries[$i][1])));
break;
}
 }
  }
return $thekeywords;
}

//This function returns the language based on the accept language variable
function getlanguage($lan){
$languages=Array("German de", "Chinese zh", "Spanish es", "French fr", "Japanese ja", "Italian it", "English en");
for($i=0;$i<count($languages);$i++){
$currlan=split("-", $lan);
$languagedb=split(" ", $languages[$i]);
if($currlan[0]==$languagedb[1]){
$thelanguage=$languagedb[0];
break;
}
 }
if(!$thelanguage) $thelanguage="Other ($lan)";
return $thelanguage;
}

//Get IP address
if (getenv("HTTP_X_FORWARDED_FOR"))
{ 
 $ipaddress=getenv("HTTP_X_FORWARDED_FOR"); 
}
else 
if (getenv("HTTP_CLIENT_IP"))
{
 $ipaddress=getenv("HTTP_CLIENT_IP");
}
else
{ 
 $ipaddress=getenv("REMOTE_ADDR"); 
}

//$ipaddress=getenv("LOCAL_ADDR");
//Get browser agent info
$agent=split("; ", getenv("HTTP_USER_AGENT"));
//Get browser name
$browser=$agent[1];
//Get operating system
$operatingsystem=$agent[2];
//Get browser language
$thelanguage=getlanguage(getenv("HTTP_ACCEPT_LANGUAGE"));
//Get date and time
$thedate=date("l")." ".date("F")." ".date("j")." ".date("Y")." - ".date("g").":".date("i").":".date("s")." ".date("A");
//Get referrer page from a parameter passed by the visits.js Javascript file
$referrerpage=$r;
//Get the keywords used to search the page
if($referrerpage!="") $keywords=getkeywords($referrerpage);
else $keywords="Not Available";
//Check if referrer is blank
if($referrerpage=="") $referrerpage="Not Available";
//Determine if user is new to the website
$newuser=($visit1=="")?"Yes":"No";
//Get date and time of last visit
$visit=($visit1)?$visit1:$thedate;
//Get number of days since last visit
$visitdays=($visit2!="")?intval((time()-intval($visit2))/(60*60*24)):0;
//If result is a decimal number, then set it to 0
if($visitdays<1) $visitdays=0;
//Set cookie expire time in (30 days * 24 hours * 3600 seconds per hour)
$cookie_expire=time()+(30*24*3600);
//Set last visit date and time in cookie
setcookie("visit1", $thedate, $cookie_expire);
//Set current timestamp in cookie
setcookie("visit2", time(), $cookie_expire);
//Open the text file to log the new visit
$visitlog=fopen("visits.txt", "a");
//Create the new record that will contain IP address, is user new to site, browser and operating system, browser language, date and time, last visit, number of days since last visit, referrer page, and keywords used by user to search your website
$newrecord="IP Address: $ipaddress\r\nnew user: $newuser\r\nBrowser name: $browser\r\nOperating System: $operatingsystem\r\nLanguage: $thelanguage\r\nDate/Time: $thedate\r\nLast Visit: $visit\r\nDays since last visit: $visitdays\r\nReferrer page: $referrerpage\r\nKeywords used to search your site: $keywords\r\n********************************************\r\n";
//Save the new record in the file
fputs($visitlog, $newrecord);
//Close the file
fclose($visitlog);
?>