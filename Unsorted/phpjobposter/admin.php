<?


include ("config.php");

if ($passsubmitted=="yes"){
    if ($password==$adminpass){
        $p=2;
    }else{
        $p=1;
        $pasmes="You have entered the wrong password.";
    }
     if ($username==$adminuser){
        $u=2;
    }else{
        $u=1;
        $pasmes="You have entered the wrong username.";
    }
}else{
   $p=0;
   $u=0;
   $pasmes="";
}

$pu=$p+$u;

if($pu<4){
echo "\n";
echo "<html>\n";
echo "\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
echo "<meta name=\"GENERATOR\" content=\"Microsoft FrontPage 4.0\">\n";
echo "<meta name=\"ProgId\" content=\"FrontPage.Editor.Document\">\n";
echo "<title>$jobtitle Admin Page</title>\n";
echo "</head>\n";
echo "\n";
echo "<body>\n";
echo "\n";
echo "<div align=\"center\">\n";
echo "  <center>\n";
echo "  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\" bgcolor=\"$maincolor\">\n";
echo "    <tr>\n";
echo "      <td>\n";
echo "        <div align=\"left\">\n";
echo "          <table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"696\">\n";
echo "            <tr>\n";
echo "              <td width=\"690\" height=\"5\">\n";
echo "                <div align=\"center\">\n";
echo "                  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"$headercolor\">\n";
echo "                    <tr>\n";
echo "                      <td width=\"100%\">\n";
echo "                        <div align=\"center\">\n";
echo "                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"$headercolor\">\n";
echo "                          <tr>\n";
echo "                            <td width=\"550\" valign=\"top\" bgcolor=\"$headercolor\">\n";
echo "                              <p align=\"center\"><font face=\"Arial\" color=\"$titlefontcolor\"><b>$jobtitle Admin Page</b></font></td>\n";
echo "                          </tr>\n";
echo "                          <tr>\n";
echo "                            <td width=\"550\" valign=\"top\" bgcolor=\"$headercolor\">\n";
echo "                              <div align=\"center\">\n";
echo "                                <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\" bgcolor=\"$headercolor\">\n";
echo "                                  <tr>\n";
echo "                                    <td width=\"100%\"><font face=\"Arial\"><b>\n";
echo "                              <marquee bgcolor=\"$headercolor\" width=\"524\" height=\"19\">Please\n";
echo "                              enter your username and password</marquee>\n";
echo "                              </b></font></td>\n";
echo "                                  </tr>\n";
echo "                                </table>\n";
echo "                              </div>\n";
echo "                            </td>\n";
echo "                          </tr>\n";
echo "                        </table>\n";
echo "                        </div>\n";
echo "                      </td>\n";
echo "                    </tr>\n";
echo "                  </table>\n";
echo "                </div>\n";
echo "              </td>\n";
echo "            </tr>\n";
echo "            <tr>\n";
echo "              <td width=\"684\">\n";
echo "              </center>\n";
echo "              <form method=\"POST\" action=\"$phpself\">\n";
echo "                \n";
echo "                <div align=\"center\">\n";
echo "                  <center>\n";
echo "                  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">\n";
echo "                    <tr>\n";
echo "                      <td width=\"50%\" align=\"right\" valign=\"middle\"><font face=\"Arial\">Username:<br>\n";
echo "                        </font></td>\n";
echo "                      <td width=\"50%\" valign=\"middle\"><font face=\"Arial\"><input type=\"text\" name=\"username\" size=\"20\"></font></td>\n";
echo "                    </tr>\n";
echo "                    <tr>\n";
echo "                      <td width=\"50%\" align=\"right\" valign=\"middle\"><font face=\"Arial\">\n";
echo "                Password:</font></td>\n";
echo "                      <td width=\"50%\" valign=\"middle\"><font face=\"Arial\">\n";
echo "                        <input type=\"password\" name=\"password\" size=\"20\"></font></td>\n";
echo "                    </tr>\n";
echo "                    <tr>\n";
echo "                      <td width=\"100%\" align=\"right\" colspan=\"2\" valign=\"bottom\" height=\"30\">\n";
echo "                        <p align=\"center\"><font face=\"Arial\"><input type=\"submit\" value=\"Submit\" name=\"B1\"></font><font face=\"Arial\">\n";
echo "                <input type=\"hidden\" name=\"passsubmitted\" value=\"yes\"></font></td>\n";
echo "                    </tr>\n";
echo "                  </table>\n";
echo "                  </center>\n";
echo "                </div>\n";
echo "              </form>\n";
echo "            </td>\n";
echo "            <center>\n";
echo "            </tr>\n";
echo "          </table>\n";
echo "        </div>\n";
echo "      </center>\n";
echo "      </td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "</div>\n";
echo "<p align=\"center\"><font face=\"Arial\" color=\"#ff0000\" size=\"2\">$pasmes</font></p>\n";
echo "\n";
echo "</body>\n";
echo "\n";
echo "</html>\n";






}else{

$dbLink = @mysql_connect($dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase);
                   $sql="Select * FROM jobnum ORDER BY recordid";
                   $result = mysql_query ($sql,$dbLink) or die( "problem getting job list");

                     While ($row = mysql_Fetch_array($result)){
                    $jobnum=$row['recordid'];
                    }
                   mysql_close();
                   $newjob=$jobnum+1;



$dbLink = @mysql_connect($dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase);
                   $sql="Select * FROM jobs ORDER BY jobid";
                   $result = mysql_query ($sql,$dbLink) or die( "problem getting job list");

$loadlist="";
                   While ($row = mysql_Fetch_array($result)){
                    $joblist.=$row['jobid'];
                    $joblist.="   ";
                    $joblist.=$row['title'];
                    $joblist.="\n";
                    $jobid=$row['jobid'];
                    $ttitle=$row['title'];
                    $loadlist.="                                        <option value=\"$jobid\">$jobid $ttitle</option>\n";
                    }


$checked="";
$checked.= "                                <td align=\"center\"><font size=\"2\" face=\"Arial\"><input type=\"radio\" value=\"add\" name=\"R1\"   checked>Add\n";
$checked.= "                                  to Database</font></td>\n";
$checked.="                                <td align=\"center\"><font size=\"2\" face=\"Arial\"><input type=\"radio\" value=\"update\" name=\"R1\">Update Record</font></td>\n";




if ($loadjob=="load"){


$dbLink = @mysql_connect($dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase );
                   $sql="Select * FROM jobs WHERE  jobid=$joblistings";
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
                    }
$checked="";
$checked.= "                                <td align=\"center\"><font size=\"2\" face=\"Arial\"><input type=\"radio\" value=\"add\" name=\"R1\">Add\n";
$checked.= "                                  to Database</font></td>\n";
$checked.="                                <td align=\"center\"><font size=\"2\" face=\"Arial\"><input type=\"radio\" value=\"update\" name=\"R1\"  checked>Update Record</font></td>\n";
}else{
$blah=0;
}



echo "\n";
echo "<html>\n";
echo "\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
echo "<meta name=\"GENERATOR\" content=\"Microsoft FrontPage 4.0\">\n";
echo "<meta name=\"ProgId\" content=\"FrontPage.Editor.Document\">\n";
echo "<title>$jobtitle Admin Page</title>\n";
echo "</head>\n";
echo "\n";
echo "<body>\n";
echo "\n";
echo "<div align=\"center\">\n";
echo "  <center>\n";
echo "  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\" bgcolor=\"$maincolor\">\n";
echo "    <tr>\n";
echo "      <td>\n";
echo "        <div align=\"left\">\n";
echo "          <table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
echo "            <tr>\n";
echo "              <td width=\"700\" colspan=\"2\" height=\"5\">\n";
echo "                <div align=\"center\">\n";
echo "                  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo "                    <tr>\n";
echo "                      <td width=\"100%\">\n";
echo "                        <div align=\"center\">\n";
echo "                          <center>\n";
echo "                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"$headercolor\">\n";
echo "                          <tr>\n";
echo "                            <td width=\"550\" valign=\"top\" bgcolor=\"$headercolor\">\n";
echo "                              <p align=\"center\"><font face=\"Arial\" color=\"$titlefontcolor\"><b>$jobtitle Admin Page</b></font></td>\n";
echo "                            <td width=\"150\" rowspan=\"2\">\n";
echo "                              <div align=\"center\">\n";
echo "                                <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"$headercolor\">\n";
echo "                                  <tr>\n";
echo "                                    <td width=\"100%\">\n";
echo "                                      <form method=\"POST\" action=\"$phpself\">\n";
echo "                                         <p align=\"center\"><font size=\"2\" face=\"Arial\"><b>Choose\n";
echo "                                        a job to load</b></font><br>\n";
echo "                                        <select size=\"1\" name=\"joblistings\">\n";





echo $loadlist;




echo "                                       <input type=\"hidden\" value=\"load\" name=\"loadjob\">\n";
echo "                                       <input type=\"hidden\" value=\"yes\" name=\"passsubmitted\">\n";
echo "                                       <input type=\"hidden\" value=\"$password\" name=\"password\">\n";
echo "                                       <input type=\"hidden\" value=\"$username\" name=\"username\">\n";

echo "                                        &nbsp;\n";
echo "                                        </select><br>\n";
echo "                                        <input type=\"submit\" value=\"Submit\" name=\"B1\"></p>\n";
echo "                                      </form>\n";
echo "                                    </td>\n";
echo "                                  </tr>\n";
echo "                                </table>\n";
echo "                              </div>\n";
echo "                            </td>\n";
echo "                          </tr>\n";
echo "                          <tr>\n";
echo "                            <td width=\"550\" valign=\"top\" bgcolor=\"$headercolor\">\n";
echo "                              <div align=\"center\">\n";
echo "                                <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">\n";
echo "                                  <tr>\n";
echo "                                    <td width=\"100%\"><font face=\"Arial\"><b>\n";
echo "                              <marquee bgcolor=\"$headercolor\" width=\"524\" height=\"19\">Welcome\n";
echo "                              to the PHP Job Poster</marquee>\n";
echo "                              </b></font></td>\n";
echo "                                  </tr>\n";
echo "                                </table>\n";
echo "                              </div>\n";
echo "                            </td>\n";
echo "                          </tr>\n";
echo "                        </table>\n";
echo "                          </center>\n";
echo "                        </div>\n";
echo "                      </td>\n";
echo "                    </tr>\n";
echo "                  </table>\n";
echo "                </div>\n";
echo "              </td>\n";
echo "            </tr>\n";
echo "            <tr>\n";
echo "              <td width=\"500\">\n";
echo "                <form method=\"POST\" action=\"editdatabase.php\">\n";
echo "                                    <div align=\"center\">\n";
echo "                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"$maincolor\" width=\"98%\">\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Job\n";
echo "                          Title:<br>\n";
echo "                          <input type=\"text\" name=\"title\" size=\"34\" value=\"$title\"></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                        <td align=\"left\" rowspan=\"8\" valign=\"top\">\n";
echo "                          <p align=\"center\"><font size=\"2\" face=\"Arial\"><b>List\n";
echo "                          of current jobs in database</b></font><br>\n";
echo "                          <textarea rows=\"22\" name=\"curjobs\" cols=\"26\">$joblist</textarea></td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Company\n";
echo "                          Name:<br>\n";
echo "                          <input type=\"text\" name=\"company\" size=\"34\" value=\"$company\"></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Job\n";
echo "                          Location:<br>\n";
echo "                          <input type=\"text\" name=\"location\" size=\"34\" value=\"$location\"></font></b></td>\n";
echo "\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Job\n";
echo "                          Description:<br>\n";
echo "                          <textarea rows=\"5\" name=\"description\" cols=\"29\">$description</textarea></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Contact\n";
echo "                          Info:<br>\n";
echo "                          <textarea rows=\"3\" name=\"contact\" cols=\"29\">$contact</textarea></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Email:<br>\n";
echo "                          <input type=\"text\" name=\"email\" size=\"34\" value=\"$email\"></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\"><b><font size=\"2\" face=\"Arial\">Url:<br>\n";
echo "                          <input type=\"text\" name=\"url\" size=\"34\" value=\"$url\"></font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\">\n";
echo "                          <p align=\"center\">&nbsp;</td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\">\n";
echo "                          <p align=\"center\"><b><font COLOR=\"#ee0000\"><font face=\"Arial\" size=\"4\">Note:\n";
echo "                          </font></font><font face=\"Arial\" size=\"4\">Do not input\n";
echo "                          any #, &quot; <br>\n";
echo "                          or | symbols or you will receive errors.</font></b></td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                        <td align=\"left\" valign=\"top\">\n";
echo "                          <div align=\"center\">\n";
echo "                            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "                              <tr>\n";
echo $checked;
echo "                              </tr>\n";
echo "                              <tr>\n";
echo "                                <td colspan=\"2\" align=\"center\"><font size=\"2\" face=\"Arial\"><input type=\"radio\" value=\"delete\" name=\"R1\">Delete\n";
echo "                                  Record</font></td>\n";
echo "                              </tr>\n";
echo "                            </table>\n";
echo "                          </div>\n";
echo "                        </td>\n";
echo "                      </tr>\n";
echo "                      <tr>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                        <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
echo "                        <td align=\"left\" valign=\"top\">\n";
echo "                          <p align=\"center\"><font size=\"2\" face=\"Arial\">\n";
echo "                         <input type=\"hidden\" name=\"newjobid\" value=\"$newjob\">\n";
echo "                         <input type=\"hidden\" name=\"currentjobid\" value=\"$currentjobid\">\n";
echo "                         <input type=\"hidden\" name=\"publishdate\" value=\"$publishdate\">\n";
echo "                         <input type=\"hidden\" name=\"username\" value=\"$username\">\n";
echo "                         <input type=\"hidden\" name=\"password\" value=\"$password\">\n";

echo "                         <input type=\"submit\" value=\"Submit\" name=\"B3\"><input type=\"reset\" value=\"Reset\" name=\"B4\"></font></td>\n";
echo "                      </tr>\n";
echo "                    </table>\n";
echo "                  </div>\n";
echo "                </form>\n";
echo "              </center></td>\n";
echo "            <center>\n";
echo "            <td width=\"200\" valign=\"top\">\n";
echo "              <div align=\"center\">\n";
echo "                <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">\n";
echo "                      <p align=\"center\"><font face=\"Arial\" size=\"2\"><b>New Job\n";
echo "                      ID<br>\n";
echo "                      <input type=\"text\" name=\"newjob\" size=\"10\" value=\"$newjob\"><br>\n";
echo "                      Date Published<br>\n";
echo "                      <input type=\"text\" name=\"pubdate\" size=\"10\" value=\"$publishdate\"><br>\n";
echo "                      Current Job Id<br>\n";
echo "                      <input type=\"text\" name=\"currentid\" size=\"10\" value=\"$currentjobid\"></b></font></td>\n";
echo "                  </tr>\n";
echo "                </table>\n";
echo "              </div>\n";
echo "              <p>&nbsp;</p>\n";
echo "              <div align=\"center\">\n";
echo "                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">\n";
echo "                      <p align=\"center\"><font face=\"Arial\" size=\"1\" color=\"#EE0000\">Use\n";
echo "              the form below to change the username and password for the job\n";
echo "              listings page.&nbsp; If the choices are blank there will be no\n";
echo "                      username or password for the page</font></td>\n";
echo "                  </tr>\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">\n";
echo "              <div align=\"center\">\n";
echo "                <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"$headercolor\">\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">\n";
echo "                      <form method=\"POST\" action=\"setpass.php\">\n";
echo "                        <p align=\"center\"><font size=\"2\" face=\"Arial\">USERNAME:<br>\n";
echo "                        <input type=\"text\" name=\"stuuser\" size=\"20\"><br>\n";
echo "                        PASSWORD:</font><br>\n";
echo "                        <input type=\"password\" name=\"stupass\" size=\"20\"><br>\n";
echo "\n";
echo "                        <input type=\"hidden\" name=\"newjobid\" value=\"$newjob\" size=\"20\">\n";
echo "                        <input type=\"hidden\" name=\"username\" value=\"$username\">\n";
echo "                        <input type=\"hidden\" name=\"password\" value=\"$password\">\n";
echo "                        <input type=\"submit\" value=\"Submit\" name=\"B1\"></p>\n";
echo "                      </form>\n";
echo "                    </td>\n";
echo "                  </tr>\n";
echo "                </table>\n";
echo "              </div>\n";
echo "                    </td>\n";
echo "                  </tr>\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">&nbsp;</td>\n";
echo "                  </tr>\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">&nbsp;</td>\n";
echo "                  </tr>\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">&nbsp;</td>\n";
echo "                  </tr>\n";
echo "                  <tr>\n";
echo "                    <td width=\"100%\">\n";
echo "                      <p align=\"center\"><font face=\"Arial\" size=\"1\" color=\"#EE0000\"><a href=\"documentation.html\" target=\"_blank\">Click\n";
echo "                      here for help</a></font></td>\n";
echo "                  </tr>\n";
echo "                </table>\n";
echo "              </div>\n";
echo "            </td>\n";
echo "            </tr>\n";
echo "          </table>\n";
echo "        </div>\n";
echo "      </td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "</div>\n";
echo "<p>&nbsp;</p>\n";
echo "\n";
echo "</body>\n";
echo "\n";
echo "</html>\n";
}
?>