<?
  function print_login_header($title) {

    global $user, $cfg;
    global $debug_time_start;

    $debug_time_start = microtime();

    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$cfg[site_name] Document Management: $title</title>\n";

    echo "<meta http-equiv=\"Expires\" content=\"".gmdate("l, d F Y H:i:s")." GMT\">\n";
    echo "<meta http-equiv=\"Last-Modified\" content=\"".gmdate("l, d F Y H:i:s")." GMT\">\n";
    echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\">\n";
    echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";

    echo "<style type=\"text/css\">\n";
    echo "  body { font: 10pt Helvetica, Arial; }\n";
    echo "  form { font: 10pt Helvetica, Arial; }\n";
    echo "  h1 { font: 18pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h2 { font: 16pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h3 { font: 12pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h4 { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  td { font: 10pt Helvetica, Arial; }\n";
    echo "  th { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  p { font: 10pt Helvetica, Arial; }\n";
    echo "  a { font: font-weight: bold; color: $cfg[link_color]; }\n";
    echo "  a.active { font-weight: bold; color: $cfg[active_link_colour]; }\n";
    echo "  a:active { font-weight: bold; color: $cfg[active_link_colour]; }\n";
    echo "  a:visited { font-weight: bold; color: $cfg[visited_link_colour]; }\n";
    echo "  a.visited { font-weight: bold; color: $cfg[visited_link_colour]; }\n";
    echo "  b { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  .desc { font: 8pt Helvetica, Arial; font-weight: normal; color: $cfg[table_text]; }\n";
    echo "  .descb { font: 8pt Helvetica, Arial; font-weight: normal; }\n";
    echo "</style>\n";

    echo "</head>\n";
    echo "<body style=\"margin: 0;\" bgcolor=\"$cfg[page_bg]\">\n";
    echo "<table border=\"0\" bgcolor=\"$cfg[table_bg]\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
    echo "<tr>\n";
    echo "<td align=\"left\">\n";
    echo "<b><font color=\"$cfg[header_text]\">Not Logged in</font></b>\n";
    echo "</td>\n";
    echo "<td align=\"right\">\n<font color=\"$cfg[header_text]\">&nbsp;\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<hr size=\"1\">\n";
  }

  function print_header($title) {

    global $user, $cfg;
    global $debug_time_start;

    $debug_time_start = microtime();

    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$cfg[site_name] Document Management: $title</title>\n";

    echo "<meta http-equiv=\"Expires\" content=\"".gmdate("l, d F Y H:i:s")." GMT\">\n";
    echo "<meta http-equiv=\"Last-Modified\" content=\"".gmdate("l, d F Y H:i:s")." GMT\">\n";
    echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\">\n";
    echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";

    echo "<style type=\"text/css\">\n";
    echo "  body { font: 10pt Helvetica, Arial; }\n";
    echo "  form { font: 10pt Helvetica, Arial; }\n";
    echo "  h1 { font: 18pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h2 { font: 16pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h3 { font: 12pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  h4 { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  td { font: 10pt Helvetica, Arial; }\n";
    echo "  th { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  p { font: 10pt Helvetica, Arial; }\n";
    echo "  a { font: font-weight: bold; color: $cfg[link_color]; }\n";
    echo "  a.active { font-weight: bold; color: $cfg[active_link_colour]; }\n";
    echo "  a:active { font-weight: bold; color: $cfg[active_link_colour]; }\n";
    echo "  a:visited { font-weight: bold; color: $cfg[visited_link_colour]; }\n";
    echo "  a.visited { font-weight: bold; color: $cfg[visited_link_colour]; }\n";
    echo "  b { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
    echo "  .desc { font: 8pt Helvetica, Arial; font-weight: normal; color: $cfg[table_text]; }\n";
    echo "  .descb { font: 8pt Helvetica, Arial; font-weight: normal; }\n";
    echo "</style>\n";

    echo "</head>\n";
    echo "<body style=\"margin: 0;\" bgcolor=\"$cfg[page_bg]\">\n";
    echo "<table border=\"0\" bgcolor=\"$cfg[table_bg]\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
    echo "<tr>\n";
    echo "<td align=\"left\">\n";
    echo "<b><font color=\"$cfg[header_text]\">Logged in as ";
    if($user->god)
        echo "(GOD) ";
    echo "$user->name\n";
    echo "</font></b>\n";
    echo "</td>\n";
    echo "<td align=\"right\">\n<font color=\"$cfg[header_text]\">\n";
    echo "<a href=\"main.php\"><b>Home</b></a> |\n";
    echo "<a href=\"contacts.php\"><b>Contacts</b></a> |\n";
    echo "<a href=\"message.php\"><b>Messages</b></a> |\n";
    echo "<a href=\"list.php\"><b>List</b></a> | \n";
    echo "<a href=\"up.php\"><b>Update</b></a> | \n";
    echo "<a href=\"new.php\"><b>New</b></a> | \n";
    if($user->god) {
        echo "<a href=\"users.php\"><b>Users</b></a> | \n";
        echo "<a href=\"logs.php\"><b>Logs</b></a> | \n";
    }
    echo "<a href=\"logout.php\" target=\"_top\"><b>Logout</b></a></font>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<hr size=\"1\">\n";
  }

  function print_login_footer() {
    global $cfg;

    if($cfg[debug]) {
        global $debug_time_start;
        $debug_time_end = microtime();

        $start = explode(" ", $debug_time_start);
        $end = explode(" ", $debug_time_end);

        $elapsed = ($end[0] - $start[0]) + ($end[1] - $start[1]);
    }

    echo "<table border=\"0\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"right\">\n";
    echo "<hr size=\"1\">\n";
    echo "<p class=\"descb\">SDMS v$cfg[version], Copyright (C) 2000 <a href=\"mailto:sdms@cafuego.net\">Cafuego</a>; <a href=\"http://www.gnu.org/copyleft/gpl.html#SEC1\">LICENSE</a>\n";
    if( $cfg[debug] )
      echo "<br>debug: Generated in ". number_format( $elapsed, 3, ".", ",") ." seconds.\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</body>\n";
    echo "</html>\n";
  }

  function print_footer() {
    global $cfg;

    if($cfg[debug]) {
        global $debug_time_start;
        $debug_time_end = microtime();

        $start = explode(" ", $debug_time_start);
        $end = explode(" ", $debug_time_end);

        $elapsed = ($end[0] - $start[0]) + ($end[1] - $start[1]);
    }

    echo "<table border=\"0\" width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"right\">\n";
    echo "<form action=\"search.php\" method=\"post\">\n";
    echo "<hr size=\"1\">\n";
    echo "<input type=\"text\" size=\"12\" name=\"query\">\n";
    echo "<input type=\"submit\" value=\"Find!\">\n";
    echo "</form>\n";
    echo "<p class=\"descb\">SDMS v$cfg[version], Copyright (C) 2000 <a href=\"mailto:sdms@cafuego.net\">Cafuego</a>; <a href=\"http://www.gnu.org/copyleft/gpl.html#SEC1\">LICENSE</a>\n";
    if( $cfg[debug] )
      echo "<br>debug: Generated in ". number_format( $elapsed, 3, ".", ",") ." seconds.\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</body>\n";
    echo "</html>\n";
  }

  function neutral_table_start($align, $outer = 0, $inner = 0) {
    echo "<div align=\"$align\">\n";
    echo "<table border=\"$outer\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "<tr>\n<td>\n";
    echo "<table border=\"$inner\" cellpadding=\"4\" cellspacing=\"0\">\n";
  }

  function table_start($align, $outer = 0, $inner = 0) {
    global $cfg;
    echo "<div align=\"$align\">\n";
    echo "<table border=\"$outer\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"$cfg[table_bg]\">\n";
    echo "<tr>\n<td>\n";
    echo "<table border=\"$inner\" cellpadding=\"4\" cellspacing=\"0\">\n";
  }

  function table_end() {
    echo "</table>\n";
    echo "</td>\n</tr>\n";
    echo "</table>\n";
    echo "</div>\n";
  }

  function get_extension($filename) {
    $tmp = explode(".", $filename);
    if( sizeof($tmp) > 1)
        return strtolower($tmp[sizeof($tmp)-1]);
    else
        return "none";
  }

  function is_god($user_id) {
    $res = @mysql_query("SELECT user FROM gods WHERE user=$user_id");
    if(mysql_errno())
      return false;
    if(mysql_num_rows($res) == 1)
      return true;
    return false;
  }

  function may_read($user_id, $document_id) {
    if( is_god($user_id) )
      return true;
    $res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");
    if(mysql_errno())
      return false;
    if(mysql_num_rows($res) != 1)
      return false;
    return true;
  }

  function may_write($user_id, $document_id) {
    if( is_god($user_id) )
      return true;
    $res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");
    if(mysql_errno())
      return false;
    if(mysql_num_rows($res) != 1)
      return false;
    $row = @mysql_fetch_array($res);
    if($row[level] != "R")
      return true;
    return false;
  } 

  function may_god($user_id, $document_id) {
    if( is_god($user_id) )
      return true;
    $res = @mysql_query("SELECT level FROM ACL WHERE user_id=$user_id AND document_id=$document_id");   
    if(mysql_errno())
      return false;
    if(mysql_num_rows($res) != 1)
      return false;
    $row = @mysql_fetch_array($res);
    if($row[level] == "G")
      return true;
    return false;
  }

  function access_string($level) {
    switch($level) {
      case "R":
        return "Read-Only";
        break;
      case "W":
        return "Read/Write";
        break;
      case "G":
        return "God Mode";
        break;
      case "X":
        return "No Access";
        break;
      default:
        return "Unknown";
        break;
    }
  }

  function get_access($user_id,$document_id) {
    if( may_god($user_id,$document_id))
      return "G";
    if( may_write($user_id,$document_id))
      return "W";
    if( may_read($user_id,$document_id))
      return "R";
    return "X";
  }
?>
