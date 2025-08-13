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

                $strMethod = @$_GET["strMethod"];

                switch($strMethod)
				{
				        case "addnew":
				        {
				                 if($userStatus < 3)
				                        if(in_array("add_polls", $publisherAccess))
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
				                        if(in_array("edit_polls", $publisherAccess))
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
				                        if(in_array("delete_polls", $publisherAccess))
				                                ProcessDelete();
				                        else
				                                NoAccess();
				                 else
				                        ProcessDelete();
				                break;
				        }
				        case "results":
				        {
				                 if($userStatus < 3)
				                        if(in_array("poll_results", $publisherAccess))
				                                ShowResults();
				                        else
				                                NoAccess();
				                 else
				                        ShowResults();
				                break;
				        }
				        default:
				        {
				                 if($userStatus < 3)
				                        if(in_array("view_polls", $publisherAccess))
				                                ShowPollList();
				                        else
				                                NoAccess();
				                 else
				                        ShowPollList();
				                break;
				        }
				}

                function ShowPollList()
                        {
                                global $recsPerPage;
                                global $appName;
                                
                                $page = @$_GET["page"];
                                $start = @$_GET["start"];
                                ?>
                                
                                        <?php include("includes/jscript/poll.js"); ?>

                                            <?php

                                                        /*
                                                                We will get a list of poll from the tbl_Polls table and
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
                                                                                        $numRows = mysql_num_rows(mysql_query("select pk_pId from tbl_Polls"));

                                                                                        $strQuery = "select * from tbl_Polls order by pQuestion asc limit $start, $recsPerPage";
                                                                                        $results = mysql_query($strQuery);
                                                                                        $bgColor = "#ECECEC";

                                                                                        ?>
                                                                                                <form onSubmit="return ConfirmDelPoll()" action="polls.php?strMethod=delete" method="post">
                                                                                                <p style="margin-left:15" class="BodyHeading">Voting Polls<br>
                                                                                                <span class="BodyText">
																									<br><b>Note: Only one poll can be visible on your site at any one time. The poll that is currently visible
																									has its titled bolded in the list below.</b>
                                                                                                </span>
                                                                                                <div align="center">
                                                                                                <center>
                                                                                                <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                                                                                <tr>
                                                                                                        <td width="100%" height="20" colspan="5" align="right" class="BodyText" valign="top">
                                                                                                        <?php

                                                                                                                if($page > 1)
                                                                                                                  $nav .= "<a href='polls.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

                                                                                                                for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
                                                                                                                  if($i == $page)
                                                                                                                    $nav .= "<a href='polls.php?page=$i'><b>$i</b></a> | ";
                                                                                                                  else
                                                                                                                    $nav .= "<a href='polls.php?page=$i'>$i</a> | ";

                                                                                                                if(($start+$recsPerPage) < $numRows && $numRows > 0)
                                                                                                                  $nav .= "<a href='polls.php?page=" . ($page+1) . "'><u>Next »</u></a>";

                                                                                                                if(substr(strrev($nav), 0, 2) == " |")
                                                                                                                  $nav = substr($nav, 0, strlen($nav)-2);

                                                                                                                echo $nav . "<br>&nbsp;";
                                                                                                        ?>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                    <tr>
                                                                                                      <td width="1%" bgcolor="#333333" height="21">
                                                                                                      </td>
                                                                                                      <td width="51%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Question</span></td>
                                                                                                      <td width="15%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Type</span></td>
                                                                                                      <td width="3%" bgcolor="#333333" height="21">
                                                                                                        <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Results</span></td>
                                                                                                      <td width="30%" bgcolor="#333333" height="21">
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
                                                                                                                No polls found in the database
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
                                                                                                                                &nbsp;<input type="checkbox" name="pId[]" value="<?php echo $row["pk_pId"]; ?>">
                                                                                                                        </div>
                                                                                                                  </td>
                                                                                                                  <td width="51%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <a href="polls.php?strMethod=modify&pId=<?php echo $row["pk_pId"]; ?>">
                                                                                                                                        <?php
                                                                                                                                        
																																			if($row["pVisible"] == 1)
																																				echo "<b>";
																																				
                                                                                                                                            if(strlen($row["pQuestion"]) > 27)
                                                                                                                                                    { echo substr($row["pQuestion"], 0, 27) . "..."; }
                                                                                                                                            else
                                                                                                                                                    { echo $row["pQuestion"]; }

																																			if($row["pVisible"] == 1)
																																				echo "</b>";

                                                                                                                                        ?></a>
                                                                                                                        </p>
                                                                                                                </td>
                                                                                                                  <td width="15%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <?php
                                                                                                                                
																																	if($row["pType"] == 0)
																																		echo "Single Answer";
																																	else
																																		echo "Multi Answer";

                                                                                                                                ?>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                  <td width="3%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
																															<a href="polls.php?strMethod=results&pId=<?php echo $row["pk_pId"]; ?>">Click Here</a>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                  <td width="30%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                                <?php echo substr($row["pDateCreated"], 4, 2) . "/" . substr($row["pDateCreated"], 6, 2) . "/" . substr($row["pDateCreated"], 0, 4); ?>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                </tr>
                                                                                                        <?
                                                                                                }
                                                                                               if($numRows > 0)
                                                                                               {
                                                                                        ?>
                                                                                                <tr>
                                                                                                        <td width="52%" colspan="2">
																											<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                        </td>
                                                                                                        <td width="48%" height="20" colspan="3" align="right" class="BodyText" valign="top">
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
                                              <td colspan="2" width="28%" height="21">
                                                        <br><input onClick="JumpToAddPoll()" type="button" value="Add Poll »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                              </td>
                                              <td width="25%" height="21">

                                              <td width="27%" height="21">

                                              <td width="20%" height="21">
                                                <div align="right">
														<br><a href="#top">^ Top</a>
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

                                <?php include("includes/jscript/poll.js"); ?>

                                <form onSubmit="return CheckAddPoll()" name="frmAddPoll" action="polls.php?strMethod=newfinal" method="post">
                                <div align="center">
                                        <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Add Voting Poll</span><br><br>
                                                                        Use the form below to create a new voting poll. Fields marked with a <span class="Error">*</span> are required.<br><br>
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
                                                                        Question:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strQuestion" type="text" size="60" maxlength="250">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Poll Type:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <select name="intPollType" style="width:385">
																	<option value="0">Single Answer</option>
																	<option value="1">Multi Answer</option>
                                                                </select>
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 1:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer1" type="text" size="60" maxlength="50">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 2:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer2" type="text" size="60" maxlength="50">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 3:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer3" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 4:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer4" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 5:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer5" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 6:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer6" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 7:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer7" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 8:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer8" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 9:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer9" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Answer 10:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strAnswer10" type="text" size="60" maxlength="50">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Visible:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input type="checkbox" name="blnVisible" value="1" CHECKED> Yes, this poll should be visible on my site
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Sort Answers:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input type="checkbox" name="blnSort" value="1" CHECKED> Yes, sort these answers alphabetically
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
                                                                <input type="submit" value="Add Poll »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
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

                                $strQuestion = $_POST["strQuestion"];
                                $intPollType = $_POST["intPollType"];
                                $strAnswer1 = $_POST["strAnswer1"];
                                $strAnswer2 = $_POST["strAnswer2"];
                                $strAnswer3 = $_POST["strAnswer3"];
                                $strAnswer4 = $_POST["strAnswer4"];
                                $strAnswer5 = $_POST["strAnswer5"];
                                $strAnswer6 = $_POST["strAnswer6"];
                                $strAnswer7 = $_POST["strAnswer7"];
                                $strAnswer8 = $_POST["strAnswer8"];
                                $strAnswer9 = $_POST["strAnswer9"];
                                $strAnswer10 = $_POST["strAnswer10"];
                                $blnVisible = $_POST["blnVisible"];
                                $blnSort = $_POST["blnSort"];
                                
                                if($blnVisible != 1)
									$blnVisible = 0;

                                $answerArray = array($strAnswer1, $strAnswer2, $strAnswer3, $strAnswer4, $strAnswer5, $strAnswer6, $strAnswer7, $strAnswer8, $strAnswer9, $strAnswer10);
                                $newArray = array();
                                $j = 0;
                                
                                // Loop through the array and remove the empty elements
                                for($i = 0; $i < sizeof($answerArray); $i++)
									if(!$answerArray[$i] == "")
										$newArray[$j++] = $answerArray[$i];
                                
                                $newArray = array_unique($newArray);
                                
                                if($blnSort == 1)
									sort($newArray);
									
								// Set the initial values of the variables to empty
								$strAnswer1 = $strAnswer2 = $strAnswer3 = $strAnswer4 = $strAnswer5 = $strAnswer6 = $strAnswer7 = $strAnswer8 = $strAnswer9 = $strAnswer10 = "";
								
								for($i = 0; $i < sizeof($newArray); $i++)
								{
									$j = $i + 1;
									eval("\$strAnswer$j = \$newArray[$i];");
								}

                                // Setup a variable to handle our errors
                                $errDesc = "<ul>";

                                if($strQuestion == "")
                                        { $errDesc .= "<li>You must enter a question</li>"; }
                                if($strAnswer1 == "")
                                        { $errDesc .= "<li>You must enter answer #1</li>"; }
                                if($strAnswer2 == "")
                                        { $errDesc .= "<li>You must enter answer #2</li>"; }

                                $errDesc .= "</ul>";

                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details for New Poll</span><br><br>
                                                                Some of the values that you have entered/selected for this new
                                                                poll are invalid. These are shown in the list below:<br>
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
                                                                                $strQuery  = "INSERT INTO tbl_Polls(pQuestion, pAnswer1, pAnswer2, pAnswer3, pAnswer4, pAnswer5, pAnswer6, pAnswer7, pAnswer8, pAnswer9, pAnswer10, pType, pVisible) ";
                                                                                $strQuery .= "VALUES('$strQuestion', '$strAnswer1', '$strAnswer2', '$strAnswer3', '$strAnswer4', '$strAnswer5', '$strAnswer6', '$strAnswer7', '$strAnswer8', '$strAnswer9', '$strAnswer10', $intPollType, $blnVisible)";
                                                                                
                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                        {
                                                                                            // Should this poll be visible on the sight right now?
                                                                                            if($blnVisible == 1)
                                                                                            {
																								@mysql_query("update tbl_Polls set pVisible = 0 where pk_pId <> " . mysql_insert_id());
																								@mysql_query("update tbl_Polls set pVisible = 1 where pk_pId = " . mysql_insert_id());
                                                                                            }
                                                                                            
                                                                                            // Query was ok, send the credentials in an email to the user and upload pic
                                                                                            ?>
                                                                                                    <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                            <span class="BodyHeading">New Poll Created!</span><br><br>
                                                                                                            You have successfully created a new poll. Click on the
                                                                                                            link below to continue.<br><br>
                                                                                                            <a href="polls.php">Continue</a>
                                                                                                    </p>
                                                                                            <?php
                                                                                        }
                                                                                else
                                                                                        {
                                                                                                // Query failed
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">Poll Not Added</span><br><br>
                                                                                                                An error occured while trying to add this poll to the database. You can click
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
                                                                                                <span class="BodyHeading">Couldnt Open Polls Table</span><br><br>
                                                                                                A connection to the database was made, however the polls table couldn't be opened.
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
                                modified poll that we are updating in the tbl_Polls database.
                                It will then pass the data to the UpdateFinal function of this page.
                        */

                                $pId = @$_GET["pId"];
                                $dbVars = new dbVars();

                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                $strQuery = "select * from tbl_Polls where pk_pId = $pId";
                                                                $results = mysql_query($strQuery);

                                                                if($row = mysql_fetch_array($results))
                                                                        {
                                                                                // Poll exists, get the data from the database
                                                                        }
                                                                else
                                                                        {
                                                                                // Poll doesnt exist
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Poll Doesn't Exist</span><br><br>
                                                                                                The poll that you have chosen to edit no longer exists in the
                                                                                                database. Please use the link below to continue.<br><br>
                                                                                                <a href="polls.php">Continue</a>
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
                                                                                <a href="polls.php">Continue</a>
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
                                                                <a href="polls.php">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;

                                        }
                        ?>

                        <?php include("includes/jscript/poll.js"); ?>

                        <form onSubmit="return CheckAddPoll()" name="frmAddPoll" action="polls.php?strMethod=updatefinal" method="post">
                        <input type="hidden" name="pId" value="<?php echo $pId; ?>">
                        <div align="center">
                                <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                                <td colspan="2" width="100%">
                                                        <p class="BodyText">
                                                                <span class="BodyHeading">Modify Voting Poll</span><br><br>
                                                                Use the form below to modify the selected voting poll. Fields marked with a <span class="Error">*</span> are required.<br>
                                                                <b>Note: All current votes for this poll will be deleted.</b><br><br>
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
                                                                Question:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strQuestion" type="text" size="60" maxlength="250" value="<?php echo $row["pQuestion"]; ?>">
                                                        <span class="Error">*</span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Poll Type:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <select name="intPollType" style="width:385">
															<option value="0" <?php if($row["pType"] == 0) echo " SELECTED "; ?>>Single Answer</option>
															<option value="1" <?php if($row["pType"] == 1) echo " SELECTED "; ?>>Multi Answer</option>
                                                        </select>
                                                        <span class="Error">*</span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 1:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer1" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer1"]; ?>">
                                                        <span class="Error">*</span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 2:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer2" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer2"]; ?>">
                                                        <span class="Error">*</span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 3:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer3" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer3"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 4:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer4" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer4"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 5:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer5" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer5"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 6:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer6" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer6"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 7:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer7" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer7"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 8:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer8" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer8"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 9:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer9" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer9"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Answer 10:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input name="strAnswer10" type="text" size="60" maxlength="50" value="<?php echo $row["pAnswer10"]; ?>">
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Visible:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input type="checkbox" name="blnVisible" value="1" <?php if($row["pVisible"] == 1) echo " CHECKED "; ?>> Yes, this poll should be visible on my site
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="20%">
                                                        <div align="right">
                                                                Sort Answers:&nbsp;&nbsp;
                                                        </div>
                                                </td>
                                                <td width="80%">
                                                        <input type="checkbox" name="blnSort" value="1"> Yes, sort these answers alphabetically
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
                                                        <input type="submit" value="Update Poll »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
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

                        $pId = @$_POST["pId"];
                        $dbVars = new dbVars();
                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        if($svrConn && is_array($pId))
                                {
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                        if($dbConn)
                                                {
													foreach($pId as $v)
													{
														$where1 .= " pk_pId = $v or";
														$where2 .= " paPollId = $v or";
													}
													                                            
													$where1 = ereg_replace(" or$", "", $where1);
													$where2 = ereg_replace(" or$", "", $where2);

                                                    $strQuery = "delete from tbl_Polls where $where1";
                                                    mysql_query($strQuery);
                                                        
                                                    $strQuery = "delete from tbl_PollAnswers where $where2";
                                                    mysql_query($strQuery);

                                                        ?>
                                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                        <span class="BodyHeading">Poll Deleted Successfully</span><br><br>
                                                                        You have successfully deleted this poll from the database.
                                                                        Use the link below to return to the poll listing page.<br><br>
                                                                        <a href="polls.php">Continue</a>
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
                                                                        delete the selected poll. Please use the link below to return
                                                                        to the poll listings page.<br><br>
                                                                        <a href="polls.php">Return</a>
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // Couldnt connect to the database server
                                        ?>
                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                        <span class="BodyHeading">Invalid Poll Id</span><br><br>
                                                        The poll id that you have selected is invalid. Please use the link below to go
                                                        back and select another poll.<br><br>
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

                                $strQuestion = $_POST["strQuestion"];
                                $intPollType = $_POST["intPollType"];
                                $strAnswer1 = $_POST["strAnswer1"];
                                $strAnswer2 = $_POST["strAnswer2"];
                                $strAnswer3 = $_POST["strAnswer3"];
                                $strAnswer4 = $_POST["strAnswer4"];
                                $strAnswer5 = $_POST["strAnswer5"];
                                $strAnswer6 = $_POST["strAnswer6"];
                                $strAnswer7 = $_POST["strAnswer7"];
                                $strAnswer8 = $_POST["strAnswer8"];
                                $strAnswer9 = $_POST["strAnswer9"];
                                $strAnswer10 = $_POST["strAnswer10"];
                                $blnVisible = $_POST["blnVisible"];
                                $blnSort = $_POST["blnSort"];
                                $pId = $_POST["pId"];
                                
                                $answerArray = array($strAnswer1, $strAnswer2, $strAnswer3, $strAnswer4, $strAnswer5, $strAnswer6, $strAnswer7, $strAnswer8, $strAnswer9, $strAnswer10);
                                $newArray = array();
                                $j = 0;
                                
                                // Loop through the array and remove the empty elements
                                for($i = 0; $i < sizeof($answerArray); $i++)
									if(!$answerArray[$i] == "")
										$newArray[$j++] = $answerArray[$i];
                                
                                $newArray = array_unique($newArray);
                                
                                if($blnSort == 1)
									sort($newArray);
									
								// Set the initial values of the variables to empty
								$strAnswer1 = $strAnswer2 = $strAnswer3 = $strAnswer4 = $strAnswer5 = $strAnswer6 = $strAnswer7 = $strAnswer8 = $strAnswer9 = $strAnswer10 = "";
								
								for($i = 0; $i < sizeof($newArray); $i++)
								{
									$j = $i + 1;
									eval("\$strAnswer$j = \$newArray[$i];");
								}
								
                                // Setup a variable to handle our errors
                                $errDesc = "<ul>";
                                
                                if($strQuestion == "")
                                        { $errDesc .= "<li>You must enter a question</li>"; }
                                if($strAnswer1 == "")
                                        { $errDesc .= "<li>You must enter answer #1</li>"; }
                                if($strAnswer2 == "")
                                        { $errDesc .= "<li>You must enter answer #2</li>"; }

                                $errDesc .= "</ul>";
                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details for Poll</span><br><br>
                                                                Some of the values that you have entered/selected for this
                                                                poll are invalid. These are shown in the list below:<br>
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
                                                                                $strQuery  = "UPDATE tbl_Polls ";
                                                                                $strQuery .= "SET pQuestion = '$strQuestion', ";
                                                                                $strQuery .= "    pType = '$intPollType', ";
                                                                                $strQuery .= "    pVisible = '$blnVisible', ";
                                                                                $strQuery .= "    pAnswer1 = '$strAnswer1', ";
                                                                                $strQuery .= "    pAnswer2 = '$strAnswer2', ";
                                                                                $strQuery .= "    pAnswer3 = '$strAnswer3', ";
                                                                                $strQuery .= "    pAnswer4 = '$strAnswer4', ";
                                                                                $strQuery .= "    pAnswer5 = '$strAnswer5', ";                                                                                                                                                                                                                                                                                                                                
                                                                                $strQuery .= "    pAnswer6 = '$strAnswer6', ";
                                                                                $strQuery .= "    pAnswer7 = '$strAnswer7', ";
                                                                                $strQuery .= "    pAnswer8 = '$strAnswer8', ";
                                                                                $strQuery .= "    pAnswer9 = '$strAnswer9', ";
                                                                                $strQuery .= "    pAnswer10 = '$strAnswer10' ";                                                                                                                                                                                                                                                                                                                                
                                                                                $strQuery .= "WHERE pk_pId = $pId";
                                                                                
                                                                                // Remove all old answers
                                                                                @mysql_query("delete from tbl_PollAnswers where paPollId = $pId");
                                                                                
                                                                                
                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                {
																					// Should this poll be visible on the sight right now?
																					if($blnVisible == 1)
																					{
																						@mysql_query("update tbl_Polls set pVisible = 0 where pk_pId <> $pId");
																						@mysql_query("update tbl_Polls set pVisible = 1 where pk_pId = $pId");
																					}
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Poll Updated!</span><br><br>
                                                                                                You have successfully updated the selected poll. Click on the
                                                                                                link below to continue.<br><br>
                                                                                                <a href="polls.php">Continue</a>
                                                                                        </p>
                                                                                <?php
                                                                                }
                                                                        else
                                                                                {
                                                                                        ?>
                                                                                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                        <span class="BodyHeading">Couldn't Update Poll</span><br><br>
                                                                                                        An error occured while trying to update the details of the selected poll.
                                                                                                        Click on the link below to go back and try again.<br><br>
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
                                                                                                <span class="BodyHeading">Couldnt Open Polls Table</span><br><br>
                                                                                                A connection to the database was made, however the polls table couldn't be opened.
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

	function ShowResults()
	{
		// This function will show the results of the selected
		// poll in a similar way to the front end
		
		$pId = @$_GET["pId"];
		
		if(!is_numeric($pId))
			$pId = 0;
		
		$pResult = mysql_query("select * from tbl_Polls where pk_pId = $pId");

		if(mysql_num_rows($pResult) > 0)
		{
		?>
			<p class="BodyHeading">
				<span class="SideHeading">&nbsp;&nbsp;Voting Poll Results</span>
			</p>
		<?php
			
		$pRow = mysql_fetch_array($pResult);
		$vTotal = 0;
			
		// Show the results of the poll
		?>
			<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="100%" colspan="2" valign="top" class="SideBody">
					<table width="100%" border="0" cellspacing="0" cellpadidng="0" align="center">
					<tr>
						<td width="100%" colspan="3">
							<span class="BodyText"><b><?php echo $pRow["pQuestion"]; ?></b></span><br>
							<img src="blank.gif" width="1" height="10"><br>
						</td>
					</tr>
					<?php
							
						// We will get each answer from the database as well as draw an image and
						// the percentage that answer has
								
						$pollAnswers = array();
						$pa = 0;
								
						for($i = 1; $i <= 10; $i++)
						{
							eval("
								if(\$pRow['pAnswer$i'] != '')
								{
									\$pollAnswers[$pa] = \$pa+1;
									++\$pa;
								}
							");
						}
								
						// Firstly, we work out the total number of votes for
						// this poll
						$tResult = mysql_query("select count(distinct(paVisitorIP)) from tbl_PollAnswers where paPollId = " . $pRow["pk_pId"]);
						$tRow = mysql_fetch_row($tResult);
						$vTotal = $tRow[0];
								
						// Now that we have the answers for this poll in an array,
						// we will loop through them and show them in the column
						for($i = 0; $i < sizeof($pollAnswers); $i++)
						{
							$vResult = mysql_query("select count(*) from tbl_PollAnswers where paPollId = " . $pRow["pk_pId"] . " and paAnswer = " . $pollAnswers[$i]);
							$vRow = mysql_fetch_row($vResult);
							$vNum = $vRow[0];
									
							if($vTotal > 0)
								$vPer = floor(($vNum / $vTotal) * 100);
							else
								$vPer = 0;
							?>
								<tr>
									<td width="100%" colspan="3">
										<span class="BodyText"><?php echo $pRow["pAnswer" . ($i+1)]; ?> [<?php echo $vNum; ?>]</span>
									</td>
								</tr>
								<tr>
									<td width="80%" colspan="1">
										<img src="../images/vote.gif" width="<?php echo $vPer; ?>%" height="10">
									</td>
									<td width="5%">&nbsp;</td>
									<td width="15%" colspan="1">
										<span class="BodyText"><?php echo $vPer; ?>%</span>
									</td>
								</tr>
							<?php
						}
					?>
						<tr>
							<td width="100%" colspan="3">
								<span class="BodyText">
									<br><b>Total of <?php echo $vTotal; ?> vote(s)</b><br><br>
									<a href="polls.php"><< Back To Polls</a>
								</span>
							</td>
						</tr>
					</table>&nbsp;
				</td>
			</tr>
		</table>
		<?php
		}
		else
		{
		?>
			<p>
				<span class="SideHeading">&nbsp;&nbsp;Voting Poll Results</span>
				<span class="BodyText">
					The selected poll no longer exists in the database. Please use the link below
					to return to the polls page.
					<br><br>
					<a href="polls.php">Continue >></a>
				</span>
			</p>
		<?php
		}
	}

	?>

<?php include("templates/dev_bottom.php"); ?>