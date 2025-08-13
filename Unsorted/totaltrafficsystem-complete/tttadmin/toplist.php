<?php
//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
require("../ttt-mysqlvalues.inc.php");
require("../ttt-mysqlfunc.inc.php");
open_conn();
require("security.inc.php");
if ($_POST["forceupdate"] != "") { require("../ttt-do_reset.inc.php"); toplist(1); $msg = "Toplists Regenerated"; }
else { $msg = "Toplist Templates"; }
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Toplist</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="POST">
<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center"><b><font size="4"><?php echo $msg; ?></font></b></div></td>
  </tr>
  <tr> 
    <td><font size="2">You currently have these toplist templates in your &quot;ttt_toplist&quot; 
      dir:</font></td>
  </tr>
  <tr> 
    <td><font size="2">
<?php
$dir = @opendir("../ttt_toplist") or print_error("Could not open \"ttt_toplist\"");
while (($file = readdir($dir)) !== false)
{
	if (substr($file, -6) == ".thtml") {
		$file2 = str_replace(".thtml", ".html", $file);
print <<<END
    $file - <a href="../ttt_toplist/$file" target="_blank">View Template</a> - <a href="../ttt_toplist/$file2" target="_blank">View Toplist</a> - <a href="#" onclick="document.form1.code.value='&lt;!--#include virtual=&quot;ttt_toplist/$file2&quot; --&gt;'">Gen Code</a><br>
END;
}
}  
?>
<center><br><input type="text" name="code" size="50" value="Toplist include code"><br>
<input type="submit" name="forceupdate" value="Regenerate All Toplists"><br>
Insert the generated code above, in your page where you want the toplist displayed
</center>
    </font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="center"><strong><font size="4">Toplist Template Codes</font></strong></div></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="40%"><font size="2">##sitedomain1##<br>
            ##sitename1##<br>
            ##in1## <br>
	    ##out1##<br>
            ##link1## <br>
            </font></td>
          <td width="60%"><font size="2">Site domain of your number 1 trade.<br>
            Site name of your number 1 trade.<br>
            Hits in from your number 1 trade.<br>
	    Hits out to your number 1 trade.<br>
	Link to the trade with sitename</font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><font size="2">Change &quot;##sitedomain1##&quot; to &quot;##sitedomain2##&quot; 
      to display the site domain of your number 2 trade.<br>
      Numbers of trades are based on total hits in.</font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="center"><strong><font size="4">Toplist Template Help</font></strong></div></td>
  </tr>
  <tr> 
    <td><font size="2">When you upload a template with the &quot;.thtml&quot; 
      extension to the &quot;ttt_toplist&quot; directory TTT will automaticaly 
      generate a toplist each hour with the extension &quot;.html&quot;. IE. &quot;mytoplist.thtml&quot; 
      will generate a toplist called &quot;mytoplist.html&quot;.<br>
      In order to display a toplist on your page you must put this SSI code in 
      your page.</font></td>
  </tr>
  <tr> 
    <td><div align="center"><em><font size="2">&lt;!--#include virtual=&quot;ttt_toplist/&lt;toplistname&gt;.html&quot; 
        --&gt;</font></em></div></td>
  </tr>
  <tr> 
    <td><font size="2">Change &lt;toplistname&gt; with the name of the toplist 
      you want to include.<br>
      Please note that the page you want to include the toplist on must have the 
      extension &quot;.shtml&quot;</font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="center"><font size="2"><a href="javascript:window.close()">Close 
        Window</a></font></div></td>
  </tr>
</table>
</form>
</body>
</html>
