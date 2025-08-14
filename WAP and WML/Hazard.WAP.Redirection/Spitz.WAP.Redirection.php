<?
// Based on code by Brad Derstine -- www.bizzarscripts.net
$wmlredirect = "http://www.spitzbucket.com/web.php";// Your wapsite
$htmlredirect = "http://www.spitzbucket.com/wap.php";// Your website

// Get browser ID
$browser=substr(trim($HTTP_USER_AGENT),0,4);

// Identify Web Browsers
 if($browser=="Mozi") // Mozilla, Internet Explorer 6 & Opera 7

    {
        $br = "HTML"; // Identify the above as Web Browsers
    }
    else 
    {
        $br = "WML"; // Identify balance as Wap Devices/Emulators
    }

if($br == "HTML") {
    header("Location: ".$htmlredirect);
    exit;
    }
    else 
    {
    header("Location: ".$wmlredirect);
    exit;
    }
?>