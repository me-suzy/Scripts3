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
  echo "$start";
  if (!(ereg("^http",$url))) {
  echo "$error_domain";
  $error="TRUE";
  echo "$end";
  }
  elseif (!(ereg( '^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email) )) {
  echo "$error_email";
  $error="TRUE";
  echo "$end";
  }
  elseif (!($name)) {
  echo "$error_name";
  $error="TRUE";
  echo "$end";
  }
  elseif (!($pics)) {
  echo "$error_pics";
  $error="TRUE";
  echo "$end";
  }
  elseif ($category=="ignore") {
  echo "$error_category";
  $error="TRUE";
  echo "$end";
  }
  else {

  $query = "SELECT * from blacklist";
  $result = mysql_db_query ($dbname, $query);
$tmp_email = $email;
if ($result){
  $numOfRows = mysql_num_rows ($result);
  for ($i = 0; $i < $numOfRows; $i++){
    $banned_domain = mysql_result ($result, $i, "domain");
 
if ((ereg($banned_domain,$url))) {
  echo "<h2><font face=\"verdana, arial\">Error: BlackListed Domain!</font></h2>";
  $error="TRUE";
   echo "$end";

}
elseif ((ereg($banned_domain,$tmp_email))) {
  echo "<h2><font face=\"verdana, arial\">Error: BlackListed Email</font></h2>";
  $error="TRUE";
  echo "$end";

}
else {
$email = $tmp_email;
}

  }
}

}
if (!($error)) {
  $today = date("F j"); 
  $query = "insert into submitted values ('$id','$url','$email','$name','$pics','$category','$today')";
  $result = mysql_db_query ($dbname, $query);
  if ($result) {
  echo "$submit_valid";  
  echo $ad1;
  echo $ad2;
  
  }
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  }


}
else { }


}
else{
  echo "$start";
  echo "
    <form method=\"post\" action=\"submit.php\">
    <input type=\"text\" name=\"url\" value=\"url\"><br>
    <input type=\"text\" name=\"email\" value=\"email\"><br>
    <input type=\"text\" name=\"name\" value=\"name\"><br>
    <input type=\"text\" name=\"pics\" value=\"pics #\"><br>
    <select name=\"category\">
                    <option value=\"ignore\">Category</option>
                    <option value=\"Amateurs\">Amateurs </option>
                    <option value=\"Anal Sex\">Anal Sex </option>
                    <option value=\"Asian\">Asian </option>
                    <option value=\"Ass/Pussy\">Ass/Pussy </option>
                    <option value=\"Babes\">Babes </option>
                    <option value=\"BDSM/Femdom\">BDSM/Femdom </option>
                    <option value=\"Bizzare\">Bizzare </option>
                    <option value=\"Blowjobs\">Blowjobs </option>
                    <option value=\"Cartoons\">Cartoons </option>
                    <option value=\"Celebreties\">Celebreties </option>
                    <option value=\"Cumshots\">Cumshots </option>
                    <option value=\"Ebony\">Ebony </option>
                    <option value=\"Exhibitionism\">Exhibitionism </option>
                    <option value=\"Fetish\">Fetish </option>
                    <option value=\"Groupsex\">Groupsex </option>
                    <option value=\"Hardcore\">Hardcore </option>
                    <option value=\"ndians\">Indians </option>
                    <option value=\"Interracial\">Interracial </option>
                    <option value=\"Latinas\">Latinas </option>
                    <option value=\"Lesbians\">Lesbians </option>
                    <option value=\"Mature\">Mature </option>
                    <option value=\"Movies\">Movies </option>
                    <option value=\"Panty/Stocking\">Panty/Stocking </option>
                    <option value=\"Plumpers\">Plumpers </option>
                    <option value=\"Pornstars\">Pornstars </option>
                    <option value=\"Teens\">Teens </option>
                    <option value=\"Tits\">Tits </option>
                    <option value=\"Toys\">Toys </option>
                    <option value=\"Transexual\">Transexual </option>
                    <option value=\"Uniform\">Uniform </option>
                    <option value=\"Variety\">Variety </option>
                    <option value=\"Voyeur/Upskirt\">Voyeur/Upskirt</option>
                    <option value=\"Watersports\">Watersports</option>
                  </select>
    <input type=\"submit\" name=\"submit\" value=\"submit\">
    </form>
    </body></html>
  ";
  echo "$end";

}

?> 
