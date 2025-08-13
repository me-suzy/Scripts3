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

if ($submit){


  $query = "SELECT * from submitted where id=$id";
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
    $today = mysql_result ($result, $i, "date");

  }
}
else{
  echo mysql_errno().": ".mysql_error()."<BR>";
}

                    if ($action=="accept") {
			  $query = "insert into accepted values ('$id','$url','$email','$name','$pics','$category')";
			  $result = mysql_db_query ($dbname, $query);
			  if ($result) {
			  $query = "delete from submitted where id=$id";
			  $result = mysql_db_query ($dbname, $query);
                    echo $start;
			  echo "<h2><font face=\"verdana, arial\">Accepted</font></h2>";  
			  echo "<meta http-equiv=\"refresh\" content=\"0;URL=browse.php\">"; 
                    echo $end;
			  }
			  else {
			    echo mysql_errno().": ".mysql_error()."<BR> ";
			  echo $end;
			  }
                                          }
                    elseif ($action=="decline") {
			  $query = "delete from submitted where id=$id";
     		        $result = mysql_db_query ($dbname, $query);
			  echo $start;
			  echo "<h2><font face=\"verdana, arial\">Declined</font></h2>";  
			  echo "<meta http-equiv=\"refresh\" content=\"0;URL=browse.php\">"; 
			  echo $end;

                                                }


}
elseif ($blacklist) {
  echo $start;
  $query = "DELETE from blacklist where domain='$domain'";
  $result = mysql_db_query ($dbname, $query);
if ($result){
	  echo "<h2><font face=\"verdana, arial\">Removed $domain from blacklist</font></h2>";  
	  echo "<meta http-equiv=\"refresh\" content=\"0;URL=blacklist.php\">"; 
  echo $end;  
}
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  echo $end;
  }

}
?>