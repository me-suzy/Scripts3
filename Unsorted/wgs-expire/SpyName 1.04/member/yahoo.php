<?

 error_reporting(E_ERROR | E_WARNING | E_PARSE);

 $_POST = $HTTP_POST_VARS;
 $_GET = $HTTP_GET_VARS;

  require "../lib/template.php";
  require "../lib/config.php";
  require "../lib/db.php";
  session_name('sClient');
  session_start();
  if (!isset($_SESSION["login"])) {
    header("Location: ./");
    exit;
  }
  if (isset($_GET["logout"])) {
       session_destroy();
    header("Location: ./");
    exit;
  }

 $_SESSION["login"] = $login;
 db_connect();
 $plan_query = "SELECT plan.yamoz FROM plan LEFT JOIN member ON plan.id=member.acctype WHERE member.login = '$login'";
 $plan_result = mysql_query($plan_query) or die("Query failed: $plan_query");
 while ($line = mysql_fetch_array($plan_result, MYSQL_ASSOC)) {
  foreach ($line as $col_value) {
   if ($col_value != "on") {
    print "This feature is not enabled in your current plan.";
    exit;
   }
  }
 }
 mysql_free_result($plan_result);
 db_close();
  

 (isset($_POST["directory_list"])) ? $directory_list = $_POST["directory_list"] : $directory_list = "";
 (isset($_POST["days"])) ? $days = $_POST["days"] : $days = "";
 (isset($_POST["display"])) ? $display = $_POST["display"] : $display = "";

 if ($display == "html" || $display == "") {
 	print template_parse("<%include(index.template)");

?>

<FORM method=post>
  <TABLE>
    <TR>
<TD align=center>Enter up to 10 Yahoo directories below:</TD>
<TD align=center>that expires in</TD>
<TD align=center>display as</TD>
<TD>&nbsp;</TD>
</TR>
<TR>
<TD align=left><TEXTAREA name=directory_list cols=50 rows=10></TEXTAREA>
</TD>
<TD valign=top>
<SELECT name=days>
 <OPTION value=31>the next month</OPTION>
 <OPTION value=92>3 months</OPTION>
 <OPTION value=183>6 months</OPTION>
 <OPTION value=275>9 months</OPTION>
 <OPTION value=365>12 months</OPTION>
 <OPTION value=0>already expired</OPTION>
</SELECT>
</TD>
<TD valign=top>
<SELECT name=display>
 <OPTION value=html>HTML</OPTION>
 <OPTION value=text>Text</OPTION>
</SELECT>
</TD>
<TD valign=top>
<INPUT type=submit value=Search>
</TD>
</TR>
</TABLE>
</FORM>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="70%" id="AutoNumber1">
  <tr>
    <td width="100%"><font size="2" face="Arial">Please enter one or more Yahoo directories to see 
the expiration dates for the domains listed in that directories <br>
Note: You can only search a maximum of 10 directories at a time.<br>
<br>
<strong>Example:</strong><br>
Valid Yahoo directory: <br>
http://dir.yahoo.com/Business_and_Economy/Shopping_and_Services/Books/Accessories/</font><p><font face="Arial" size="2">To download the entire list of Yahoo directory, 
right click the file below and select &quot;<b>Save target as</b>&quot;<br>
<a href="yahoo.txt">Yahoo Full Directory File</a> (~2.238KB) </font></p>

    </td>
  </tr>
</table>

<?

 } else {
  header("Content-Type: text/plain");
  header("Content-Disposition: filename=domains.txt");
 }

 $time_frame = 86400 * $days;

 if ($directory_list == "") {
  exit;
 }

 $directories = split("\n", $directory_list);
 if (count($directories) > 10) {
  print "You may only enter up to 10 directories at once.";
  exit;
 }

foreach ($directories as $directory) {

 preg_replace("/\n/", "", $directory);
 $directory = trim($directory);

 if ($display == "html") {
  print "<HR width=100%>\n";
  print "<P><FONT face=Arial size=2>";
  print "<B>Results for: <FONT color=red>$directory</FONT></B><P>\n";
  print "<HR width=100%>\n";
 }

 if (!preg_match("/^http\:\/\/dir.yahoo.com\//", $directory)) {
  print "Skipping.  Not a valid Yahoo directory.\n";
  continue;
 }

 $content = file($directory);

 if ($content == 0) {
  print "Skipping.  Unable to open directory... please verify URL.\n";
  continue;
 }

 $current_unix = strtotime ("now");
 $counter = 0;

 foreach ($content as $line) {
  if (preg_match("/srd.yahoo.com/", $line) && !preg_match("/R=(\d)/", $line)) {
   $result = "";
   $line = preg_replace("/^.*\*http:\/\//", "", $line);
   preg_match("/([\w\d-])*\.(com|net|org)(\"|\/)/", $line, $matches);
   $domain = @ $matches[0];
   $domain = preg_replace("/(\"$|\/$)/", "", $domain);
   if ($domain == "yahoo.com") {
    continue;
   }
   $counter++;
   if (preg_match("/\.org$/", $domain)) {
    $whois_server = "whois.publicinterestregistry.org";
    $suffix = "org";
   } else {
    $whois_server = "whois.crsnic.net";
    $suffix = "comnet";
   }
   $fp = fsockopen ($whois_server, 43, $errno, $errstr, 10);
   if (!$fp) {
    print "$errstr ($errno)<br>\n";
   } else {
    fputs ($fp, "$domain\r\n");
    while (!feof($fp)) {
     $result .= fgets ($fp, 128);
    }
    $whois_results = split("\n", $result);
    foreach ($whois_results as $line) {
     if (preg_match("/Creation Date:/", $line) && $suffix == "comnet") {
      $creation = $line;
      $creation = preg_replace("/Creation Date: /", "", $creation);
      $creation = trim($creation);
      $creation_unix = strtotime ($creation);
     }
     if (preg_match("/Created On:/", $line) && $suffix == "org") {
      $creation = $line;
      $creation = preg_replace("/Created On:/", "", $creation);
      $creation = ltrim($creation);
      $creation = preg_replace("/\ .*$/", "", $creation);
      $creation_unix = strtotime ($creation);
     }
     if (preg_match("/Expiration Date:/", $line) && $suffix == "comnet") {
      $expiration = $line;
      $expiration = preg_replace("/Expiration Date: /", "", $expiration);
      $expiration = ltrim($expiration);
      $expiration = preg_replace("/\ .*$/", "", $expiration);
      $expiration_unix = strtotime ($expiration);
     }
     if (preg_match("/Expiration Date:/", $line) && $suffix == "org") {
      $expiration = $line;
      $expiration = preg_replace("/Expiration Date:/", "", $expiration);
      $expiration = trim($expiration);
      $expiration = preg_replace("/\ .*$/", "", $expiration);
      $expiration_unix = strtotime ($expiration);
     }
     $domain_upper = strtoupper($domain);
     if (preg_match("/No match for \"$domain_upper\"/", $line)) {
      $expiration = "Already";
     }
    }
    fclose ($fp);
   }

   $time_to_expiry = $expiration_unix - $current_unix;
   if (($time_to_expiry <= $time_frame && $time_frame != 0) || ($time_frame == 0 && $expiration == "Already"))  {
    if ($domain != "") {
     if ($display == "html") {
      print "<P>\n";
      print "($counter) ";
     }
     print "$domain\n";
     if ($display == "html") {
      print "<BR>";
      print "Creation Date: $creation<BR>\n";
      print "<FONT color=red>";
      print "Expiration Date: $expiration";
      print "</FONT>\n";
     }
    }
   } 
  }
 }
}

?>

