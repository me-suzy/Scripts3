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
				case "addnew":
				{
					 if($userStatus < 3)
						if(in_array("add_news", $publisherAccess))
							ProcessNew();
						else
							NoAccess();
					 else
						ProcessNew();
					break;
				}
				case "newfinal":
					AddNew();
					break;
				case "modify":
				{
					 if($userStatus < 3)
						if(in_array("edit_news", $publisherAccess))
							ProcessModify();
						else
							NoAccess();
					 else
						ProcessModify();
					break;
				}
				case "updatefinal":
					UpdateFinal();
					break;
				case "delete":
				{
					 if($userStatus < 3)
						if(in_array("delete_news", $publisherAccess))
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
						if(in_array("view_news", $publisherAccess))
							ShowNewsList();
						else
							NoAccess();
					 else
						ShowNewsList();
					break;
				}
			}
			
		function ShowNewsList()
			{
				global $recsPerPage;
				global $siteName;
				
				$page = @$_GET["page"];
				$start = @$_GET["start"];
				?>

					<?php include("includes/jscript/devnews.js"); ?>
	
					<form onSubmit="return ConfirmDelNews()" action="news.php?strMethod=delete" method="post">
					<p style="margin-left:15" class="BodyHeading"><?php echo $siteName; ?> News Posts
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
											$numRows = mysql_num_rows(mysql_query("select pk_dnId from tbl_News"));

											$strQuery = "select * from tbl_News order by pk_dnId desc limit $start, 20";
											$results = mysql_query($strQuery);
											$bgColor = "#ECECEC";
											?>
												<tr>
													<td width="100%" height="20" colspan="5" align="right" class="BodyText" valign="top">
													<?php
													
														if($page > 1)
														  $nav .= "<a href='news.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

														for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
														  if($i == $page)
														    $nav .= "<a href='news.php?page=$i'><b>$i</b></a> | ";
														  else
														    $nav .= "<a href='news.php?page=$i'>$i</a> | ";
														  
														if(($start+$recsPerPage) < $numRows && $numRows > 0)
														  $nav .= "<a href='news.php?page=" . ($page+1) . "'><u>Next »</u></a>";
														
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
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Title</span></td>
												  <td width="27%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">URL</span></td>
												  <td width="20%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Created</span></td>
												</tr>
											<?php

											if($numRows == 0)
											{
											?>
												<tr>
												  <td width="100%" bgcolor="#FFFFFF" height="21" colspan="4">
													<span class="BodyText">No news posts found in the database.</span>
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
																&nbsp;&nbsp;<input type="checkbox" name="nId[]" value="<?php echo $row["pk_dnId"]; ?>">
															</div>
														  </td>
														  <td width="52%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<a href="news.php?strMethod=modify&newsId=<?php echo $row["pk_dnId"]; ?>">
																	<?php
																	
																		if(strlen($row["nTitle"]) > 35)
																			echo substr($row["nTitle"], 0, 35) . "...";
																		else
																			echo $row["nTitle"];																	
																	?>
																</a>
															</p>
														  <td width="27%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<a href="<?php echo $row["nURL"]; ?>" target="_blank">
																	<?php 
																	
																		//echo substr($row["alEmail"], 0, 15); 
																		if(strlen($row["nURL"]) > 17)
																			{ echo substr($row["nURL"], 0, 17) . "..."; }
																		else
																			{ echo $row["nURL"]; }
																	
																	
																	?></a>
															</p>										  
														  </td>
														  <td width="20%" height="28" bgcolor="<?php echo $bgColor; ?>">
															<p style="margin-left:10; margin-right:10" class="BodyText">
																<?php echo substr($row["nDateAdded"], 4, 2) . "/" . substr($row["nDateAdded"], 6, 2) . "/" . substr($row["nDateAdded"], 0, 4); ?>
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
					      <td colspan="3" width="80%" height="21">
							<br><input onClick="window.location.href='news.php?strMethod=addnew'" type="button" value="Add News »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
						  </td>
					      <td width="20%" height="21">
					        <div align="right">
								<br><a href="#top">^ Top</a>
					        </div>
					    </tr>
					  </table>
					  <br>&nbsp;
					  </center>
					</div>
					
			<?php
		}
		
	function ProcessNew()
		{
			/*
				This function will show the screen to capture data about the
				new new post
			*/
			
			?>
			
				<?php include("includes/jscript/devnews.js"); ?>
				
				<form onSubmit="return CheckAddNews()" name="frmAddNews" action="news.php?strMethod=newfinal" method="post">
				<div align="center">
					<table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" width="100%">
								<p class="BodyText">
									<span class="BodyHeading">Add News</span><br><br>
									Use the form below to create a new news post. Fields marked with a <span class="Error">*</span>  are required.<br><br>
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
									Title:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<input name="strTitle" type="text" size="65" maxlength="100">
								<span class="Error">*</span>								
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									Source:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<input name="strSource" type="text" size="65" maxlength="50">
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									URL:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<input name="strURL" type="text" size="65" maxlength="250" value="http://">
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									User Posting:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?
									$dbVars = new dbVars();
									@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);				
									$dbConn = mysql_select_db($dbVars->strDb, $svrConn);

									// We will get this users ID from the database
									$strQuery = "select asUName, asFName, asLName from tbl_AdminSessions where asSessId = '" . session_id() . "'";
									$auResult = mysql_query($strQuery);
											
									if(mysql_num_rows($auResult) > 0)
									{
										// Now that we have the users ID, we will get his ID and make
										// it as a hidden form field value										

										$auRow = mysql_fetch_row($auResult);
										?>
											<input name="strAuth" type="text" size="65" maxlength="250" disabled value="<?php echo $auRow[1] . " " . $auRow[2]; ?>">
										<?php
										
										$strQuery = "select pk_alId from tbl_AdminLogins where alUserName = '{$auRow[0]}'";
										$aiResult = mysql_query($strQuery);
										
										if(mysql_num_rows($aiResult) > 0)
										{
											$aiRow = mysql_fetch_row($aiResult);
											?>
												<input name="intAuthorId" type="hidden" value="<?php echo $aiRow[0]; ?>">
											<?php
										}
										else
										{
											// User not found
										}
									}
									else
									{
										// No session
									}
								?>
							<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%" valign="top">
								<div align="right">
									Content:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?php PrintWYSIWYGTable(); ?>
								<input type="hidden" name="strContent">
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
								<input type="submit" value="Add News »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
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
			
				$strTitle = @$_POST["strTitle"];
				$strSource = @$_POST["strSource"];
				$strURL = @$_POST["strURL"];
				$intAuthorId = @$_POST["intAuthorId"];
				$strContent = @$_POST["strContent"];
				
				// Setup a variable to handle our errors
				$errDesc = "<ul>";
				
				if($strTitle == "")
					{ $errDesc .= "<li>You must enter a title</li>"; }
				if($strSource == "")
					{ $errDesc .= "<li>You must enter a source</li>"; }
				if($strURL == "")
					{ $errDesc .= "<li>You must enter a URL</li>"; }
				if($intAuthorId == "")
					{ $errDesc .= "<li>You must select an author</li>"; }
				if($strContent == "")
					{ $errDesc .= "<li>You must enter content</li>"; }
				if(strlen($strContent) > 1000)
					{ $errDesc .= "<li>The content for this news should be under 1,000 characters</li>"; }
				
				$errDesc .= "</ul>";
				
				if($errDesc != "<ul></ul>")
					{
						// Values are invalid
						?>
							<p style="margin-left:15; margin-right:10" class="BodyText">
								<span class="BodyHeading">Invalid Details for News Post</span><br><br>
								Some of the values that you have entered/selected for this news
								post are invalid. These are shown in the list below:<br>
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
										$strQuery  = "INSERT INTO tbl_News ";
										$strQuery .= "VALUES(0, '$intAuthorId', '$strTitle', '$strContent', '" . date("YmdHis") . "', '$strSource', '$strURL')";
										
										$result = mysql_query($strQuery);
										
										?>

										<p style="margin-left:15; margin-right:10" class="BodyText">
											<span class="BodyHeading">News Posted Successfully!</span><br><br>
											You have successfully added a news post.<br><br>
											<a href="news.php">Continue</a>													
										</p>	
										
										<?php											
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
				modified news post that we are updating in the tbl_News database.
				It will then pass the data to the UpdateFinal function of this page.
			*/
			
				$newsId = @$_GET["newsId"];
				$dbVars = new dbVars();
				
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								$strQuery = "select * from tbl_News where pk_dnId = $newsId";
								$results = mysql_query($strQuery);
								
								if($row = mysql_fetch_array($results))
									{
										// News exists, get the data from the database
									}
								else
									{
										// User doesnt exist
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">News Doesn't Exist</span><br><br>
												The news post that you have chosen to edit no longer exists in the
												database. Please use the link below to continue.<br><br>
												<a href="news.php">Continue</a>
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
										<a href="adminusers.php">Continue</a>
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
								<a href="adminusers.php">Continue</a>
							</p>
						<?php
								include("templates/dev_bottom.php");
								exit;
					
					}
			?>

				<?php include("includes/jscript/devnews.js"); ?>
				
				<form onSubmit="return CheckAddNews()" name="frmAddNews" action="news.php?strMethod=updatefinal&newsId=<?php echo $newsId; ?>" method="post">				
				<div align="center">
					<table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan="2" width="100%">
								<p class="BodyText">
									<span class="BodyHeading">Update News</span><br><br>
									Use the form below to update a new news post. Fields marked with a <span class="Error">*</span>  are required.<br><br>
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
									Title:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<input name="strTitle" type="text" size="65" maxlength="100" value="<?php echo $row["nTitle"]; ?>">
								<span class="Error">*</span>								
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									Source:&nbsp;&nbsp;
								</div>
							</td>
							<td width="80%">
								<input name="strSource" type="text" size="65" maxlength="50" value="<?php echo $row["nSource"]; ?>">
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									URL:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<input name="strURL" type="text" size="65" maxlength="250" value="<?php echo $row["nURL"]; ?>">
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%">
								<div align="right">
									User Posting:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?
									$dbVars = new dbVars();
									@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);				
									$dbConn = mysql_select_db($dbVars->strDb, $svrConn);

									// We will get this users ID from the database
									$strQuery = "select asUName, asFName, asLName from tbl_AdminSessions where asSessId = '" . session_id() . "'";
									$auResult = mysql_query($strQuery);
											
									if(mysql_num_rows($auResult) > 0)
									{
										// Now that we have the users ID, we will get his ID and make
										// it as a hidden form field value										

										$auRow = mysql_fetch_row($auResult);
										?>
											<input name="strAuth" type="text" size="65" maxlength="250" disabled value="<?php echo $auRow[1] . " " . $auRow[2]; ?>">
										<?php
										
										$strQuery = "select pk_alId from tbl_AdminLogins where alUserName = '{$auRow[0]}'";
										$aiResult = mysql_query($strQuery);
										
										if(mysql_num_rows($aiResult) > 0)
										{
											$aiRow = mysql_fetch_row($aiResult);
											?>
												<input name="intAuthorId" type="hidden" value="<?php echo $aiRow[0]; ?>">
											<?php
										}
										else
										{
											// User not found
										}
									}
									else
									{
										// No session
									}
								?>
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%" valign="top">
								<div align="right">
									Content:&nbsp;&nbsp;
								</div>							
							</td>
							<td width="80%">
								<?php PrintWYSIWYGTable($row["nContent"]); ?>
								<input type="hidden" name="strContent">
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
								<input type="submit" value="Update News »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
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
			$nId = @$_POST["nId"];
			
			$dbVars = new dbVars();			
			@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
			
			if($svrConn && is_array($nId))
				{
					$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
					if($dbConn)
						{
                            foreach($nId as $v)
                            {
								$where .= " pk_dnId = $v or";
							}
                                                                        
                            $where = ereg_replace(" or$", "", $where);
							$strQuery = "delete from tbl_News where $where";
							mysql_query($strQuery);

							?>
								<p style="margin-left:15; margin-right:10" class="BodyText">
									<span class="BodyHeading">News Post Deleted Successfully</span><br><br>
									You have successfully deleted a news post from the database.
									Use the link below to return to the news listings page.<br><br>
									<a href="news.php">Continue</a>									
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
									<a href="adminusers.php">Return</a>									
								</p>							
							<?php
						}
				}
			else
				{
					// Couldnt connect to the database server
					?>
						<p style="margin-left:15; margin-right:10" class="BodyText">
							<span class="BodyHeading">Invalud Post Id</span><br><br>
							The news id that you have selected is invalid. Please use the link below to go
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
			
				$newsId = @$_GET["newsId"];
				$strTitle = @$_POST["strTitle"];
				$strSource = @$_POST["strSource"];
				$strURL = @$_POST["strURL"];
				$intAuthorId = @$_POST["intAuthorId"];
				$strContent = @$_POST["strContent"];
				
				// Setup a variable to handle our errors
				$errDesc = "<ul>";
				
				if($strTitle == "")
					{ $errDesc .= "<li>You must enter a title</li>"; }
				if($strSource == "")
					{ $errDesc .= "<li>You must enter a source</li>"; }
				if($strURL == "")
					{ $errDesc .= "<li>You must enter a URL</li>"; }
				if($intAuthorId == "")
					{ $errDesc .= "<li>You must select an author</li>"; }
				if($strContent == "")
					{ $errDesc .= "<li>You must enter content</li>"; }
				if(strlen($strContent) > 1000)
					{ $errDesc .= "<li>The content for this news should be under 1,000 characters</li>"; }
				
				$errDesc .= "</ul>";
				
				if($errDesc != "<ul></ul>")
					{
						// Values are invalid
						?>
							<p style="margin-left:15; margin-right:10" class="BodyText">
								<span class="BodyHeading">Invalid Details for News Post</span><br><br>
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
										$strQuery  = "UPDATE tbl_News ";
										$strQuery .= "SET nAuthorId = $intAuthorId, ";
										$strQuery .= "    nTitle = '$strTitle', ";
										$strQuery .= "    nContent = '$strContent', ";
										$strQuery .= "    nSource = '$strSource', ";
										$strQuery .= "    nURL = '$strURL' ";
										$strQuery .= "WHERE pk_dnId = $newsId";
										
										mysql_query($strQuery);
										
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">News Post Updated</span><br><br>
												The selected news post was updated successfully.<br><br>
												<a href="news.php">Continue</a>												
											</p>
										<?php
									}
								else
									{
										// Couldnt connect to the database
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">Couldnt Open News Table</span><br><br>
												A connection to the database was made, however the news table couldn't be opened.
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