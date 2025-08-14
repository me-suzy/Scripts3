<?
 $_POST = $HTTP_POST_VARS;
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
 $plan_query = "SELECT plan.digger FROM plan LEFT JOIN member ON plan.id=member.acctype WHERE member.login = '$login'";
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
  print template_parse("<%include(index.template)");
?>

<FORM method=post>
<INPUT type=text name=url size=50>
<BR>
Enter URL to dig above.
<INPUT type=submit value="Dig URL">
</FORM>
<P>
<B>Note:</B> URLS that do not end in a filename must end in a trailing slash.
<BR>
Correct: http://www.webguystudios.com/
<BR>
Incorrect: http://www.webguystudios.com
<P>

<?
 (isset($_POST["url"])) ? $url = $_POST["url"] : $url = "";
 $relative = array();
 $absolute = array();

 if ($url == "") {
  exit;
 }

 print "Results for " . $url . "<P>\n";

 if ($url != "") {
  $content = implode(" ", file($url));
  preg_match_all("|href=\"?([^\"' >]+)|i", $content, $links); 
  while(list(,$link) = each($links[1])) {
   if(preg_match("/(^javascript:|\.css|mailto:)/", $link)) { 
    continue; 
   }
   if (preg_match("/^http:\/\//", $link)) {
    array_push($absolute, $link);
   } else {
    array_push($relative, $link);
   }
  }
 }

 $absolute = array_unique($absolute);
 $relative = array_unique($relative);
 $absolute_count = count($absolute);
 $relative_count = count($relative);
 print $absolute_count . " absolute links:<P>\n";
 foreach ($absolute as $ex_link) {
  print "<A HREF=\"";
  print $ex_link;
  print "\">" . $ex_link . "</A><BR>\n";
 }
 print "<P>\n";
 print $relative_count . " relative links:<P>\n";
 foreach ($relative as $in_link) {
  $base_url = "";
  if (preg_match("/^\//", $in_link)) {
   preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $matches);
   $path = split("\/", $in_link);
   $base_url = $matches[1] . $matches[2];
  } else {
   $path = split("\/", $url);
   $length = count($path);
   $base_url = "http:";
   for ($i=1; $i<$length-1; $i++) {
    $base_url = $base_url . "/" . $path[$i];
   }
   $base_url = $base_url . "/";
  }
  print "<A HREF=\"" . $base_url;
  print $in_link;
  print "\">" . $base_url . $in_link . "</A><BR>\n";
 }
?>

