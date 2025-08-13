<?

include("config.php");

$p=0;
$q=0;


if ($sure=="true"){
$p=$p+1;
}
if ($yes=="yes"){
$q=$q+1;
}


$t=$p+$q;



if ($t==2){
$month=date('m');
$day=date('d');
$year=date('y');
$newdate="$month/$day/$year";
$title=stripslashes($title);
$company=stripslashes($company);
$location=stripslashes($location);
$description=stripslashes($description);
$contact=stripslashes($contact);
$email=stripslashes($email);

$dbLink = @mysql_connect( $dbasehost,$dbaseuser, $dbasepassword );
	mysql_select_db( $dbase );
                   $sql = "INSERT INTO jobs (jobid, title, company, location, description, contact, email, url, publishdate)";
                   $sql .= " VALUES ('$newjobid', '$title','$company','$location', '$description','$contact','$email','$url','$newdate')";
                   $result = mysql_query ($sql,$dbLink) or die( "Couldn't execute add dbase");

$dbLink = @mysql_connect( $dbasehost,$dbaseuser, $dbasepassword );
	mysql_select_db( $dbase );
$oldnum=$newjobid-1;
                   $sql="UPDATE  jobnum SET recordid ='$newjobid' WHERE recordid=$oldnum";
                   $result = mysql_query ($sql,$dbLink) or die( "Couldn't execute newjobid");



                   header ("Location:  $folder/admin.php?username=$username&password=$password&passsubmitted=yes");
}

if ($t==1){
 header ("Location:  $folder/admin.php?username=$username&password=$password&passsubmitted=yes");
}


if ($t==0){
echo "\n";
echo "<html>\n";
echo "\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
echo "<meta name=\"GENERATOR\" content=\"Microsoft FrontPage 4.0\">\n";
echo "<meta name=\"ProgId\" content=\"FrontPage.Editor.Document\">\n";
echo "<title>New Page 2</title>\n";
echo "</head>\n";
echo "\n";
echo "<body>\n";
echo "\n";
echo "<p align=\"center\"><font face=\"Arial\">Are you sure you wish to add job $newjobid?</font></p>\n";
echo "<form method=\"POST\" action=\"$phpself\">\n";
echo "  <p align=\"center\"><font face=\"Arial\"><input type=\"radio\" value=\"yes\" name=\"yes\" checked>yes\n";
echo "  <input type=\"radio\" value=\"no\" name=\"yes\">no</font></p>\n";
echo "  <input type=\"hidden\" value=\"true\" name=\"sure\">\n";
echo "  <input type=\"hidden\" name=\"title\" value=\"$title\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"company\" value=\"$company\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"location\" value=\"$location\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"description\" value=\"$description\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"contact\" value=\"$contact\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"email\" value=\"$email\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"currentjobid\" value=\"$currentjobid\" size=\"20\">\n";
echo " <input type=\"hidden\" name=\"url\" value=\"$url\" size=\"20\">  \n";
echo "<input type=\"hidden\" name=\"newjobid\" value=\"$newjobid\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"username\" value=\"$username\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"password\" value=\"$password\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"publishdate\" value=\"$publishdate\" size=\"20\">\n";
echo "  <p align=\"center\"><font face=\"Arial\"><input type=\"submit\" value=\"Submit\" name=\"B1\"></font></p>\n";
echo "</form>\n";
echo "<p align=\"center\">&nbsp;</p>\n";
echo "\n";
echo "</body>\n";
echo "\n";
echo "</html>\n";
}
?>