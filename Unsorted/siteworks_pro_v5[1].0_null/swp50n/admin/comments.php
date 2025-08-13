<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	include(realpath("../conf.php"));

	include(realpath("templates/dev_top.php"));

?>

	<?php
	
		/*
			Firstly, we will make sure that the user is a super-administrator
			(has an IsLoggedIn value of 3). If they arent, we will redirect them
			to the main page.
		*/
		
		$userStatus = IsLoggedIn();

		if($userStatus == 0)
			header("Location: $siteURL/$admindir/index.php");

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
					include(realpath("templates/dev_bottom.php"));
					exit;
			}
	
		$strMethod = @$_GET["strMethod"];
		
		if($strMethod == "")
			$strMethod = @$_POST["strMethod"];

		switch($strMethod)
			{

				case "modify":
				{
					 if($userStatus < 3)
						if(in_array("view_comments", $publisherAccess))
							ProcessModify();
						else
							NoAccess();
					 else
						ProcessModify();
					break;
				}
				case "updatefinal":
				{
					 if($userStatus < 3)
						if(in_array("approve_comments", $publisherAccess))
							UpdateFinal();
						else
							NoAccess();
					 else
						UpdateFinal();
					break;
				}
				case "delete":
				{
					 if($userStatus < 3)
						if(in_array("delete_comments", $publisherAccess))
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
						if(in_array("view_comments", $publisherAccess))
							ShowCommentsList();
						else
							NoAccess();
					 else
						ShowCommentsList();
					break;
				}
			}
			
		function ShowCommentsList()
			{
				global $recsPerPage;
				global $siteName;
				
				$page = @$_GET["page"];
				$start = @$_GET["start"];
				?>

					<?php include(realpath("includes/jscript/comments.js")); ?>
	
					<form onSubmit="return ConfirmDelComments()" action="comments.php?strMethod=delete" method="post">
					<p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> Comment Posts
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
											$numRows = mysql_num_rows(mysql_query("select pk_cId from tbl_Comments"));

											$strQuery = "select * from tbl_Comments order by pk_cId desc limit $start, 20";
											$results = mysql_query($strQuery);
											$bgColor = "#ECECEC";
											?>
												<tr>
													<td width="100%" height="20" colspan="5" align="right" class="BodyText" valign="top">
													<?php
													
														if($page > 1)
														  $nav .= "<a href='comments.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

														for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
														  if($i == $page)
														    $nav .= "<a href='comments.php?page=$i'><b>$i</b></a> | ";
														  else
														    $nav .= "<a href='comments.php?page=$i'>$i</a> | ";
														  
														if(($start+$recsPerPage) < $numRows && $numRows > 0)
														  $nav .= "<a href='comments.php?page=" . ($page+1) . "'><u>Next »</u></a>";
														
														if(substr(strrev($nav), 0, 2) == " |")
														  $nav = substr($nav, 0, strlen($nav)-2);
														  
														echo $nav . "<br>&nbsp;";
													?>
													</td>
												</tr>
												<tr>
												  <td width="1%" bgcolor="#333333" height="21">
												  </td>
												  <td width="52%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Comment</span></td>
												  <td width="27%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Name</span></td>
												  <td width="20%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Status</span></td>
												  <td width="20%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Date&nbsp;Added</span></td>
												</tr>
											<?php

											if($numRows == 0)
											{
											?>
												<tr>
												  <td width="100%" bgcolor="#FFFFFF" height="21" colspan="4">
													<span class="BodyText">No comment posts found in the database.</span>
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
																&nbsp;&nbsp;<input type="checkbox" name="cId[]" value="<?php echo $row["pk_cId"]; ?>">
															</div>
														  </td>
														  <td width="52%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<a href="comments.php?strMethod=modify&cId=<?php echo $row["pk_cId"]; ?>">
																	<?php 
																	
																		//echo substr($row["alEmail"], 0, 15); 
																		if(strlen($row["cComment"]) > 30)
																			{ echo substr($row["cComment"], 0, 30) . "..."; }
																		else
																			{ echo $row["cComment"]; }
																	
																	
																	?>
																</a>
															</p>										  
														  </td>
														  <td width="27%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<?php

																	if($row['cEmail'] == '')
																		echo $row["cName"];
																	else
																		echo "<a href='mailto:{$row['cEmail']}'>{$row["cName"]}</a>";

																?>
															</p>
															</td>
														  <td width="20%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<?php if($row['cVisible'] == 0) echo 'Invisible'; else echo 'Visible'; ?>
															</p>
														  </td>
														  <td width="20%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<?php echo substr($row["cDateCreate"], 4, 2) . "/" . substr($row["cDateCreate"], 6, 2) . "/" . substr($row["cDateCreate"], 0, 4); ?>
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
							<td width="53%" height="20" colspan="2">
								<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
							</td>
							<td width="47%" height="20" colspan="3" align="right" class="BodyText" valign="top">
							<br>
								<?php echo $nav . "<br>&nbsp;"; ?>
							<br>&nbsp;
							</td>
						</tr>
						<?php
						}
						?>
					    <tr>

					      <td colspan="5" width="20%" height="21">
					        <div align="right">
								<a href="#top">^ Top</a>
					        </div>
					    </tr>
					  </table>
					  <br>&nbsp;
					  </center>
					</div>
					
			<?php
		}
		
	function ProcessModify()
		{

			global $siteCommentsApprove;

			/*
				This function will show the screen to capture data about the
				comment post that we are updating in the tbl_Comments database.
				It will then pass the data to the UpdateFinal function of this page.
			*/
			
				$cId = @$_GET["cId"];
				$dbVars = new dbVars();
				
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								$strQuery = "select * from tbl_Comments where pk_cId = $cId";
								$results = mysql_query($strQuery);
								
								if($row = mysql_fetch_array($results))
									{
										// comment exists, get the data from the database
									}
								else
									{
										// User doesnt exist
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">Comment Doesn't Exist</span><br><br>
												The comment post that you have chosen to edit no longer exists in the
												database. Please use the link below to continue.<br><br>
												<a href="comments.php">Continue</a>
											</p>
										<?php
												include(realpath("templates/dev_bottom.php"));
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
										<a href="comments.php">Continue</a>
									</p>
								<?php
										include(realpath("templates/dev_bottom.php"));
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
								<a href="comments.php">Continue</a>
							</p>
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					
					}
			?>

				<form name="frmAddComments" <?php if($row['cVisible'] == 1) echo "action=\"comments.php\""; else echo "action=\"comments.php?strMethod=updatefinal&cId=$cId\""; ?> method="post">
				<input type="hidden" name="cId[]" value="<?php echo $_GET['cId']; ?>">				
				<div align="center">
					<table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" width="100%">
								<p class="BodyText">
									<span class="BodyHeading">View Comment</span><br><br>
									Use the form below to view<?php if($siteCommentsApprove) echo ' and approve'; ?> a new comment post. Fields marked with a <span class="Error">*</span>  are required.<br><br>
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="2" width="100%" background="doth.gif">&nbsp;
								
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
									Name:&nbsp;&nbsp;
								</div>							
							</td>
							<td height="20" width="80%">
								<?php

									if($row['cEmail'] == '')
										echo $row["cName"];
									else
										echo "<a href='mailto:{$row['cEmail']}'>{$row["cName"]}</a>";

								?>
							</td>
						</tr>
						<tr>
							<td height="20" width="20%">
								<div align="right">
									Article:&nbsp;&nbsp;
								</div>
							</td>
							<td width="80%">
								<?php echo mysql_result(mysql_query("select aTitle FROM tbl_Articles WHERE pk_aId = '{$row['cId']}'"), 0, 0); ?>
							</td>
						</tr>
						<tr>
							<td height="20" width="20%">
								<div align="right">
									Comment:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?php echo nl2br(htmlspecialchars($row['cComment'])); ?>
							</td>
						</tr>
						<tr>
							<td height="20" width="20%">
								<div align="right">
									Date Posted:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?php echo substr($row["cDateCreate"], 4, 2) . "/" . substr($row["cDateCreate"], 6, 2) . "/" . substr($row["cDateCreate"], 0, 4); ?>
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
								<input type="button" value="Delete" onClick="document.frmAddComments.action = 'comments.php?strMethod=delete'; document.frmAddComments.submit();" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
								<input type="submit" value="<?php if($row['cVisible'] == 0) echo 'Approve Comment »'; else echo 'Continue &raquo;'; ?>" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
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
			$cId = @$_POST["cId"];
			
			$dbVars = new dbVars();			
			@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
			
			if($svrConn && is_array($cId))
				{
					$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
					if($dbConn)
						{
                            foreach($cId as $v)
                            {
								$where .= " pk_cId = $v or";
							}
                                                                        
                            $where = ereg_replace(" or$", "", $where);
							$strQuery = "delete from tbl_Comments where $where";
							mysql_query($strQuery);

							?>
								<p style="margin-left:15; margin-right:10" class="BodyText">
									<span class="BodyHeading">Comment Post(s) Deleted Successfully</span><br><br>
									You have successfully deleted comment post(s) from the database.
									Use the link below to return to the comment listings page.<br><br>
									<a href="comments.php">Continue</a>									
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
									delete the selected comment(s). Please use the link below to return
									to the administrator listings page.<br><br>
									<a href="comments.php">Return</a>									
								</p>							
							<?php
						}
				}
			else
				{
					// Couldnt connect to the database server
					?>
						<p style="margin-left:15; margin-right:10" class="BodyText">
							<span class="BodyHeading">Invalid Comment Id</span><br><br>
							The comment id that you have selected is invalid. Please use the link below to go
							back and select another post.<br><br>
							<a href="javascript:history.go(-1)">Go Back</a>									
						</p>							
					<?php
				}
		}
		
	function UpdateFinal()
		{
			/*
				This function will update the new post whose details have been parsed
				from the form.
			*/

			

				$cId = @$_GET["cId"];
				
				// Setup a variable to handle our errors

				/* We will update the account details to the database */
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						
						if($dbConn)
							{
								$strQuery  = "UPDATE tbl_Comments ";
								$strQuery .= "SET cVisible = '1' ";
								$strQuery .= "WHERE pk_cId = '$cId'";
								
								mysql_query($strQuery);
								
								?>
									<p style="margin-left:15; margin-right:10" class="BodyText">
										<span class="BodyHeading">Comment Post Approved</span><br><br>
										The selected comment was approved successfully.<br><br>
										<a href="comments.php">Continue</a>												
									</p>
								<?php
							}
						else
							{
								// Couldnt connect to the database
								?>
									<p style="margin-left:15; margin-right:10" class="BodyText">
										<span class="BodyHeading">Couldnt Open Comments Table</span><br><br>
										A connection to the database was made, however the comments table couldn't be opened.
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
	?>
		
	<?php include(realpath("templates/dev_bottom.php")); ?>