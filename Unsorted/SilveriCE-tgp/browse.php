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
  $query = "SELECT * from submitted";
  $result = mysql_db_query ($dbname, $query);


if ($result){
    echo $start;
  echo "<table bgcolor=\"#cccccc\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
  echo "<tr><td><font color=\"#111111\">URL</td><td><font color=\"#111111\">Email</td><td><font color=\"#111111\">Name</td><td><font color=\"#111111\">Pics</td><td><font color=\"#111111\">Category</td><td><font color=\"#111111\">Date</td><td><font color=\"#111111\">Accept?</td><td><font color=\"#111111\">decline?</td></tr>";

  $numOfRows = mysql_num_rows ($result);
  for ($i = 0; $i < $numOfRows; $i++){
    $id = mysql_result ($result, $i, "id");
    $url = mysql_result ($result, $i, "url");
    $email = mysql_result ($result, $i, "email");
    $name = mysql_result ($result, $i, "name");
    $pics = mysql_result ($result, $i, "pics");
    $category = mysql_result ($result, $i, "category");
    $today = mysql_result ($result, $i, "date");
    $complete_url = "<a href=\"$url\" target=\"_blank\">".$url."</a>";
echo "<tr><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$complete_url</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$email</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$name</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$pics</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$category</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\">$today</td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\"><a href=\"modify.php?submit=true&id=$id&action=accept\">X</a></td><td bgcolor=\"#b5b5b5\"><font color=\"#111111\"><a href=\"modify.php?submit=true&id=$id&action=decline\">X</a></td></tr>\n";
  }
echo "</table>";
echo $end;
}
else{
  echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
}




?>
