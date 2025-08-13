<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  global $cfg;
  $user = new user($login);
  $document = new document($doc_id);

  // Log access to this document.
  //
  @mysql_query("INSERT INTO documents_log(user,document,revision,date,address) VALUES($user->id,$document->id,$document->revision,NOW(),'". addslashes(gethostbyaddr(getenv("REMOTE_ADDR"))) ."')");

  echo "<html>\n";
  echo "<head>\n";
  echo "<title>$cfg[site_name] Document Management: Download</title>\n";

  echo "<style type=\"text/css\">\n";
  echo "  body { font: 10pt Helvetica, Arial; }\n";
  echo "  form { font: 10pt Helvetica, Arial; }\n";
  echo "  h1 { font: 18pt Helvetica, Arial; font-weight: bold; }\n";
  echo "  h2 { font: 16pt Helvetica, Arial; font-weight: bold; }\n";
  echo "  h3 { font: 12pt Helvetica, Arial; font-weight: bold; }\n";
  echo "  td { font: 10pt Helvetica, Arial; }\n";
  echo "  p { font: 10pt Helvetica, Arial; }\n";
  echo "  a { font: 10pt Helvetica, Arial; font-weight: bold; color: $cfg[link_color]; }\n";
  echo "  b { font: 10pt Helvetica, Arial; font-weight: bold; }\n";
  echo "</style>\n";

  // Start the download in 3 seconds.
  //
  if( may_read($user->id,$document->id) )
    echo "<meta http-equiv=\"refresh\" content=\"3; url=file.php/$document->id/$document->name\">\n";

  echo "</head>\n";
  echo "<body style=\"margin: 0;\" bgcolor=\"$cfg[page_bg]\">\n";
  echo "<table border=\"0\" bgcolor=\"$cfg[table_bg]\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
  echo "<tr>\n";
  echo "<td align=\"left\">\n";
  echo "<b><font color=\"$cfg[header_text]\">Logged in as $user->name</font></b>\n";
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

  if(! may_read($user->id,$document->id) ) {
    echo "<h2 align=\"center\">Permission denied</h2>\n";
  } else {
    echo "<h2 align=\"center\">$document->name</h2>\n";
    echo "<h3 align=\"center\">The document will commence downloading in 3 seconds</h3>\n";
    echo "<h4 align=\"center\">If the download does not start within 3 seconds, you can download the document<br>\n";
    echo "by clicking on <a href=\"file.php/$document->id/$document->name\">$document->name</a></h4>\n";
  }

  print_footer();

?>
