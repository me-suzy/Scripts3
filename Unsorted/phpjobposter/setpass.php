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


$oldnum=$newjobid-1;


if ($t==2){
$dbLink = @mysql_connect( $dbasehost,$dbaseuser, $dbasepassword );
	mysql_select_db( $dbase );

                   $sql="UPDATE  jobnum SET username='$stuuser' WHERE recordid=$oldnum";
                   $result = mysql_query ($sql,$dbLink) or die( "Couldn't execute newjobid");


                   $sql="UPDATE  jobnum SET password='$stupass' WHERE recordid=$oldnum";
                   $result = mysql_query ($sql,$dbLink) or die( "Couldn't execute newjobid2");


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
echo "<p align=\"center\"><font face=\"Arial\">Are you sure you wish to change the password and username?</font></p>\n";
echo "<form method=\"POST\" action=\"$phpself\">\n";
echo "  <p align=\"center\"><font face=\"Arial\"><input type=\"radio\" value=\"yes\" name=\"yes\" checked>yes\n";
echo "  <input type=\"radio\" value=\"no\" name=\"yes\">no</font></p>\n";
echo "  <input type=\"hidden\" value=\"true\" name=\"sure\">\n";
echo "                        <input type=\"hidden\" name=\"newjobid\" value=\"$newjobid\" size=\"20\">\n";
echo "                        <input type=\"hidden\" name=\"stuuser\" value=\"$stuuser\" size=\"20\">\n";
echo "                        <input type=\"hidden\" name=\"stupass\" value=\"$stupass\" size=\"20\">\n";
echo "                        <input type=\"hidden\" name=\"username\" value=\"$username\" size=\"20\">\n";
echo "                        <input type=\"hidden\" name=\"password\" value=\"$password\" size=\"20\">\n";
echo "  <p align=\"center\"><font face=\"Arial\"><input type=\"submit\" value=\"Submit\" name=\"B1\"></font></p>\n";
echo "</form>\n";
echo "<p align=\"center\">&nbsp;</p>\n";
echo "\n";
echo "</body>\n";
echo "\n";
echo "</html>\n";
}
?>