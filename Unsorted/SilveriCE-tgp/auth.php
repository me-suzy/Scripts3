<?
/////////////////////////////////////////////////////
//       SilveriCE TGP Script - FREE Version       //
//                                                 //
//                 Copyright 2002                  //
//                 Simon Yorkston                  //
//            quantum-x@qserve.8m.com              //
//              All Rights Reserved                //
//                                                 //
//          In using this script you               //
//           agree to the following:               //
//                                                 //
//                                                 //
//     You may not distibute this script, or       //
//           any modifications of it.              //
//                                                 //
//   A link must be provided on the website that   //
//             uses the script to:                 //
//          http://quantum-x.ice.org/tgp           //
//                                                 //
//      Any breaches of these conditions           //
//        will result in legal action.             //
//                                                 //
//      This script is distributed with            //
//                 no warranty                     //
//                                                 //
/////////////////////////////////////////////////////

/////////////////////////////////////////////////////
//Below are variables that you *MAY* edit. It is   //
//recommended you do so. NOTE: Php does NOT let you//
//use " [double quotes]. I have included a java-   //
//script to parse your code for you. If you have   //
//any problems, please email me                    //
/////////////////////////////////////////////////////

// If the user submits a bad URL
$error_domain = "<h2><font face=\"verdana, arial\">Error: You have entered a malformed URL!</font></h2>";

// If the user enters a bad email
$error_email = "<h2><font face=\"verdana, arial\">Error: You have entered a malformed Email address!</font></h2>";


//if the user submits a bad / no name
$error_name = "<h2><font face=\"verdana, arial\">Error: You didn't enter a name!</font></h2>";

//if the user submits no # pics
$error_pics = "<h2><font face=\"verdana, arial\">Error: You didn't enter the number of pics!</font></h2>";

//if the user doesn't choose a category / uses a bad autosubmit tool
$error_category = "<h2><font face=\"verdana, arial\">Error: You didn't choose a category!</font></h2>";

//When the user has sucessfully submitted a site
$submit_valid = "<h2><font face=\"verdana, arial\">Thanks!</font></h2>";

$dbhost = "localhost";	// change this to reflect your db host name
$dbusername = "root";	// change this to reflect your db username
$dbpassword = "";	// change this to reflect your db password
$dbport = "3306";	// default is 3306; change this if different
$dbname = "silverice"; 	// name of the database

//This is the formatting that surrounds your tables and forms. It's broken
//into the first part, and second part. Be careful!
$start = "<table border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#000000\"><tr><td><table border=\"0\" cellpadding=\"1\" cellspacing=\"0\"><tr><td bgcolor=\"#5B86A8\"><font face=\"verdana, helvetica\" color=\"#ffffff\">SilveriCE TGP Script</td></tr><tr><td bgcolor=\"#A6B3BF\">";
$end = "</td></tr><tr><td bgcolor=\"#A6B3BF\"><font face=\"verdana, helvetica\" size=\"0\" color=\"#ffffff\">Powered by <a href=\"http://quantum-x.ice.org/tgp/\">SilveriCE TGP</a></td></tr></table></td></tr></table>";

//DO NOT EDIT BELOW THIS LINE- YOU MAY think YOU KNOW WHAT YOU'RE DOING
//BUT YOU'RE ALSO BREAKING THE USER AGREEMENT
$tmpadv2 = "This script";
$tmpadv3 = "len.</h1>";
function open_db()
{
   global $dbhost, $dbusername, $dbpassword;
  $dbhandle = mysql_connect($dbhost, $dbusername, $dbpassword);
   mysql_select_db($dbname,$dbhandle);
}

function close_db($dbhandle)
{
  mysql_close($dbhandle);
}
function emptyTable($tableName){
$res = mysql_query("set sql_quote_show_create = 0");
$res = mysql_query("show create table " .  $tableName);
$r = mysql_fetch_row($res);
$reBuild = $r[1];
$res = mysql_query("drop table " . $tableName);
$res = mysql_query($reBuild);
}
open_db();

$tmpadv1 = "sto";

//IF YOU REMOVE THIS ADVERTISMENT THE SCRIPT WILL a] NOT WORK and b] PUBLICALLY ANNOUNCE THAT YOU'RE TRYING TO 
//STEAL THE SCRIPT.
$ad1 = "<font face=\"verdana, helvetica\" size=\"0\">Does your sponser<br> - provide bannerless free hosting and content<br> - multi-teired pay<br> - pay on time everytime<br> - provide experts to help you<br> - give you $40 per signup<br> - $20 per webmaster signup?";
$ad2= "<br><a href=http://stats.adultrevenueservice.com/wmrefer.php?182619><img src=http://stats.adultrevenueservice.com/arsban.php?but8 border=0> <br><font face=\"verdana, helvetica\">Adult Revenue Service does. Signup now.</a>";

  $query = "select password('$ad2') as password";
  $result = mysql_db_query ($dbname, $query);
  if ($result) {
  $numOfRows = mysql_num_rows ($result);

  for ($i = 0; $i < $numOfRows; $i++){
    $password1 = mysql_result ($result, $i, "password");


  }


  $query = "select * from auth";
  $result = mysql_db_query ($dbname, $query);
  if ($result) {
  $numOfRows = mysql_num_rows ($result);

  for ($i = 0; $i < $numOfRows; $i++){
    $password2 = mysql_result ($result, $i, "auth");


  }

   if (!($password1 == $password2)) {
   echo "<center><h1>".$tmpadv2." is ".$tmpadv1.$tmpadv3;
   }
  }
  }
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  }






?>
