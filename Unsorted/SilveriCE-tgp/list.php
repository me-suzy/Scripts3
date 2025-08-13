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

<?php
  $query = "SELECT * from published";
  $result = mysql_db_query ($dbname, $query);


if ($result){
  $numOfRows = mysql_num_rows ($result);
if (!($numOfRows==0)) {
  for ($i = 0; $i < $numOfRows; $i++){
    $id = mysql_result ($result, $i, "id");
    $url = mysql_result ($result, $i, "url");
    $email = mysql_result ($result, $i, "email");
    $name = mysql_result ($result, $i, "name");
    $pics = mysql_result ($result, $i, "pics");
    $category = mysql_result ($result, $i, "category");

//This is an important one. This is the url that will be published. You can leave it as it is,
//or if you need to add a traffic trade script, you have to add it here.
//READ THE README FOR MORE HELP
    $publish = $url;
    $url_format = "<a href=\"$publish\" target=\"_blank\">$pics of $category</a>";
  
    echo $url_format."<br>";

  }
 }
else { echo "You have not accepted any sites, or have not yet published sites";}

}
else{
  echo mysql_errno().": ".mysql_error()."<BR>";
}




?>
