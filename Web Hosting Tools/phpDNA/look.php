<html>
<head>
<title>Whois Lookup</title>
<style>
<!--
   .text {font-family:Tahoma,sans-serif; font-size: 11px; color:#737373; padding-left:20; padding-right:10 }
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
 function putFocus(formInst, elementInst) {
  if (document.forms.length > 0) {
   document.forms[formInst].elements[elementInst].focus();
  }
 }
// The second number in the "onLoad" command in the body
// tag determines the form's focus. Counting starts with '0'
//  End -->
</script>
</head>
<body topmargin="0" marginheight="0" onLoad="putFocus(0,0);">
<div align="center"><center>
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="740" id="AutoNumber1">
                   <tr>
                             <td><div align="center"><center>
                               <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber3">
                                          <tr>
                                                    <td width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber5">
                                                      <tr>
                                                                <td width="10" align="left" valign="top">&nbsp;<p>&nbsp;</td>
                                                                <td width="720" align="left" valign="top"><p align="center"><strong><font face="Tahoma" color="#0079E7">Web Starter Kit</font></strong></p>
                                                                  <font face="Tahoma" size="2"><form method="POST" action="look.php">
       <p><b><font face="Tahoma">Step 2</font></b></p>
        <p>See your domain search results below:</p>
        <p><B><?PHP
        include ("config.php");
        if ($dname == NULL)
{
header("Location: $path/whois/whois.html");
exit;
}
//.com lookup
$GrabURL = "$path/whois/whois.php?dom=$dname.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("$dname.com is Available<BR>");
}
else
{
Print ("$dname.com is Not Available <BR>");
}
//.net lookup
$GrabURL = "$path/whois/whois.php?dom=$dname.net"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("$dname.net is Available</a> <BR>");
}
else
{
Print ("$dname.net is Not Available <BR>");
}
//.org lookup
$GrabURL = "$path/whois/whois.php?dom=$dname.org"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==NOT)
{
Print ("$dname.org is Available</a> <BR>");
}
else
{
Print ("$dname.org is Not Available <BR>");
}
//.ca lookup
$GrabURL = "$path/whois/whois.php?dom=$dname.ca"; 
$GrabStart = 'Status:         '; 
$GrabEnd = 'Domain:'; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);
if (trim($snip[0])==AVAIL)
{
Print ("$dname.ca is Available</a> <BR>");
}
else
{
Print ("$dname.ca is Not Available <BR>");
}

//.info lookup
$GrabURL = "$path/whois/whois.php?dom=$dname.info"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==NOT)
{
Print ("$dname.info is Available</a> <BR>");
}
else
{
Print ("$dname.info is Not Available <BR>");
}
//about.com lookup
$GrabURL = "$path/whois/whois.php?dom=about$dname.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("about$dname.com is Available</a> <BR>");
}
else
{
Print ("about$dname.com is Not Available <BR>");
}
//online.com lookup
$extra="online";
$GrabURL = "$path/whois/whois.php?dom=$dname$extra.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("$dname$extra.com is Available</a> <BR>");
}
else
{
Print ("$dname$extra.com is Not Available <BR>");
}
//e.com lookup
$GrabURL = "$path/whois/whois.php?dom=e$dname.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("e$dname.com is Available</a> <BR>");
}
else
{
Print ("e$dname.com is Not Available <BR>");
}
//my.com lookup
$GrabURL = "$path/whois/whois.php?dom=my$dname.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("my$dname.com is Available</a> <BR>");
}
else
{
Print ("my$dname.com is Not Available <BR>");
}
//all.com lookup
$GrabURL = "$path/whois/whois.php?dom=all$dname.com"; 
$GrabStart = '<pre>'; 
$GrabEnd = ' '; 
$OpenFile = fopen("$GrabURL", "r"); 
$RetrieveFile = fread($OpenFile, 2000); $GrabData = eregi("$GrabStart(.*)$GrabEnd", $RetrieveFile, $DataPrint); 
fclose($OpenFile); 
$snip = explode(" ", $DataPrint[1]);

if (trim($snip[0])==Whois)
{
Print ("all$dname.com is Available</a> <BR>");
}
else
{
Print ("all$dname.com is Not Available <BR>");
}

?></B></pre>
       </p>
        <p>If the domain name you searched for appears with &quot;is Available&quot;, select it, and proceed to the order form. If it is not available, search for another domain name in the box below.</p>
        <table cellSpacing="0" cellPadding="0" width="100%" border="0">
                   <tr style="font-size: small; font-family: arial, verdana, helvetica">
                             <td width="50%"><font face="Tahoma"><font size="2">Enter Domain Name: </font><input type="text" name="dname" size="20" tabindex="1"> </font><input type="image" src="../images/015.gif" align="top" border="0" name="search" width="26" height="25"></td>
                   </tr>
                   <tr style="font-size: small; font-family: arial, verdana, helvetica">
                             <!-- global tlds (left) --><td width="50%"><font class="nn-body-font"><font face="Tahoma" size="2"><br>
                               We will search the availability of .com, .net, .org, .ca and .info</font></font></td>
                             <!-- hyphen (right) --></tr>
                   <tr style="font-size: small; font-family: arial, verdana, helvetica">
                             <td width="50%"><font class="nn-body-font"><font face="Tahoma" size="2"><b><br>
                               Note</b>: Enter a domain name with out putting www or .com on the end.<br>
                               <br>
                               &nbsp;&nbsp;- Use only letters, numbers, and dashes (-).<br>
                               &nbsp;&nbsp;- A dash may not start or end the name.<br>
                               &nbsp;&nbsp;- Cannot have more than <b>63</b> characters total</font></font></td>
                   </tr>
        </table></form>
</font></p>


                                                                  </td>
                                                                <td width="10">&nbsp;</td>
                                                      </tr>
                                                      </table></td>
                                          </tr>
                                          </table></center>
                               </div></td>
                   </tr>
        </table></center>
</div>
</pre></pre></pre></pre><p><a href="http://www.monsterhosting.ca"><img border="0" src="http://www.monsterhosting.ca/whois/whois.gif"></a></p>
</body>
</html>