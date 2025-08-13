<?php
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


  require 'auth.php';
?>
<HTML>
<HEAD>
<?php
if ($submit){
  $query = "insert into blacklist values ('$banned_domain')";
  $result = mysql_db_query ($dbname, $query);
  if ($result) {
  echo $start;
  echo "<h2><font face=\"verdana, arial\">Added $banned_domain to blacklist</font></h2>";  
  echo "<meta http-equiv=\"refresh\" content=\"0;URL=blacklist.php\">";
  echo $end;
  }
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
  }

}
else {
  $query = "SELECT * from blacklist";
  $result = mysql_db_query ($dbname, $query);


if ($result){
  echo $start;
  echo "<center>";
  echo "<table bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
  echo "<tr><td><font color=\"#111111\">Domain</td><td><font color=\"#111111\">remove?</td></tr>";

  $numOfRows = mysql_num_rows ($result);
  for ($i = 0; $i < $numOfRows; $i++){
    $domain = mysql_result ($result, $i, "domain");
  
echo "<tr><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$domain</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\"><a href=\"modify.php?blacklist=true&domain=$domain\">X</a></td></tr>\n";
  }
echo "</table>";
echo "<form method=\"post\" action=\"blacklist.php\">
    <input type=\"text\" name=\"banned_domain\" value=\"domain\">
    <input type=\"submit\" name=\"submit\" value=\"submit\">
    </form>
    </center>";
  echo $end;
}
else{
  echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
}
}



?>
