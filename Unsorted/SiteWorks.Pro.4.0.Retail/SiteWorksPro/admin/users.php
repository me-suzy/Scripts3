<?php include("templates/dev_top.php"); ?>

        <?php

                /*
                        Firstly, we will make sure that the user is a super-administrator
                        (has an IsLoggedIn value of 3). If they arent, we will redirect them
                        to the main page.
                */

                $userStatus = IsLoggedIn();

                if($userStatus == 0)
                        header("Location: $siteURL/admin/index.php");

                function NoAccess()
                {
                ?>
                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                <span class="BodyHeading">Unauthorised</span><br><br>
                                You do not have sufficient login privledges to access this
                                part of the site. This breach has been logged.<br><br>
                                <a href="index.php">Return Home</a>
                        </p>

                <?php

                        // Don't process the rest of the script

                        global $PHP_SELF;

                        $usrCredentials = new usrCredentials();
                        $strPage = $PHP_SELF;
                        $strUser = $usrCredentials->GetData();
                        $strLog = "Unauthorised: {$strUser[0]}, $strPage, $REMOTE_ADDR, " . date("d/m/Y h:i:m A");

                        AppendToLog($strLog);
                        include("templates/dev_bottom.php");
                        exit;
                }

                $strMethod = $_GET["strMethod"];
                
                if($strMethod == "")
					$strMethod = @$_POST["strMethod"];

                switch($strMethod)
                        {
                                case "addnew":
                                {
                                         if($userStatus < 3)
                                                if(in_array("add_users", $publisherAccess))
                                                        ProcessNew();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessNew();
                                        break;
                                }
                                case "newfinal":
                                {
                                        AddNew();
                                        break;
                                }
                                case "modify":
                                {
                                         if($userStatus < 3)
                                                if(in_array("edit_users", $publisherAccess))
                                                        ProcessModify();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessModify();
                                        break;
                                }
                                case "updatefinal":
                                {
                                        UpdateFinal();
                                        break;
                                }
                                case "delete":
                                {
                                         if($userStatus < 3)
                                                if(in_array("delete_users", $publisherAccess))
                                                        ProcessDelete();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessDelete();
                                        break;
                                }
                                default:
                                {
                                         if($userStatus < 3)
                                                if(in_array("view_users", $publisherAccess))
                                                        ShowUserList();
                                                else
                                                        NoAccess();
                                         else
                                                ShowUserList();
                                        break;
                                }
                        }

                function ShowUserList()
                        {
                                global $recsPerPage;
                                global $appName;
                                
                                $page = @$_GET["page"];
                                $start = @$_GET["start"];
                                ?>

                                        <?php include("includes/jscript/adminuser.js"); ?>

                                            <?php

                                                        /*
                                                                We will get a list of admin users from the tbl_AdminLogins table and
                                                                display each of them in a table row
                                                        */

                                                        $dbVars = new dbVars();
                                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                        if($svrConn)
                                                                {
                                                                        // We are connected to the database server
                                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                        if($dbConn)
                                                                                {
                                                                                        // Connected to the database, execute our query
                                                                                        if(!is_numeric($page) || $page < 1)
                                                                                                $page = 1;

                                                                                        if($page == 1)
                                                                                                $start = 0;
                                                                                        else
                                                                                                $start = ($page * $recsPerPage) - $recsPerPage;

                                                                                        // Get the number of records in the table
                                                                                        $numRows = mysql_num_rows(mysql_query("select pk_alId from tbl_AdminLogins"));

                                                                                        $strQuery = "select * from tbl_AdminLogins order by alFName, alLName limit $start, $recsPerPage";
                                                                                        $results = mysql_query($strQuery);
                                                                                        $bgColor = "#ECECEC";

                                                                                        ?>
                                                                                                <form onSubmit="return ConfirmDelAdminUser()" action="users.php?strMethod=delete" method="post">
                                                                                                <p style="margin-left:15" class="BodyHeading"><?php echo $appName; ?> Users
                                                                                                <div align="center">
                                                                                                <center>
                                                                                                <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                                                                                <tr>
                                                                                                        <td width="100%" height="20" colspan="5" align="right" class="BodyText" valign="top">
                                                                                                        <?php

                                                                                                                if($page > 1)
                                                                                                                  $nav .= "<a href='users.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

                                                                                                                for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
                                                                                                                  if($i == $page)
                                                                                                                    $nav .= "<a href='users.php?page=$i'><b>$i</b></a> | ";
                                                                                                                  else
                                                                                                                    $nav .= "<a href='users.php?page=$i'>$i</a> | ";

                                                                                                                if(($start+$recsPerPage) < $numRows && $numRows > 0)
                                                                                                                  $nav .= "<a href='users.php?page=" . ($page+1) . "'><u>Next »</u></a>";

                                                                                                                if(substr(strrev($nav), 0, 2) == " |")
                                                                                                                  $nav = substr($nav, 0, strlen($nav)-2);

                                                                                                                echo $nav . "<br>&nbsp;";
                                                                                                        ?>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                    <tr>
                                                                                                      <td width="1%" bgcolor="#333333" height="21">
                                                                                                      </td>
                                                                                                      <td width="27%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Full
                                                                                                        Name</span></td>
                                                                                                      <td width="25%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading"><b>Username</span></td>
                                                                                                      <td width="27%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Email</span></td>
                                                                                                      <td width="20%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Created</span></td>
                                                                                                    </tr>
                                                                                        <?php

                                                                                        // If there's no records, show a "No users found" table row
                                                                                        if($numRows == 0)
                                                                                        {
                                                                                        ?>
                                                                                                <tr>
                                                                                                  <td width="100%" colspan="5" bgcolor="#FFFFFF" height="25">
                                                                                                        <span class="BodyText">
                                                                                                                No users found in the database
                                                                                                        </span>
                                                                                                  </td>
                                                                                                </tr>
                                                                                        <?php
                                                                                        }

                                                                                        while($row = mysql_fetch_array($results))
                                                                                                {
                                                                                                        $bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
                                                                                                        ?>
                                                                                                                <tr>
                                                                                                                  <td width="1%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <div align="center">
                                                                                                                                <input type="checkbox" name="uId[]" value="<?php echo $row["pk_alId"]; ?>">
                                                                                                                        </div>
                                                                                                                  </td>
                                                                                                                  <td width="27%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <a href="users.php?strMethod=modify&auId=<?php echo $row["pk_alId"]; ?>">
                                                                                                                                        <?php

                                                                                                                                                $strUser = $row["alFName"] . " " . $row["alLName"];
                                                                                                                                                if(strlen($strUser) > 17)
                                                                                                                                                        { echo substr($strUser, 0, 17) . "..."; }
                                                                                                                                                else
                                                                                                                                                        { echo $strUser; }
                                                                                                                                        ?></a>
                                                                                                                        </p>
                                                                                                                </td>
                                                                                                                  <td width="25%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <?php

                                                                                                                                        if(strlen($row["alUserName"]) > 13)
                                                                                                                                                { echo substr($row["alUserName"], 0, 13) . "..."; }
                                                                                                                                        else
                                                                                                                                                { echo $row["alUserName"]; }
                                                                                                                                ?>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                  <td width="27%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <a href="mailto:<?php echo $row["alEmail"]; ?>">
                                                                                                                                        <?php

                                                                                                                                                //echo substr($row["alEmail"], 0, 15);
                                                                                                                                                if(strlen($row["alEmail"]) > 17)
                                                                                                                                                        { echo substr($row["alEmail"], 0, 17) . "..."; }
                                                                                                                                                else
                                                                                                                                                        { echo $row["alEmail"]; }


                                                                                                                                        ?></a>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                  <td width="20%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <?php echo substr($row["alDateJoined"], 4, 2) . "/" . substr($row["alDateJoined"], 6, 2) . "/" . substr($row["alDateJoined"], 0, 4); ?>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                </tr>
                                                                                                        <?
                                                                                                }
                                                                                                
																						if($numRows > 0)
																						{
                                                                                        ?>
                                                                                            <tr>
																								<td width="28%" colspan="2">
																									<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
																								</td>
																								<td width="100%" height="20" colspan="3" align="right" class="BodyText" valign="top">
																								<br>
																								<?php echo $nav . "<br>&nbsp;"; ?>
																								</td>
                                                                                            </tr>
                                                                                        <?php
																						}
                                                                                }
                                                                        else
                                                                                {
                                                                                        // Couldn't connect to the database
                                                                                        ?>
                                                                                                <tr>
                                                                                                        <td colspan="5" width="100%" background="greenline.gif">
                                                                                                                <span class="Error">Error: A connection to the database couldnt be established.</span>
                                                                                                        </td>
                                                                                                </tr>
                                                                                        <?php
                                                                                }
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to the database server
                                                                        ?>
                                                                                <tr>
                                                                                        <td colspan="5" width="100%" background="greenline.gif">
                                                                                                <span class="Error">Error: A connection to the database server couldnt be established.</span>
                                                                                        </td>
                                                                                </tr>
                                                                        <?php
                                                                }
                                            ?>

                                            <tr>
                                                        <td colspan="5" width="100%">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                            <tr>
                                              <td colspan="2" width="28%" height="21">
                                                        <input onClick="JumpToAddUser()" type="button" value="Add User »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                              </td>
                                              <td width="25%" height="21">

                                              <td width="27%" height="21">

                                              <td width="20%" height="21">
                                                <div align="right">
                                                                <a href="#top">^ Top</a>
                                                </div>
                                            </tr>
                                          </table>
                                          <br>&nbsp;
                                          </center>
                                        </div>
                                       </form>

                        <?php
                }

        function ProcessNew()
                {
                        /*
                                This function will show the screen to capture data about the
                                new user that we are adding to the tbl_AdminLogins database.
                                It will then pass the data to the AddNew function of this page.
                        */

                        ?>

                                <?php include("includes/jscript/adminuser.js"); ?>

                                <form onSubmit="return CheckAddAdminLogin()" enctype="multipart/form-data"  name="frmAddAdminUser" action="users.php?strMethod=newfinal" method="post">
                                <div align="center">
                                        <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Add User</span><br><br>
                                                                        Use the form below to create a new administrator
                                                                        account. Make sure you set the appropriate security level
                                                                        of access for this user. Fields marked with a <span class="Error">*</span>  are required.<br><br>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        First Name:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strFName" type="text" size="60" maxlength="30">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Last Name:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strLName" type="text" size="60" maxlength="30">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Email:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strEmail" type="text" size="60" maxlength="250">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        User Id:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strUserId" type="text" size="60" maxlength="20">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Password:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strPass" type="password" size="60" maxlength="20">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Security Level:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <select name="intSecLevel" style="width:385">
                                                                        <option selected>[Select Security Level]</option>
                                                                        <option value="2">Publisher</option>
                                                                        <option value="3">Administrator</option>
                                                                </select>
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Picture:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strPic" type="file" style="width:385">
                                                                <span class="Error">*</span><br>
                                                                [Should be in GIF format and 70x75 pixels]<br><br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%" valign="top">
                                                                <div align="right">
                                                                        <br>Biography:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <?php PrintWYSIWYGTable(); ?>
                                                                <input type="hidden" name="strBio">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                        </td>
                                                        <td width="80%">
                                                                <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input type="submit" value="Add User »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                        </table>
                                        </form>

                        <?php
                }

        function AddNew()
                {
                        /*
                                This function will add our new user to the database and then redirect the user
                                to the adminlogins.php page when that's done.
                        */

                                $strFName = @$_POST["strFName"];
                                $strLName = @$_POST["strLName"];
                                $strEmail = @$_POST["strEmail"];
                                $strUserId = @$_POST["strUserId"];
                                $strPass = @$_POST["strPass"];
                                $intSecLevel = @$_POST["intSecLevel"];
                                $strBio = @$_POST["strBio"];

                                $strPic = $_FILES["strPic"]["tmp_name"];
                                $strPic_name = $_FILES["strPic"]["name"];
                                $strPic_type = $_FILES["strPic"]["type"];
                                $strPic_size = $_FILES["strPic"]["size"];

                                global $appName;
                                global $adminEmail;
                                global $adminName;
                                global $siteName;
                                global $siteURL;
                                global $authorEmail;

                                // Setup a variable to handle our errors
                                $errDesc = "<ul>";

                                if($strFName == "")
                                        { $errDesc .= "<li>You must enter a first name</li>"; }
                                if($strLName == "")
                                        { $errDesc .= "<li>You must enter a last name</li>"; }
                                if($strEmail == "")
                                        { $errDesc .= "<li>You must enter an email address</li>"; }
                                if($strUserId == "")
                                        { $errDesc .= "<li>You must enter a user id</li>"; }
                                if($strPass == "")
                                        { $errDesc .= "<li>You must enter a password</li>"; }
                                if($intSecLevel == "")
                                        { $errDesc .= "<li>You must select a security level</li>"; }
                                if($strPic == "" || $strPic_type != "image/gif" || $strPic_size > 20000)
                                        { $errDesc .= "<li>You must select a valid GIF image under 20k</li>"; }
                                if(strlen($strBio) > 5000)
                                        { $errDesc .= "<li>The biography for this user should be under 5,000 characters<li>"; }

                                $errDesc .= "</ul>";

                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details for New User</span><br><br>
                                                                Some of the values that you have entered/selected for this new
                                                                user are invalid. These are shown in the list below:<br>
                                                                <span class='Error'><?php echo $errDesc; ?></span>
                                                                <p style="margin-left:15">
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        </p>
                                                <?
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                        }
                                else
                                        {
                                                /* We will add the new account details to the database */
                                                $dbVars = new dbVars();
                                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                if($svrConn)
                                                        {
                                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                if($dbConn)
                                                                        {
                                                                                // We will build the INSERT INTO query
                                                                                $strBinPic = @addslashes(fread(fopen($strPic, "rb"), filesize($strPic)));
                                                                                $strQuery  = "INSERT INTO tbl_AdminLogins(pk_alId, alUserName, alPass, alEmail, alFName, alLName, alBio, alPic, alSecLevel) ";
                                                                                $strQuery .= "VALUES(0, '$strUserId', '$strPass', '$strEmail', '$strFName', '$strLName', '$strBio', '$strBinPic', $intSecLevel)";

                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                        {
                                                                                                // Query was ok, send the credentials in an email to the user and upload pic

                                                                                                $body = str_replace("<<AdminEmail>>", $adminEmail, str_replace("<<AdminName>>", $adminName, str_replace("<<Password>>", $strPass, str_replace("<<Username>>", $strUserId, str_replace("<<SiteURL>>", $siteURL, str_replace("<<SiteName>>", $siteName, str_replace("<<AuthorName>>", $strFName . " " . $strLName, $authorEmail)))))));
                                                                                                
                                                                                                @$result = mail($strEmail, "$siteName Account Details", $body, "From: $adminEmail\r\nReply-To: $adminEmail");
                                                                                                if($result)
                                                                                                        {
                                                                                                                ?>
                                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                                <span class="BodyHeading">New Administrator Created!</span><br><br>
                                                                                                                                You have successfully created a new administrator account. The login
                                                                                                                                details for this account have been sent to <b><?php echo $strEmail; ?></b>. Click on the
                                                                                                                                link below to continue.<br><br>
                                                                                                                                <a href="users.php">Continue</a>
                                                                                                                        </p>
                                                                                                                <?php
                                                                                                        }
                                                                                                else
                                                                                                        {
                                                                                                                ?>
                                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                                <span class="BodyHeading">Couldn't Send Email</span><br><br>
                                                                                                                                You have successfully created a new administrator account, however the login
                                                                                                                                details for this account <b>couldn't</b> be sent to <b><?php echo $strEmail; ?></b>
                                                                                                                                because there seemed to be a problem with the mail server. Click on the
                                                                                                                                link below to continue.<br><br>
                                                                                                                                <a href="users.php">Continue</a>
                                                                                                                        </p>
                                                                                                                <?php
                                                                                                        }
                                                                                        }
                                                                                else
                                                                                        {
                                                                                                // Query failed
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">User Not Added</span><br><br>
                                                                                                                An error occured while trying to add this user to the database. You can click
                                                                                                                on the link below to go back and retry your submission.<br><br>
                                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                        }
                                                                        }
                                                                else
                                                                        {
                                                                                // Couldnt connect to the database
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Couldnt Open Logins Table</span><br><br>
                                                                                                A connection to the database was made, however the logins table couldn't be opened.
                                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database server
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                A connection to the database couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                        }
                                        }
                }

        function ProcessModify()
                {
                        /*
                                This function will show the screen to capture data about the
                                modified user that we are updating in the tbl_AdminLogins database.
                                It will then pass the data to the UpdateFinal function of this page.
                        */

                                $auId = @$_GET["auId"];
                                $dbVars = new dbVars();

                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                $strQuery = "select * from tbl_AdminLogins where pk_alId = $auId";
                                                                $results = mysql_query($strQuery);

                                                                if($row = mysql_fetch_array($results))
                                                                        {
                                                                                // User exists, get the data from the database
                                                                        }
                                                                else
                                                                        {
                                                                                // User doesnt exist
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">User Doesn't Exist</span><br><br>
                                                                                                The user that you have chosen to edit no longer exists in the
                                                                                                database. Please use the link below to continue.<br><br>
                                                                                                <a href="users.php">Continue</a>
                                                                                        </p>
                                                                                <?php
                                                                                                include("templates/dev_bottom.php");
                                                                                                exit;
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldn't Open Database</span><br><br>
                                                                                A connection to the database couldn't be established.<br><br>
                                                                                <a href="users.php">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Couldn't Open Database</span><br><br>
                                                                A connection to the database couldn't be established.<br><br>
                                                                <a href="users.php">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;

                                        }
                        ?>

                                <?php include("includes/jscript/adminuser.js"); ?>

                                <form onSubmit="return CheckUpdateAdminLogin()" enctype="multipart/form-data"  name="frmUpdateAdminUser" action="users.php?strMethod=updatefinal" method="post">
                                <input type="hidden" name="auId" value="<?php echo $auId ?>">
                                <div align="center">
                                        <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Modify Administrator Account</span><br><br>
                                                                        Use the form below to modify a user
                                                                        account. Make sure you set the appropriate security level
                                                                        of access for this user. Fields marked with a <span class="Error">*</span>  are required.<br><br>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        First Name:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strFName" type="text" size="60" maxlength="30" value="<?php echo $row["alFName"]; ?>">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Last Name:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strLName" type="text" size="60" maxlength="30" value="<?php echo $row["alLName"]; ?>">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Email:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strEmail" type="text" size="60" maxlength="250" value="<?php echo $row["alEmail"]; ?>">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        User Id:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strUserId" type="text" size="60" maxlength="20" value="<?php echo $row["alUserName"]; ?>">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Password:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strPass" type="password" size="60" maxlength="20" value="<?php echo $row["alPass"]; ?>">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Security Level:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <select name="intSecLevel" style="width:385">
                                                                        <option>[Select Security Level]</option>
                                                                        <option <?php if($row["alSecLevel"] == 2) echo " SELECTED " ?>value="2">Publisher</option>
                                                                        <option <?php if($row["alSecLevel"] == 3) echo " SELECTED " ?>value="3">Administrator</option>
                                                                </select>
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Picture:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input onKeyDown="chkUseCurrentPic.checked = false" onClick="chkUseCurrentPic.checked = false" name="strPic" type="file" style="width:385">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                &nbsp;
                                                        </td>
                                                        <td width="80%">
                                                                <input checked name="chkUseCurrentPic" type="checkbox"> Use Current Picture?
                                                                <br>[Should be in GIF format and 70x75 pixels]<br><br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%" valign="top">
                                                                <div align="right">
                                                                        Biography:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <?php PrintWYSIWYGTable($row["alBio"]); ?>
                                                                <input type="hidden" name="strBio">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                        </td>
                                                        <td width="80%">
                                                                <input onClick="history.go(-1)" type="button" value="Cancel" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input type="submit" value="Update User »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                        </table>
                                        </form>
                        <?php

                }

        function ProcessDelete()
                {
                        global $appName;

                        $uId = @$_POST["uId"];
                        $dbVars = new dbVars();

                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        if($svrConn && is_array($uId))
                                {
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                        if($dbConn)
                                                {
													foreach($uId as $v)
													{
														$where .= " pk_alId = $v or";
													}
													                        
													$where = ereg_replace(" or$", "", $where);

													$strQuery = "delete from tbl_AdminLogins where $where";
													mysql_query($strQuery);

													?>
													        <p style="margin-left:15; margin-right:10" class="BodyText">
													                <span class="BodyHeading">Users Deleted Successfully</span><br><br>
													                You have successfully deleted one/more users from the <?php echo $appName; ?> logins database.
													                Use the link below to return to the user listing page.<br><br>
													                <a href="users.php">Continue</a>
													        </p>
													<?php
                                                }
                                        else
                                                {
                                                        // Couldnt connect to the database
                                                        ?>
                                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                        <span class="BodyHeading">Couldn't Open Database</span><br><br>
                                                                        An error occured while trying to connect to the database and
                                                                        delete the selected user. Please use the link below to return
                                                                        to the administrator listings page.<br><br>
                                                                        <a href="users.php">Return</a>
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // Couldnt connect to the database server
                                        ?>
                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                        <span class="BodyHeading">Invalid User Id</span><br><br>
                                                        The user id that you have selected is invalid. Please use the
                                                        link below to go back and select another user.<br><br>
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>
                                        <?php
                                }
                }

        function UpdateFinal()
                {
                        /*
                                This function will update the user whose details have been parsed
                                from the form.
                        */

                                $auId = $_POST["auId"];
                                $strFName = $_POST["strFName"];
                                $strLName = $_POST["strLName"];
                                $strEmail = $_POST["strEmail"];
                                $strUserId = $_POST["strUserId"];
                                $strPass = $_POST["strPass"];
                                $intSecLevel = $_POST["intSecLevel"];
                                $strBio = $_POST["strBio"];
                                $chkUseCurrentPic = $_POST["chkUseCurrentPic"];

                                $strPic = $_FILES["strPic"]["tmp_name"];
                                $strPic_name = $_FILES["strPic"]["name"];
                                $strPic_type = $_FILES["strPic"]["type"];
                                $strPic_size = $_FILES["strPic"]["size"];

                                global $appName;
                                global $adminEmail;
                                global $siteName;

                                // Setup a variable to handle our errors
                                $errDesc = "<ul>";

                                if($strFName == "")
                                        { $errDesc .= "<li>You must enter a first name</li>"; }
                                if($strLName == "")
                                        { $errDesc .= "<li>You must enter a last name</li>"; }
                                if($strEmail == "")
                                        { $errDesc .= "<li>You must enter an email address</li>"; }
                                if($strUserId == "")
                                        { $errDesc .= "<li>You must enter a user id</li>"; }
                                if($strPass == "")
                                        { $errDesc .= "<li>You must enter a password</li>"; }
                                if($intSecLevel == "")
                                        { $errDesc .= "<li>You must select a security level</li>"; }
                                if( ($strPic == "" || $strPic_type != "image/gif" || $strPic_size > 20000) && ($chkUseCurrentPic == "") )
                                        { $errDesc .= "<li>You must select a valid GIF image under 20k or select to use the current image</li>"; }
                                if(strlen($strBio) > 5000)
                                        { $errDesc .= "<li>The biography for this user should be under 5,000 characters<li>"; }

                                $errDesc .= "</ul>";

                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details for User</span><br><br>
                                                                Some of the values that you have entered/selected for this
                                                                user are invalid. These are shown in the list below:<br>
                                                                <span class='Error'><?php echo $errDesc; ?></span>
                                                                <p style="margin-left:15">
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        </p>
                                                <?
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                        }
                                else
                                        {
                                                /* We will update the account details to the database */
                                                $dbVars = new dbVars();
                                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                if($svrConn)
                                                        {
                                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                if($dbConn)
                                                                        {
                                                                                // We will build the INSERT INTO query
                                                                                if($chkUseCurrentPic == "")
                                                                                        { // We are uploading a new image
                                                                                          $strBinPic = addslashes(fread(fopen($strPic, "rb"), filesize($strPic)));
                                                                                          $strQuery  = "UPDATE tbl_AdminLogins ";
                                                                                          $strQuery .= "SET alUserName = '$strUserId', ";
                                                                                          $strQuery .= "    alPass = '$strPass', ";
                                                                                          $strQuery .= "    alEmail = '$strEmail', ";
                                                                                          $strQuery .= "    alFName = '$strFName', ";
                                                                                          $strQuery .= "    alLName = '$strLName', ";
                                                                                          $strQuery .= "    alBio = '$strBio', ";
                                                                                          $strQuery .= "    alPic = '$strBinPic', ";
                                                                                          $strQuery .= "    alSecLevel = $intSecLevel ";
                                                                                          $strQuery .= "WHERE pk_alId = $auId";
                                                                                        }
                                                                                else
                                                                                        { // We are using the image already in the database
                                                                                          $strQuery  = "UPDATE tbl_AdminLogins ";
                                                                                          $strQuery .= "SET alUserName = '$strUserId', ";
                                                                                          $strQuery .= "    alPass = '$strPass', ";
                                                                                          $strQuery .= "    alEmail = '$strEmail', ";
                                                                                          $strQuery .= "    alFName = '$strFName', ";
                                                                                          $strQuery .= "    alLName = '$strLName', ";
                                                                                          $strQuery .= "    alBio = '$strBio', ";
                                                                                          $strQuery .= "    alSecLevel = $intSecLevel ";
                                                                                          $strQuery .= "WHERE pk_alId = $auId";
                                                                                        }

                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                                {
                                                                                                // Query was ok
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">Administrator Account Updated!</span><br><br>
                                                                                                                You have successfully updated an administrator account. Click on the
                                                                                                                link below to continue.<br><br>
                                                                                                                <a href="users.php">Continue</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                                }
                                                                                        else
                                                                                                {
                                                                                                        ?>
                                                                                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                        <span class="BodyHeading">Couldn't Update Account</span><br><br>
                                                                                                                        An error occured while trying to update the details of the selected administrators
                                                                                                                        account. Click on the link below to go back and try again.<br><br>
                                                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                                </p>
                                                                                                        <?php
                                                                                                }
                                                                        }
                                                                else
                                                                        {
                                                                                // Couldnt connect to the database
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Couldnt Open Logins Table</span><br><br>
                                                                                                A connection to the database was made, however the logins table couldn't be opened.
                                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database server
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                A connection to the database couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                        }
                                        }
                }
        ?>

<?php include("templates/dev_bottom.php"); ?>