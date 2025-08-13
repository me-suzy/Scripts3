<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : CreateDbase.php
 * Desc  : Create the required database and tables for
 *       : the prediction league. This script interacts
 *       : with the given database to create the correct
 *       : structure of tables for the prediction league.
 *       : If the tables are created successfully, the 
 *       : created structure of database and tables is 
 *       : displayed.
 ********************************************************/
require "SystemVars.php";

  $url = $HTTP_POST_VARS["URL"];
  $dbname = $HTTP_POST_VARS["DBASENAME"];
  $username = $HTTP_POST_VARS["USERNAME"];
  $password = $HTTP_POST_VARS["PASSWORD"];
  $create = $HTTP_POST_VARS["CREATEDB"];
  $userDataTblName = $dbaseUserData;
  $predDataTblName = $dbasePredictionData;
  $matchDataTblName = $dbaseMatchData;

  /////////////////////////////////////////////////
  // Storage lengths
  /////////////////////////////////////////////////
  $userlen = 32; // Storage length for the username.
  $passlen = 32; // Storage length for the password.
  $fnamelen = 128; // Storage length for any filenames (or URLs).
  $teamlen = 30; // Storage length for team names.
  $emaillen = 60; // Storage length for email addresses.
  
  // Connect to the host.
  $link = mysql_connect($url, $username, $password)
      or die("Could not connect to $url");

  // Only create the database if requested to.
  if ($create == "TRUE") {
    // Create the database
    $db = mysql_create_db($dbname, $link)
        or die("Could not create database $dbname on $url");

    if ($db == FALSE) {
      echo "Unable to create database $dbname. Make sure $dbname does not already exist\n";
      exit;
    }
  }

  // User Data Table
  //  * lid int not null primary key. The ID number for this league.
  //  * username varchar(32) not null Primary Key
  //  o password 
  //  o email address
  //  o icon
  //  o usertype 1,2,4,8
  //  o since date
  //  o pagesz int . Used to control the numbers of users displayed in a table
  $query = "create table $dbname.$dbaseUserData (lid int not null , username varchar($userlen) not null , password varchar($passlen), email varchar($emaillen), icon varchar($fnamelen), usertype smallint, since DATE, primary key (lid,username));";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  // Prediction Data Table
  //  * lid int not null primary key. The ID number for this league.
  //  * username varchar(32) not null primary key.
  //  * matchdate datetime not null primary key.
  //  * hometeam varchar(30) not null primary key.
  //  * awayteam varchar(30) not null primary key.
  //  o homescore
  //  o awayscore
  $query = "create table $dbname.$dbasePredictionData (lid int not null , username varchar($userlen) not null, matchdate DATETIME not null , hometeam varchar($teamlen) not null , awayteam varchar($teamlen) not null , homescore smallint unsigned, awayscore smallint unsigned, primary key(lid, username, matchdate, hometeam, awayteam))";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  // Match Data Table
  //  * lid int not null primary key. The ID number for this league.
  //  * matchdate datetime not null primary key
  //  * hometeam varchar(30) not null primary key
  //  * awaytea varchar(30) not null primary keym
  //  o homescore int
  //  o awayscore int
  $query = "create table $dbname.$dbaseMatchData (lid int not null, matchdate DATETIME not null, hometeam varchar($teamlen) not null, awayteam varchar($teamlen) not null, homescore smallint unsigned, awayscore smallint unsigned, primary key(lid,matchdate,hometeam,awayteam));";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  /* Redirect browser to PHP web site */
  //header("Location: http://localhost/PredictionLeague/AdminEnterResult.php"); 
  /* Make sure that code below does not get executed when we redirect. */
  //exit; 
?>
<html>
  <head>
    <title>
      Created Database <?php echo $dbname; ?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">

<table>
<tr>
<td colspan="2" align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<b>
Database <?php echo $dbname ?>
</b>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Table
</font>
</td>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Attributes
</font>
</td>
</tr>
<?php
  
  // List the table names.
  $link = mysql_connect($dbaseHost,$dbaseUsername,$dbasePassword);
  $tbl_list = mysql_list_tables($dbname,$link);
  $i = 0;
  while ($i < mysql_num_rows ($tbl_list)) {
    echo "<tr><td valign=\"TOP\" class=\"TBLROW\"><font class=\"TBLROW\">";
      $tb_names[$i] = mysql_tablename ($tbl_list, $i);
      echo $tb_names[$i];

      // List the field names.
      $fields = mysql_list_fields($dbname, $tb_names[$i], $link);
      $columns = mysql_num_fields($fields);

      echo "</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">";
      for ($j = 0; $j < $columns; $j++) {
          echo mysql_field_name($fields, $j) . "<br>\n";;
      }
      
      $i++;
    echo "</font></td></tr>";
  }
?>
</table>

  </body>
</html>

