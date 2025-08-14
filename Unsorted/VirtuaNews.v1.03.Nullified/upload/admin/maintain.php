<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

if (preg_match("/(admin\/maintain.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog();

function dumptable($table) {

  $sqldump = "# Table structure for $table\n\nDROP TABLE IF EXISTS $table;\n";
  $sqldump .= "CREATE TABLE $table (\n";

  // Get field list
  $tablefields = 1;

  $getfields = query("SHOW FIELDS FROM $table");
  while ($field_arr = fetch_array($getfields)) {

    $sqldump .= iif($tablefields == 1,"",",\n");

    $sqldump .= "  $field_arr[Field] ".strtoupper($field_arr[Type]);
    if ($field_arr["Default"] != "") {
      $sqldump .= " DEFAULT '$field[Default]'";
    }

    if ($field_arr['Null'] != "YES") {
      $sqldump .= " NOT NULL";
    }
    if ($field_arr[Extra] != "") {
      $sqldump .= " $field_arr[Extra]";
    }
    $tablefields ++;
  }
  free_result($getfields);

  // Get keys list
  $getkeys = query("SHOW KEYS FROM $table");
  while ($key_arr = fetch_array($getkeys)) {
    $kname = $key_arr['Key_name'];
    if (($kname != "PRIMARY") & ($key_arr['Non_unique'] == 0)) {
      $kname = "UNIQUE|$kname";
    }
    if(!is_array($index[$kname])) {
      $index[$kname] = array();
    }
    $index[$kname][] = $key_arr['Column_name'];
  }
  free_result($getkeys);

  // get each key info
  if ($index) {
    while(list($kname,$columns) = each($index)){
      $sqldump .= ",\n";
      $colnames = implode($columns,",");

      if ($kname == "PRIMARY"){
        $sqldump .= "  PRIMARY KEY ($colnames)";
      } else {
        if (substr($kname,0,6) == "UNIQUE") {
          $sqldump .= "  UNIQUE KEY ".substr($kname,7)." ($colnames)";
        } else {
          $sqldump .= "  KEY $kname ($colnames)";
        }
      }
    }
  }
  $sqldump .= "\n);\n\n";

  $sqldump .= "# Dumping data for table $table\n\n";

  // get data
  $getrows = query("SELECT * FROM $table");
  $numfields = countfields($getrows);

  while ($row_arr = fetch_array($getrows)) {
    $sqldump .= "INSERT INTO $table VALUES(";

    $fieldcounter = 1;

    // get each field's data
    while ($fieldcounter <= $numfields) {

      $sqldump .= iif($fieldcounter == 1,"",",");

      if (isset($row_arr[$fieldcounter-1]) == 0) {
        $sqldump .= "NULL";
      } else {
        $sqldump .= "'".mysql_escape_string($row_arr[$fieldcounter-1])."'";
      }
      $fieldcounter ++;
    }

    $sqldump .= ");\n";

  }

  free_result($getrows);

  $sqldump .= "\n\n";

  return $sqldump;


}

switch ($action) {

case "maintain":

  echohtmlheader();
  echotableheader("Prune The Database");
  echotabledescription("Here you can prune news posts and comments from the database and backup the database, use it to mass delete all old posts to save on database usage",1);
  echotabledescription("Please note, this can be a very server intensive process if you are deleting or backing up alot of data",1);

  $tablerows .= returnminitablerow("Backup Database",returnlinkcode("Here","admin.php?action=maintain_backup"));
  $tablerows .= returnminitablerow("Prune the comments",returnlinkcode("Here","admin.php?action=maintain_comments"));
  $tablerows .= returnminitablerow("Prune the news",returnlinkcode("Here","admin.php?action=maintain_news"));

  echotabledescription("\n".returnminitable($tablerows,0,25)."      ");
  echotablefooter();
  echohtmlfooter();

break;

case "maintain_import":

  if (!empty($script)) {

    if ($script == "membersarea") {
      header_redirect("/","Members area");
    }

    include("importers/importfunctions.php");

    if (!@include("importers/".$script.".php")) {
      adminerror("Invalid Import Script","You have specified an invalid import script.");
    }

    exit;
  } else {

    echohtmlheader();
    echoformheader("maintain_import","Database Import");
    echotabledescription("You may use this section to import data into your VirtuaNews database.");
    echotabledescription("Once you have chosen an import script, proceed through the import routine. <span class=\"red\">DO NOT</span> press your browser's 'back' button, or refresh any pages, as this can lead to items being imported twice.");
    echotabledescription("It is highly recommended the you <span class=\"red\">BACK UP YOUR DATABASE</span> before proceeding with this process.");

    unset($scriptoptions);
    if ($handle = @opendir("importers")) {

      while ($file = readdir($handle)) {
        if (($file != ".") & ($file != "..") & ($file != "importfunctions.php") & is_file("importers/$file")) {
          $importfile = file("importers/$file");
          $scriptoptions .= "        <option value=\"".preg_replace("/\.php$/","",$file)."\">".htmlspecialchars(str_replace("// ","",trim($importfile[1])))."</option>\n";
        }
      }
      closedir($handle);
    }

    if (empty($scriptoptions)) {
      $scriptoptions = "        <option value=\"membersarea\">You do not have any import scripts to use.</option>\n";
      $scriptoptions .= "        <option value=\"membersarea\">To get more please visit the VirtuaNews.</option>\n";
      $scriptoptions .= "        <option value=\"membersarea\">members area.</option>\n";
    }

    echotablerow("Choose an import script:<br />".returnlinkcode("Get More Import Scripts","/",1),"\n      <select name=\"script\" size=\"5\" class=\"form\">\n$scriptoptions      </script>\n    ","",25);

    echoformfooter();
    echohtmlfooter();

  }

break;

case "maintain_comments":

  foreach ($cat_arr AS $key => $val) {
    $checkboxes .= returncheckboxcode("categories[$key]",1,$val[name]);
  }

  echohtmlheader();
  echoformheader("maintain_comments_kill","Prune news comments");
  echotabledescription("Use this to remove old comments from the database older than the number of days you specify");
  echotablerow("Prune from which categories:","\n$checkboxes   ");
  echoinputcode("Prune comments older than: <span class=\"red\">(in days)</span>","num_days");
  echoyesnocode("Update User Post Counts:","updatepostcounts",1);
  echoformfooter();
  echohtmlfooter();

break;

case "maintain_comments_kill":

  settype($num_days,"integer");

  if ($num_days == 0) {
    adminerror("Invalid Data Type","The number of days must be a valid integer");
  }

  unset($cat_ids);
  unset($comment_ids);
  unset($news_ids);
  unset($user_ids);

  foreach ($cat_arr AS $key => $val) {
    if ($categories[$key]) {
      $cat_ids[] = $key;
    }
  }

  if ($cat_ids) {

    $getdata = query("SELECT
      news_comment.id,
      news_comment.newsid,
      news_comment.userid
      FROM news_comment
      LEFT JOIN news_news ON news_comment.newsid = news_news.id
      WHERE (news_comment.time < ".(time()-($num_days*86400)).")
      AND (news_news.catid IN (".join(",",$cat_ids)."))");

    while ($data_arr = fetch_array($getdata)) {

      $comment_ids[] = $data_arr[id];
      $news_ids[$data_arr[newsid]] ++;

      if ($data_arr[userid] & $updatepostcounts) {
        $user_ids[$data_arr[userid]] ++;
      }
    }

    if ($comment_ids) {
      query("DELETE FROM news_comment WHERE id IN (".join(",",$comment_ids).")");

      foreach ($news_ids AS $key => $val) {
        $temp = query_first("SELECT
          news_comment.userid,
          news_comment.username AS name,
          ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
          FROM news_comment
          LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
          WHERE (news_comment.newsid = $key)
          ORDER BY news_comment.time DESC
          LIMIT 1");

        query("UPDATE news_news SET lastcommentuser = '".iif($temp[userid],$temp[username],$temp[name])."' , commentcount = commentcount - '$val' WHERE id = $key");
      }

      if ($user_ids) {
        foreach ($user_ids AS $key => $val) {
          query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'$val' WHERE $foruminfo[userid_field] = $key");
        }
      }
    }

  }

  writeallpages();

  echoadminredirect("admin.php?action=maintain");
  exit;

break;

case "maintain_user_c":

  settype($id,"integer");
  if ($temp = query_first("SELECT username FROM news_user WHERE userid = $id")) {
    echodeleteconfirm("comments","maintain_user_c_kill",$id," This will delete all the comments by the user ".htmlspecialchars($temp[username]),"","prune");
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "maintain_user_c_kill":

  verifyid($foruminfo[user_table],$id,$foruminfo[userid_field]);

  $getdata = query("SELECT newsid FROM news_comment WHERE userid = $id");
  while ($data_arr = fetch_array($getdata)) {
    $numcomments[$data_arr[newsid]] ++;
  }

  if ($numcomments) {

    query("DELETE FROM news_comment WHERE userid = $id");
    query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'".array_sum($numcomments)."' WHERE $foruminfo[userid_field] = $id");

    foreach ($numcomments AS $key => $val) {

      $temp = query_first("SELECT
        news_comment.userid,
        news_comment.username AS name,
        ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
        FROM news_comment
        LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
        WHERE (news_comment.newsid = $key)
        ORDER BY news_comment.time DESC
        LIMIT 1");

      query("UPDATE news_news SET commentcount = commentcount-'$val' , lastcommentuser = '".iif($temp[userid],$temp[username],$temp[name])."' WHERE id = $key");
    }
  }

  writeallpages();

  echoadminredirect("admin.php?action=maintain");
  exit;

break;

case "maintain_news_c":

  settype($id,"integer");
  if ($temp = query_first("SELECT title FROM news_news WHERE id = $id")) {
    echodeleteconfirm("comments","maintain_news_c_kill",$id," This will delete all the comments for the news post $temp[title] and update the appropriate user post counts..","","prune");
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "maintain_news_c_kill":

  verifyid("news_news",$id);

  unset($postcounts);

  $getdata = query("SELECT userid FROM news_comment WHERE newsid = $id");
  while ($data_arr = fetch_array($getdata)) {
    if ($data_arr[userid]) {
      $postcounts[$data_arr[userid]] ++;
    }
  }

  query("DELETE FROM news_comment WHERE newsid = $id");
  query("UPDATE news_news SET commentcount = '0' , lastcommentuser = '' WHERE id = $id");

  if ($postcounts) {
    foreach ($postcounts AS $key => $val) {
      query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'$val' WHERE $foruminfo[userid_field] = $key");
    }
  }

  writeallpages();

  echoadminredirect("admin.php?action=maintain");
  exit;

break;

case "maintain_news":

  foreach ($cat_arr AS $key => $val) {
    $checkboxes .= returncheckboxcode("categories[$key]",1,$val[name]);
  }

  echohtmlheader();
  echoformheader("maintain_news_kill","Prune news posts");
  echotabledescription("Use this to remove old news posts and their comments from the database older than the number of days you specify");
  echotablerow("Prune from which categories:","\n$checkboxes    ");
  echoinputcode("Prune posts older than: <span class=\"red\">(in days)</span>","num_days");
  echoyesnocode("Update User Post Counts:","updatepostcounts",1);
  echoformfooter();
  echohtmlfooter();

break;

case "maintain_news_kill":

  settype($num_days,"integer");

  if (!$num_days) {
    adminerror("Invalid Data Type","The number of days must be a valid integer");
  }

  unset($cat_ids);
  unset($news_ids);
  unset($staff_ids);
  unset($user_ids);
  unset($cat_posts);

  foreach ($cat_arr AS $key => $val) {
    if ($categories[$key]) {
      $cat_ids[] = $key;
    }
  }

  if ($cat_ids) {

    $getdata = query("SELECT id,catid,staffid FROM news_news WHERE (time < ".(time()-($num_days*86400)).") AND (catid IN (".join(",",$cat_ids)."))");

    while ($data_arr = fetch_array($getdata)) {
      $news_ids[] = $data_arr[id];
      $cat_posts[$data_arr[catid]] ++;

      if ($updatepostcounts) {

        $staff_ids[$data_arr[staffid]] ++;

        $getcomments = query("SELECT COUNT(userid) AS count,userid FROM news_comment WHERE (newsid = $data_arr[id]) AND (userid <> 0) GROUP BY (userid)");

        while ($comment_arr = fetch_array($getcomments)) {
          $user_ids[$comment_arr[userid]] += $comment_arr[count];
        }
      }
    }
  }

  if ($news_ids) {
    query("DELETE FROM news_news WHERE id IN (".join(",",$news_ids).")");
    query("DELETE FROM news_comment WHERE newsid IN (".join(",",$news_ids).")");

    foreach ($cat_posts AS $key => $val) {
      query("UPDATE news_category SET posts = posts-'$val' WHERE id = '$key'");
    }

    if ($staff_ids) {
      foreach ($staff_ids AS $key => $val) {
        query("UPDATE news_staff SET newsposts = newsposts-'$val' WHERE id = '$key'");
      }
    }

    if ($user_ids) {
      foreach ($user_ids AS $key => $val) {
        query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] - '$val' WHERE $foruminfo[userid_field] = $key");
      }
    }

  }

  writeallpages();

  echoadminredirect("admin.php?action=maintain");
  exit;

break;

case "maintain_backup":

  echohtmlheader("adminjs");
  echoformheader("maintain_backup_server","Backup Database To Server",2,0,"backup");
  echotabledescription("Backup the database and save the file loaclly to the path below on the server (path relative to VirtuaNews root directory)");
  echoinputcode("Path:","path","admin/db_backup_".date("d-m-Y").".sql",60);
  echoformfooter(2,"Backup");
  echoformheader("maintain_backup_file","Backup Database To File");
  echotabledescription("Backup the database and save the file loaclly to your computer");
  echotabledescription("<b>Select the tables to include in the backup:</b>");
  echotabledescription(returnlinkcode("Select All","javascript:ca()")." |".returnlinkcode("Deselect All","javascript:cn()"));

  $gettables = query("SHOW TABLES");
  while ($table_arr = fetch_array($gettables)) {
    echoyesnocode("$table_arr[0]:","include[$table_arr[0]]",1,"Yes","No",30);
  }

  echoformfooter(2,"Backup");
  echohtmlfooter();

break;

case "maintain_backup_server";

  if ($path == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (function_exists("set_time_limit") == 1) {
    set_time_limit(1200);
  }

  echohtmlheader();

  if ($file = @fopen($path,"w")) {

    @fwrite($file,"# $sitename Database Dump - Powered By VirtuaNews Version $version\n");
    @fwrite($file,"# Database Dump At ".date("H:i m-d-Y",time()-$timeoffset)." GMT\n\n");

    $gettables = query("SHOW TABLES");
    while ($table_arr = fetch_array($gettables)) {
      @fwrite($file,dumptable($table_arr[0]));
      echo "Dumping Table: $table_arr[0]<br />\n";
    }

    echo "<br />\n";

    echo "Database dumped correctly to $path on the server";

  } else {
    adminerror("Write Error","there has been an error writing to the location you specified, please ensure that the file permissions are set correctly to allow writing to at that location.");
  }

  @fclose($file);
  echohtmlfooter();

break;

case "maintain_backup_file":

  if (function_exists("set_time_limit") == 1) {
    set_time_limit(1200);
  }

  header("Content-disposition: filename=virtuanews_db.sql");
  header("Content-type: unknown/unknown");

  echo "# $sitename Database Dump - Powered By VirtuaNews Version $version\n";
  echo "# Database Dump At ".date("H:i m-d-Y")." GMT\n\n";

  $gettables = query("SHOW TABLES");
  while ($table_arr = fetch_array($gettables)) {
    if ($include[$table_arr[0]]) {
      echo dumptable($table_arr[0]);
    }
  }
  exit;

break;

default:
    adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/maintain.php
|| ####################################################################
\*======================================================================*/

?>