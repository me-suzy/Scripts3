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
  echo $start;
if ($action=="yes"){
emptyTable(published);

  $query = "SELECT * from accepted";
  $result = mysql_db_query ($dbname, $query);


if ($result){
  $numOfRows = mysql_num_rows ($result);
  for ($i = 0; $i < $numOfRows; $i++){
    $id = mysql_result ($result, $i, "id");
    $url = mysql_result ($result, $i, "url");
    $email = mysql_result ($result, $i, "email");
    $name = mysql_result ($result, $i, "name");
    $pics = mysql_result ($result, $i, "pics");
    $category = mysql_result ($result, $i, "category");


  $query = "insert into published values ('$id','$name','$email','$url','$pics','$category')";
  $result2 = mysql_db_query ($dbname, $query);
  if ($result2) {
  echo "<font face=\"verdana, arial\">Site #$id [$url] added<br></font>";  

  }
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
  }


  
  }
emptyTable(accepted);
emptyTable(submitted);

echo "<br><font face=\"verdana, arial\" size=\"+2\">Done! All sites are now <a href=\"$tgp\">live</a>!</font>";
  echo $end;
}
else{
  echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
}
}
elseif ($action=="no") {echo "<meta http-equiv=\"refresh\" content=\"0;URL=admin.php\">"; }

}
else {
echo $start;
echo "<center><h1>Publish all accepted sites?</h1>
<form method=\"post\" action=\"publish.php\">
<input type=\"radio\" name=\"action\" value=\"no\" checked>No<br>
<input type=\"radio\" name=\"action\" value=\"yes\">Yes<br>
<input type=\"submit\" name=\"submit\" value=\"submit\">
</form>";
  echo $end;
}



?>
