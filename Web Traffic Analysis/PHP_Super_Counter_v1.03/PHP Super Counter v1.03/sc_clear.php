<?php

/*
***************************************************
***************************************************
*          :: PHP Super Counter v1.03 ::          *
* Coded by Roel S.F. Abspoel (roel@abspoel.com)   *
***************************************************
*    Magtrb.com  13/11/05 21:12
*http://www.magtrb.com/Scripts/pafiledb.php?action=category&id=77
* you can post any new ideas or comments at
*http://www.magtrb.com/Invision/index.php?s=&act=SF&f=9
* no need for registration to post just post directlly in english.
***************************************************     */


include_once "sc_config.php";

if (isset($List_Password) && empty($List_Password) == false) {
  if ($List_Password == $Password) {
    if ($QUERY_STRING == "test") {
      testaction();
    }
    else {
      cleartables();
   }
  }
  else {
    print_ask_pw();
    echo "<div align=center><b>Password is not correct..</b></div>";
  }
}
else {
  print_ask_pw();
}


function print_ask_pw()
{
echo "
<style type=text/css>
<!--
input {  border: 2 solid #999999; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}
textarea {  border: 2 solid #999999; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; color: #006699; font-weight: bold}
-->
</style><div align=center>
<table width=50% border=1 cellspacing=0 cellpadding=0 bordercolor=#CCCCCC>
<tr><td><div align=center><font size=4><b>Super Counter :: Clear counters</b></font></div></td></tr><tr><td>
<div align=center><br>
<form name=Email method=POST action=$_SERVER[PHP_SELF]>
Password: <input type=password name=List_Password size=35>
<input type=submit name=\"Email_submit\" value=\"Login\">
</form></div></td></tr></table></div>
";

}

function cleartables()
{

  $link = mysql_connect($GLOBALS[SQL_HOST], $GLOBALS[SQL_USER], $GLOBALS[SQL_PWD])
          or die(mysql_error());

  mysql_select_db($GLOBALS[SQL_DB])
  or die(mysql_error());
  $query ="DELETE FROM $GLOBALS[SQL_COUNTTABLE]";
  $result = mysql_query($query)
            or die(mysql_error());
  echo "$GLOBALS[SQL_COUNTTABLE] has been cleared.<br>";

  $query ="DELETE FROM $GLOBALS[SQL_LOGTABLE]";
  $result = mysql_query($query)
            or die(mysql_error());
  echo "$GLOBALS[SQL_LOGTABLE] has been cleared.<br>";

    $query ="DELETE FROM $GLOBALS[SQL_STATSTABLE]";
    $result = mysql_query($query)
              or die(mysql_error());
  echo "$GLOBALS[SQL_STATSTABLE] has been cleared.<br>";
  echo "Tables have been cleared. All counters are now reset.";
}


?>
