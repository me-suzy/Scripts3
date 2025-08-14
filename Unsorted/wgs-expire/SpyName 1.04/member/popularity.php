<?

 require "../lib/config.php";
 include "../lib/db.php";

 $_POST = $HTTP_POST_VARS;
 $_GET = $HTTP_GET_VARS;
  session_name('sClient');
  session_start();
  if (!isset($_SESSION["login"])) {
    header("Location: ./");
    exit;
  }

 $_SESSION["login"] = $login;
 db_connect();
 $plan_query = "SELECT plan.linkpop FROM plan LEFT JOIN member ON plan.id=member.acctype WHERE member.login = '$login'";
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


 (isset($_POST["domain_list"])) ? $domain_list = $_POST["domain_list"] : $domain_list = "";
 (isset($_GET["one_off"])) ? $one_off = $_GET["one_off"] : $one_off = "";
 (isset($_POST["whois_query"])) ? $whois_query = $_POST["whois_query"] : $whois_query = "";


?>

<FORM method=post>
  <p><font size="2" face="Arial, Helvetica, sans-serif">Enter domain name(s), one per line
    to check for link popularity</font></p>
  <p>
    <TEXTAREA name=domain_list cols=35 rows=8></TEXTAREA>
  </p>
  <p><font size="2" face="Arial, Helvetica, sans-serif">Please select the search engines
    you wish to query</font></p>
  <p> <font size="2" face="Arial, Helvetica, sans-serif">
    <INPUT type=checkbox name=google>
    Google.com<BR>
    <INPUT type=checkbox name=altavista>
    Altavista.com<BR>
    <INPUT type=checkbox name=inktomi>
    Inktomi<BR>
    <INPUT type=checkbox name=yahoo>
    Yahoo</font><BR>
    <INPUT type=submit value="Get Details">
  </p>
</FORM>
<P>

<?

 if ($domain_list == "" && $one_off == "") {
  exit;
 }
 $domains = split("\n", $domain_list);
 if ($one_off != "") {
  $google = "on";
  $altavista = "on";
  $inktomi = "on";
  $yahoo = "on";
  array_push ($domains, "$one_off");
 }
 foreach ($domains as $domain) {
 if ($domain == "") { continue; }
 trim($domain);
 if (isset($google) == "on") {
  $google_total = &get_google_links($domain);
 }
 if (isset($google_total) == "") { $google_total = 0; }
 if (isset($altavista) == "on") {
  $altavista_total = &get_altavista_links($domain);
 }
 if (isset($altavista_total) == "") { $altavista_total = 0; }
 if (isset($inktomi) == "on") {
  $inktomi_total = &get_inktomi_links($domain);
 }
 if (isset($inktomi_total) == "") { $inktomi_total = 0; }
 if (isset($yahoo) == "on") {
  $yahoo_total = &get_yahoo_links($domain);
 }
 if (isset($yahoo_total) == "") { $yahoo_total = 0; }

 print "<font face=\"Arial\" size=\"2\" color=\"#FF0000\">Results for $domain:</font><P>\n";
 print "<TABLE cellpadding=0>\n";
 if (isset($google) == "on") {
  print "<TR><TD><A HREF=\"http://www.google.ca/search?hl=en&ie=UTF-8&oe=UTF-8&q=link:$domain\" target=_blank>";
  print "<font face=\"Arial\" size=\"2\">Google</A>:</font></TD><TD><font face=\"Arial\" size=\"2\">$google_total</TD></TR></font>\n";
 }
 if (isset($altavista) == "on") {
  print "<TR><TD><A HREF=\"http://altavista.com/web/results?q=link:$domain&kgs=0&kls=1&avkw=qtrp\" target=_blank>";
  print "<font face=\"Arial\" size=\"2\">Altavista</A>:</font></TD><TD><font face=\"Arial\" size=\"2\">$altavista_total</TD></TR></font>\n";
 }
 if (isset($inktomi) == "on") {
  print "<TR><TD><A HREF=\"http://search.msn.com/results.asp?RS=CHECKED&FORM=MSNH&v=1&q=linkdomain:$domain\" target=_blank>";
  print "<font face=\"Arial\" size=\"2\">Inktomi</A>:</font></TD><TD><font face=\"Arial\" size=\"2\">$inktomi_total</TD></TR></font>\n";
 }
 if (isset($yahoo) == "on") {
  print "<TR><TD><A HREF=\"http://search.yahoo.com/bin/search?p=link:$domain\" target=_blank>";
  print "<font face=\"Arial\" size=\"2\">Yahoo</A>:</font></TD><TD><font face=\"Arial\" size=\"2\">$yahoo_total</TD></TR></font>\n";
 }
 $total_results=$yahoo_total+$inktomi_total+$altavista_total+$google_total;
 print  "<font face=\"Arial\" size=\"2\">Total: $total_results</font>\n";
 print "</TABLE>\n";
 print "<P>\n";
 }

function get_google_links($domain) {
 $lines = array();
 $host = "www.google.ca";
 $path = "/search?hl=en&ie=UTF-8&oe=UTF-8&q=link:" . $domain;
 $fp = fsockopen($host, "80");
 if ($fp) {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/of about/", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*of about <b>/", "", $total_sites);
    $total_sites = preg_replace("/<.*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
    return($total_sites);
   }
  }
 } else {
  echo "Can't connect to host... ";
 }
}

function get_altavista_links($domain) {
 $lines = array();
 $host = "www.altavista.com";
 $path = "/web/results?q=link:" . $domain . "&kgs=0&kls=1&avkw=qtrp";
 $fp = fsockopen($host, "80");
 if ($fp) {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/AltaVista found/", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*AltaVista found /", "", $total_sites);
    $total_sites = preg_replace("/ .*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
    return($total_sites);
   }
  }
 } else {
  echo "Can't connect to host... ";
 }
}

function get_inktomi_links($domain) {
 $lines = array();
 $host = "search.msn.com";
 $path = "/results.asp?RS=CHECKED&FORM=MSNH&v=1&q=linkdomain:" . $domain;
 $fp = fsockopen($host, "80");
 if ($fp) {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/ of about /", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*of about /", "", $total_sites);
    $total_sites = preg_replace("/ .*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
    return($total_sites);
   }
  }
 } else {
  echo "Can't connect to host... ";
 }
}

function get_yahoo_links($domain) {
 $lines = array();
 $host = "search.yahoo.com";
 $path = "/bin/search?p=link:" . $domain;
 $fp = fsockopen($host, "80");
 if ($fp) {
  fputs($fp, "GET ".$path." HTTP/1.0\r\nHost: ".$host."\r\n\r\n");
  while(!feof($fp)) {
   $line = fgets($fp, 4096);
   if (preg_match("/^1 \- /", $line)) {
    $total_sites = $line;
    $total_sites = preg_replace("/^.*of /", "", $total_sites);
    $total_sites = preg_replace("/ .*$/", "", $total_sites);
    $total_sites = preg_replace("/\,/", "", $total_sites);
    $total_sites = trim($total_sites);
    return($total_sites);
   }
  }
 } else {
  echo "Can't connect to host... ";
 }
}

?>