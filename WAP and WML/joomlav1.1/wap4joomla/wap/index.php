<?PHP
// A file to redirect users based on their browsers.
$htmlredirect = "http://mediafinder.sytes.net/"; //your main mambo site address
$wmlredirect = "http://wap.mediafinder.sytes.net/menu.php";  //your wap site address
if(strpos(strtoupper($HTTP_ACCEPT),"VND.WAP.WML") > 0) 
{
$br = "WML";
}
else 
{
$browser=substr(trim($HTTP_USER_AGENT),0,4);
if($browser=="Noki" ||   // Nokia phones and emulators
$browser=="Eric" ||   // Ericsson WAP phones and emulators
$browser=="WapI" ||   // Ericsson WapIDE 2.0
$browser=="MC21" ||   // Ericsson MC218
$browser=="AUR " ||   // Ericsson R320
$browser=="R380" ||   // Ericsson R380
$browser=="UP.B" ||   // UP.Browser
$browser=="WinW" ||   // WinWAP browser
$browser=="UPG1" ||   // UP.SDK 4.0
$browser=="upsi" ||   // another kind of UP.Browser ??
$browser=="QWAP" ||   // unknown QWAPPER browser
$browser=="Jigs" ||   // unknown JigSaw browser
$browser=="Java" ||   // unknown Java based browser
$browser=="Alca" ||   // unknown Alcatel-BE3 browser (UP based?)
$browser=="MITS" ||   // unknown Mitsubishi browser
$browser=="MOT-" ||   // unknown browser (UP based?)
$browser=="My S" ||   // unknown Ericsson devkit browser ?
$browser=="WAPJ" ||   // Virtual WAPJAG www.wapjag.de
$browser=="fetc" ||   // fetchpage.cgi Perl script from www.wapcab.de
$browser=="ALAV" ||   // yet another unknown UP based browser ?
$browser=="Wapa")     // another unknown browser ("Wapalyzer"?)
{
$br = "WML";
}
else 
{
$br = "HTML";
}
}
if($br == "WML") 
{
header("302 Moved Temporarily");
header("Location: ".$wmlredirect);
exit;
}
else 
{header("302 Moved Temporarily");
header("Location: ".$htmlredirect);
exit;
}
?>