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
                
                if($strMethod == "")
					$strMethod = @$_POST["strMethod"];

                switch($strMethod)
                        {
                                case "view":
                                {
                                         if($userStatus < 3)
                                                if(in_array("view_newsletter", $publisherAccess))
                                                        ViewSubscribers();
                                                else
                                                        NoAccess();
                                         else
                                                ViewSubscribers();
                                        break;
                                }
                                case "export":
                                {
                                         if($userStatus < 3)
                                                if(in_array("export_newsletter", $publisherAccess))
                                                        ExportNewsletter();
                                                else
                                                        NoAccess();
                                         else
                                                ExportNewsletter();
                                        break;
                                }
                                case "deluser":
                                {
                                         if($userStatus < 3)
                                                if(in_array("delete_newsletter", $publisherAccess))
                                                        ProcessDelete();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessDelete();
                                        break;
                                }
                                default:
                        }

                function ViewSubscribers()
                        {
                                global $recsPerPage;
                                global $siteName;

                                $page = @$_GET["page"];
                                $start = @$_GET["start"];
                                ?>

                                        <?php include("includes/jscript/newsletter.js"); ?>

                                        <form onSubmit="return ConfirmDelSub()" action="newsletter.php?strMethod=deluser" method="post">
                                        <p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> Newsletter Subscribers
                                        <div align="center">
                                          <center>
                                          <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                            <?php

                                                        /*
                                                                We will get a list of news items from the tbl_News table and
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
                                                                                        $numRows = mysql_num_rows(mysql_query("select pk_nlId from tbl_Newsletter"));

                                                                                        $strQuery = "select * from tbl_Newsletter order by nlEmail asc limit $start, 20";
                                                                                        $results = mysql_query($strQuery);
                                                                                        $bgColor = "#ECECEC";
                                                                                        ?>
                                                                                                <tr>
                                                                                                        <td width="100%" height="20" colspan="3" align="right" class="BodyText" valign="top">
                                                                                                        <?php

                                                                                                                if($page > 1)
                                                                                                                  $nav .= "<a href='newsletter.php?strMethod=view&page=" . ($page-1) . "'><u>« Prev</u></a> | ";

                                                                                                                for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
                                                                                                                  if($i == $page)
                                                                                                                    $nav .= "<a href='newsletter.php?strMethod=view&page=$i'><b>$i</b></a> | ";
                                                                                                                  else
                                                                                                                    $nav .= "<a href='newsletter.php?strMethod=view&page=$i'>$i</a> | ";

                                                                                                                if(($start+$recsPerPage) < $numRows && $numRows > 0)
                                                                                                                  $nav .= "<a href='newsletter.php?strMethod=view&page=" . ($page+1) . "'><u>Next »</u></a>";

                                                                                                                if(substr(strrev($nav), 0, 2) == " |")
                                                                                                                  $nav = substr($nav, 0, strlen($nav)-2);

                                                                                                                echo $nav . "<br>&nbsp;";
                                                                                                        ?>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td width="1%" bgcolor="#333333" height="21">
                                                                                                  </td>
                                                                                                  <td width="49%" bgcolor="#333333" height="21">
                                                                                                    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Email</span></td>
                                                                                                  <td width="50%" bgcolor="#333333" height="21">
                                                                                                    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Date Joined</span></td>
                                                                                                </tr>
                                                                                        <?php

                                                                                        if($numRows == 0)
                                                                                        {
                                                                                        ?>
                                                                                                <tr>
                                                                                                  <td width="100%" bgcolor="#FFFFFF" height="21" colspan="3">
                                                                                                        <span class="BodyText">No subscribers found in the database.</span>
                                                                                                  </td>
                                                                                                </tr>
                                                                                        <?php
                                                                                        }

                                                                                        while($row = mysql_fetch_array($results))
                                                                                                {
                                                                                                        $bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
                                                                                                        ?>
                                                                                                                <tr>
                                                                                                                  <td width="1%" height="28" bgcolor="<?php echo $bgColor; ?>">
                                                                                                                        <div align="center">
                                                                                                                                &nbsp;&nbsp;<input type="checkbox" name="sId[]" value="<?php echo $row["pk_nlId"]; ?>">
                                                                                                                        </div>
                                                                                                                  </td>
                                                                                                                  <td width="49%" height="28" bgcolor="<?php echo $bgColor; ?>">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                           <a href="mailto:<?php echo $row["nlEmail"]; ?>"><?php echo $row["nlEmail"]; ?></a>
                                                                                                                        </p>
                                                                                                                  <td width="50%" height="28" bgcolor="<?php echo $bgColor; ?>">
                                                                                                                        <p style="margin-left:10; margin-right:10" class="BodyText">
                                                                                                                           <?php echo MakeDate($row["nlDateJoined"]); ?>
                                                                                                                        </p>
                                                                                                                  </td>
                                                                                                                </tr>
                                                                                                        <?
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
												if($numRows > 0)
												{
                                            ?>
                                                <tr>
                                                        <td width="50%" colspan="2">
															<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                        <td width="50%" height="20" colspan="1" align="right" class="BodyText" valign="top">
                                                        <br>
                                                                <?php echo $nav . "<br>&nbsp;"; ?>
                                                        <br>&nbsp;
                                                        </td>
                                                </tr>
                                               <?php
                                               }
                                               ?>
                                            <tr>
                                              <td width="20%" height="21" colspan="3">
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

                function ExportNewsletter()
                {
					// This function will export the newsletters as a text file
					// seperated by new lines which the user can then import into
					// their mail managing program, etc
					
					global $siteName;

					$data = "";
					$dbVars = new dbVars();
					@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

					if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						
						if($dbConn)
						{
							// Get the list of newsletter subscribers from the database and send
							// it to the user as a streamed text file
							$result = mysql_query("select nlEmail from tbl_Newsletter order by nlEmail asc");

							while($row = mysql_fetch_row($result))
							{
								$data .= $row[0] . "\r\n";
							}
							
							ob_clean();
							header("Content-Type: text/plain");
							header("Content-Disposition: attachment; filename=emails.txt");
							echo $data;
							
							die();
						}
						else
						{
						?>
							<p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> Newsletter Subscribers
							<div align="center">
							  <center>
							  <table border="0" width="95%" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="5" width="100%" background="greenline.gif">
								        <span class="Error">Error: A connection to the database couldnt be established.</span>
									</td>
								</tr>
							  </table>
						<?php
						}
					}
					else
					{
					?>
						<p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> Newsletter Subscribers
						<div align="center">
						  <center>
						  <table border="0" width="95%" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="5" width="100%" background="greenline.gif">
							        <span class="Error">Error: A connection to the database couldnt be established.</span>
								</td>
							</tr>
						  </table>
					<?php
					}
                }

                function ProcessDelete()
                {
					// Delete the selected email address from the tbl_Newsletter database
					
					$sId = @$_POST["sId"];

					$dbVars = new dbVars();
					@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
					
					if($svrConn && is_array($sId))
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						
						if($dbConn)
						{
							// Get the list of newsletter subscribers from the database and send
							// it to the user as a streamed text file

                            foreach($sId as $v)
                            {
								$where .= " pk_nlId = $v or";
							}
                                                                        
                            $where = ereg_replace(" or$", "", $where);
							@mysql_query("delete from tbl_Newsletter where $where");
							?>
								<p style="margin-left:15" class="BodyHeading">Email Removed Successfully
								<div align="center">
								  <center><br>
								  <table border="0" width="95%" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan="5" width="100%">
									        <span class="BodyText">The selected email address has been successfully
									        removed. Please click on the link below to continue.
									        <br><br>
									        <a href="newsletter.php?strMethod=view">Continue >></a>
									        </span>
										</td>
									</tr>
								  </table>
							<?php
						}
						else
						{
						?>
							<p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> Newsletter Subscribers
							<div align="center">
							  <center>
							  <table border="0" width="95%" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="5" width="100%" background="greenline.gif">
								        <span class="Error">Error: A connection to the database couldnt be established.</span>
									</td>
								</tr>
							  </table>
						<?php
						}
					}
					else
					{
					?>
						<p style="margin-left:15" class="BodyHeading">Invalid Subscriber Id
						<div align="center">
						  <center>
						  <table border="0" width="95%" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="5" width="100%">
							        <br><span class="BodyText">The subscriber id that you have selected is invalid. Please use the link below to go
							        back and select another subscriber.<br><br>
							        <a href="javascript:history.go(-1)">Go Back</a>
							        </span>
								</td>
							</tr>
						  </table>
					<?php
					}
				}
			?>

        <?php include("templates/dev_bottom.php"); ?>