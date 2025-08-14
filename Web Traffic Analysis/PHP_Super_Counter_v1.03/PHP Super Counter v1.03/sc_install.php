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


include("sc_config.php");

$global_dbh = mysql_connect($SQL_HOST, $SQL_USER, $SQL_PWD)
or die(mysql_error());
mysql_select_db($SQL_DB, $global_dbh) or die(mysql_error());

function add_new_data($dbh, $generaltable, $subtable1,
$subtable2, $data1, $data2)
{
  $db_query =
    "insert into $generaltable ($subtable1, $subtable2)
     values ('$data1', '$data2')";
  $result_id =  mysql_query($db_query)
                OR die($country_query . mysql_error());
}

function create_db($dbh) {

/* create the tables */
mysql_query("create table SC_COUNTER (
SC_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
SC_REFERENCE TEXT NOT NULL,
SC_NAME TEXT NOT NULL,
SC_COUNTER INT NOT NULL,
SC_SINCE INT(10) NOT NULL)",
$dbh )
OR die(mysql_error());

mysql_query("create table SC_LOG (
SC_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
SC_TIMESTAMP INT(10) NOT NULL,
SC_IP CHAR(15) NOT NULL,
SC_HOST CHAR(100) NOT NULL,
SC_BROWSER CHAR(100) NOT NULL,
SC_OS CHAR(100) NOT NULL,
SC_LANGUAGE CHAR(5) NOT NULL,
SC_REFFERER CHAR(100) NOT NULL,
SC_PAGE CHAR(100) NOT NULL)",
$dbh )
OR die(mysql_error());

mysql_query("create table SC_REFERENCE (
SC_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
SC_CODE TEXT NOT NULL,
SC_NAME TEXT NOT NULL)",
$dbh )
OR die(mysql_error());

mysql_query("create table SC_BLOCKIPS (
SC_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
SC_IP TEXT NOT NULL)",
$dbh )
OR die(mysql_error());

mysql_query("create table SC_STATS (
SC_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
SC_CODE TEXT NOT NULL,
SC_NAME TEXT NOT NULL,
SC_YEAR INT(10) NOT NULL,
SC_COUNTER TEXT NOT NULL)",
$dbh )
OR die(mysql_error());

/* store data in the tables */

add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'Unknown', 'Unknown');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'nl', 'Netherlands');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-gb', 'United Kingdom');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'uk', 'United Kingdom');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-us', 'United States');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'el', 'Estland');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'sv', 'Sweden');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ru', 'Russia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'de', 'Germany');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en', 'United Kingdom');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ko', 'Kosovo');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es-ar', 'Argentina');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-ca', 'Canada');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fr', 'France');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'nl-be', 'Belgium');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es-mx', 'Mexico');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es', 'Spain');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'no', 'Norway');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'it', 'Italy');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fi', 'Finland');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-au', 'Australia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fr-be', 'Belgium');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'de-at', 'Austria');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'at', 'Austria');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'be', 'Belgium');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'pt', 'Puerto Rico');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ch', 'Switzerland');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 've', 'Venezuela');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'da', 'Denmark');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-nz', 'New zealand');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'nz', 'New zealand');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fr-ca', 'Canada');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ca', 'Canada');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'br', 'Brazil');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'pt-br', 'Brazil');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'zh-hk', 'Suriname');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'hk', 'Hongkong');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'th', 'Thailand');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es-cl', 'Chile');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'cl', 'Chile');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ja', 'Java');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'en-za', 'South Africa');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'za', 'South Africa');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'zh-tw', 'Taiwan');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'tw', 'Taiwan');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fr-ch', 'Switserland');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'tr', 'Turkey');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'zh-cn', 'China');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'cn', 'China');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'bg', 'Bulgaria');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ro', 'Romania');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'hu', 'Hungary');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'cs', 'Czechoslovakia (former)');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'eg', 'Egypt');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ar-eg', 'Egypt');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'de-de', 'Germany');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'ar-sa', 'Saudi Arabia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'sa', 'Saudi Arabia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'id', 'Indonesia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'lv', 'Latvia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'is', 'Iceland');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'hr', 'Croatia (Hrvatska)');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es-es', 'Spain');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'fd-fd', 'France');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'de-ch', 'Czechoslovakia (former)');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'sl', 'Sierra Leone');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'bo', 'Bolivia');
add_new_data($dbh, 'SC_REFERENCE', 'SC_CODE', 'SC_NAME', 'es-bo', 'Bolivia');

print("supercounter database created succesfully<BR>");
}
?>

<HTML><HEAD><TITLE>Creating SuperCounter database..</TITLE></HEAD>
<BODY>
<?php create_db($global_dbh); ?>
</BODY></HTML>
