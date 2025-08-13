<?

include ("config.php");

$dbLink = @mysql_connect($dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase);
                   $sql="Select * FROM jobnum ORDER BY recordid";
                   $result = mysql_query ($sql,$dbLink) or die( "problem getting job list");

                     While ($row = mysql_Fetch_array($result)){
                    $user=$row['username'];
                    $pass=$row['password'];
                    }
                   mysql_close();


$o=0;
if ($username==$user){
$o=$o+1;
}
if ($password==$pass){
$o=$o+1;
}


if ($o==2){

echo "\n";
echo "<html>\n";
echo "\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
echo "<meta name=\"GENERATOR\" content=\"Microsoft FrontPage 4.0\">\n";
echo "<meta name=\"ProgId\" content=\"FrontPage.Editor.Document\">\n";
echo "<title>$jobtitle</title>\n";
echo "</head>\n";
echo "\n";
echo "<body>\n";
echo "\n";
echo "<p align=\"center\"><font face=\"Arial Black\" size=\"3\" color=\"#65AEDA\">$jobtitle</font></p>\n";
echo "\n";
echo "<center>\n";
echo "<table borderColor=\"#ffffcc\" borderColorDark=\"#000000\" width=\"700\" bgColor=\"#65aeda\" border=\"1\">\n";
echo "  <tbody>\n";
echo "    <tr>\n";
echo "      <td vAlign=\"center\" align=\"middle\">\n";
echo "        <p align=\"center\"><font face=\"Arial\" size=\"2\"><b>Go to Listing</b></font></p>\n";
echo "      </td>\n";
echo "      <td align=\"middle\" width=\"133\">\n";
echo "        <p align=\"center\"><b><font face=\"Arial\" size=\"2\">Job Name</font></b></p>\n";
echo "      </td>\n";
echo "      <td align=\"middle\" width=\"167\"><b><font face=\"Arial\" size=\"2\">Company Name</font></b></td>\n";
echo "      <td align=\"middle\" width=\"202\"><b><font face=\"Arial\" size=\"2\">Location</font></b></td>\n";
echo "      <td align=\"middle\" width=\"98\"><b><font face=\"Arial\" size=\"2\">Date Post</font></b></td>\n";
echo "    </tr>\n";
echo "  </center>\n";



$dbLink = @mysql_connect($dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase );
                   $sql="Select * FROM jobs ORDER BY jobid";
                   $result = mysql_query ($sql,$dbLink) or die( "problem loading data");


                   While ($row = mysql_Fetch_array($result)){
                    $currentjobid=$row['jobid'];
                    $title=$row['title'];
                    $company=$row['company'];
                    $location=$row['location'];
                    $description=$row['description'];
                    $contact=$row['contact'];
                    $email=$row['email'];
                    $url=$row['url'];
                    $publishdate=$row['publishdate'];
                    stripslashes($description);




echo "  <tr>\n";
echo "    <td>\n";
echo "      <p align=\"center\"><font face=\"Arial\" size=\"1\"><b><a href=\"viewjob.php?currentjobid=$currentjobid\" target=\"_blank\">view job</a></b></font></td>\n";
echo "    <td vAlign=\"top\" align=\"middle\" width=\"133\" height=\"10\"><b><font face=\"Arial\" size=\"1\">$title</font></b></td>\n";
echo "    <td vAlign=\"top\" align=\"middle\" width=\"167\" height=\"10\">\n";
echo "      <p align=\"left\"><b><font face=\"Arial\" size=\"1\">$company</font></b></p>\n";
echo "    </td>\n";
echo "    <td vAlign=\"top\" align=\"middle\" width=\"202\" height=\"10\">\n";
echo "      <p align=\"left\"><b><font face=\"Arial\" size=\"1\">$location</font></b></p>\n";
echo "    </td>\n";
echo "    <td vAlign=\"top\" align=\"middle\" width=\"98\" height=\"10\">\n";
echo "      <p align=\"center\"><b><font face=\"Arial\" size=\"1\">$publishdate</font></b></p>\n";
echo "    </td>\n";
echo "  </tr>\n";



}





echo "</tbody>\n";
echo "</table>\n";
echo "\n";
echo "</body>\n";
echo "\n";
echo "</html>\n";
}else{
echo "The username or password was not correct!!!";
}

?>