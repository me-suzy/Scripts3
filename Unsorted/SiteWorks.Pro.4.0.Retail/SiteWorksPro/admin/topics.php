<?php include("templates/dev_top.php"); ?>

	<?php
	
		/*
			Firstly, we will make sure that the user is a super-administrator
			(has an IsLoggedIn value of 3). If they arent, we will redirect them
			to the main page.
		*/
		
		$userStatus = IsLoggedIn();
		
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
						if(in_array("add_topics", $publisherAccess))
							ProcessNew();
						else
							NoAccess();
					 else
						ProcessNew();
					break;
				}
				case "updatenew":
				{
					 if($userStatus < 3)
						if(in_array("edit_topics", $publisherAccess))
							UpdateNew();
						else
							NoAccess();
					 else
						UpdateNew();
					break;
				}
				case "delete":
				{
					 if($userStatus < 3)
						if(in_array("delete_topics", $publisherAccess))
							DeleteCat();
						else
							NoAccess();
					 else
						DeleteCat();
					break;
				}
				case "update":
				{
					 if($userStatus < 3)
						if(in_array("edit_topics", $publisherAccess))
							UpdateCat();
						else
							NoAccess();
					 else
						UpdateCat();
					break;
				}
				default:
				{
					 if($userStatus < 3)
						if(in_array("view_topics", $publisherAccess))
							ShowCatList();
						else
							NoAccess();
					 else
						ShowCatList();
					break;
				}
			}
			
		function ShowCatList()
			{
				/*
					This function displays a list of topics which are stored in the
					tbl_Topics table. These are used to logically categorise articles
					so that site navigation is easy and hassle free for visitors.
				*/
				
				global $recsPerPage;
				global $siteName;
				global $start;
				
				$page = @$_GET["page"];
				
				?>
					<?php include("includes/jscript/categories.js"); ?>
					
					<form onSubmit="return ConfirmDelCat()" name="frmUpdateTopic" action="topics.php?strMethod=delete" method="post">
					<div align="center">
						<table width="95%" border="0" cellspacing="0" cellpading="0">
							<tr>
								<td width="100%" colspan="2">
									<span class="BodyHeading"><?php echo $siteName; ?> Article Topics</span>
								</td>
							</tr>
							<?php
							
								/*
									We will display a list of topics with the ability to modify them
									from the same page and post the results back to the server.								
								*/
								
								$dbVars = new dbVars();
								@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
								
								if($svrConn)
									{
										// Connected OK
										$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
										
										if($dbConn)
											{
												if(!is_numeric($page) || $page < 1)
													$page = 1;
													
												if($page == 1)
													$start = 0;
												else
													$start = ($page * $recsPerPage) - $recsPerPage;
													
												// Get the number of records in the table
												$numRows = mysql_num_rows(mysql_query("select pk_tId from tbl_Topics"));

												$strQuery = "select * from tbl_Topics order by tName asc limit $start, $recsPerPage";
												$results = mysql_query($strQuery);
												$bgColor = "#ECECEC";
												
												?>
												<tr>
													<td width="100%" height="20" colspan="3" align="right" class="BodyText" valign="top">
													<?php

														if($page > 1)
														  $nav .= "<a href='topics.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

														for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
														  if($i == $page)
														    $nav .= "<a href='topics.php?page=$i'><b>$i</b></a> | ";
														  else
														    $nav .= "<a href='topics.php?page=$i'>$i</a> | ";
														  
														if(($start+$recsPerPage) < $numRows && $numRows > 0)
														  $nav .= "<a href='topics.php?page=" . ($page+1) . "'><u>Next »</u></a>";
														
														if(substr(strrev($nav), 0, 2) == " |")
														  $nav = substr($nav, 0, strlen($nav)-2);
														  
														echo $nav . "<br>&nbsp;";
													?>
													</td>
												</tr>
												<tr>
												  <td width="1%" bgcolor="#333333" height="21">
												  </td>
												  <td width="60%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Topic
												    Name</span></td>
												  <td width="39%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading"></span></td>
												</tr>
							
												<?php
												
												if($numRows == 0)
												{
												?>
													<tr>
													  <td width="100%" bgcolor="#FFFFFF" height="21" colspan="2">
														<span class="BodyText">No topics found in the database.</span>
													  </td>
													</tr>
												<?php
												}
												
												while($row = mysql_fetch_row($results))
													{
														$bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
														?>
															<tr>
																<td width="1%" height="28" bgcolor="<?php echo $bgColor; ?>" align="center">
																	<input type="checkbox" name="tId[]" value="<?php echo $row[0]; ?>">
																</td>
																<td width="60%" height="28" bgcolor="<?php echo $bgColor; ?>">
																	<p style="margin-left:10">
																		<input name="strTopic_<?php echo $row[0]; ?>" type="text" size="65" maxlength="50" value="<?php echo $row[1]; ?>">
																</td>
																<td width="39%" height="28" bgcolor="<?php echo $bgColor; ?>">
																	<input onClick="JumpToUpdateTopic(<?php echo $row[0]; ?>, frmUpdateTopic.strTopic_<?php echo $row[0]; ?>.value)" type="button"  style="font-size: 8pt; font-family: Verdana; font-weight: normal" value="Update »">
																</td>
															</tr>
														<?php
													}
													
												if($numRows > 0)
												{
												?>
												<tr>
													<td width="61%" colspan="2">
														<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
													</td>
													<td width="39%" height="20" colspan="1" align="right" class="BodyText" valign="top">
													<br>
													<?php echo $nav . "<br>&nbsp;"; ?>
													</td>
												</tr>
												<?php
												}
											}
										else
											{
												// Couldnt connect to database
											
											}
									}
								else
									{
										// Couldnt connect to the database server
									
									}
							?>
							
							<tr>
								<td colspan="4" width="100%">
									&nbsp;
								</td>
							</tr>
							</table>
							<table width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
							<tr>
							  <td colspan="2" width="20%" height="21">
								<input onClick="JumpToAddCat()" type="button" value="Add Topic »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
							  </td>
							  <td colspan="2" width="80%" height="21">
							    <div align="right">
									<a href="#top">^ Top</a>
							    </div>
							  </td>
							</tr>
						</table>
					</div>
					</form>
				
				<?php
			}
			
		function ProcessNew()
			{
				/*
					This function will allow the user to add a new category/topic
					to the tbl_Topics table
				*/
				?>
					
					<?php include("includes/jscript/categories.js"); ?>
					
					<form onSubmit="return CheckAddCat()" name="frmAddNewCat" action="topics.php" method="post">
					<input type="hidden" name="strMethod" value="updatenew">
					<div align="center">					
						<table width="95%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="100%" colspan="2">
									<p class="BodyText">
										<span class="BodyHeading">Add Topic</span><br><br>
										Use the form below to add a topic under which articles
										can be categorized. Click on the Add Topic
										button shown below to update the database with this topic.<br><br>
									</p>
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2" background="doth.gif">
									&nbsp;
								</td>
							</tr>
							<tr>
								<td width="20%" class="BodyText">
									<div align="right">
										Topic Name:&nbsp;&nbsp;										
									</div>
								</td>
								<td width="80%">
									<input name="strName" type="text" maxlength="50" size="65">
									<span class="Error">*</span>
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2" class="BodyText" height="10">
								</td>
							</tr>
							<tr>
								<td width="20%" class="BodyText">
									&nbsp;
								</td>
								<td width="80%">
									<input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
									<input type="submit" value="Add Topic »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
								</td>
							</tr>
						</table>
					</div>
					</form>
					
				<?php
			}
			
		function UpdateNew()
			{
				/*
					This function will add the new category to the database
					if it doesnt already exist.
				*/
				
				$strName = @$_POST["strName"];
				
				$strName = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strName));
				
				if($strName == "")
					{
						?>
							<p style="margin-left:15; margin-right:10" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								It appears that you have not completed all fields required
								to add a new topic. Please use the link below to provide
								all details required.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>						
						<?php
					}
				else
					{
						// Everything is there, we can add the new topic to the database
						$dbVars = new dbVars();
						@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
						if($svrConn)
							{
								// Connected to the database server ok
								$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
								if($dbConn)
									{
										$strQuery = "select count(*) from tbl_Topics where tName = '$strName'";
										$results = mysql_query($strQuery);
										$result = mysql_fetch_row($results);
										
										if($result[0] > 0)
											{
												// This topic already exists in the database
												?>
													<p style="margin-left:15; margin-right:10" class="BodyText">
														<span class="BodyHeading">Topic Already Exists</span><br><br>
														It appears that this topic already exists in the database.
														You can modify the topic by using the link below.<br><br>
														<a href="topics.php">Continue</a>
													</p>
												<?											
											}
										else
											{
												// This category doesnt exist, we can add it
												$strQuery = "insert into tbl_Topics VALUES(0, '$strName')";

												if(mysql_query($strQuery))
													{
														// Topic added successfully
														?>
															<p style="margin-left:15; margin-right:10" class="BodyText">
																<span class="BodyHeading">Topic Added</span><br><br>
																The topic that you entered has been successfully added
																to the database. Use the link below to return to the topic page.<br><br>
																<a href="topics.php">Continue</a>
															</p>
																<?php
													}
												else
													{
														// An error occured adding this topic
														?>
															<p style="margin-left:15; margin-right:10" class="BodyText">
																<span class="BodyHeading">An Error Occured</span><br><br>
																An error occured while trying to add this topic to the
																database. Please use the link below to go back and try again.<br><br>
																<a href="javascript:history.go(-1)">GO Back</a>
															</p>
														<?php
													}
											}
									}
								else
									{
										// Couldnt connect to the database
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">An Error Occured</span><br><br>
												An error occured while trying to add this topic to the
												database. Please use the link below to go back and try again.<br><br>
												<a href="javascript:history.go(-1)">GO Back</a>
											</p>
										<?php
									}
							}
						else
							{
								// Couldnt connect to the database server
								?>
									<p style="margin-left:15; margin-right:10" class="BodyText">
										<span class="BodyHeading">An Error Occured</span><br><br>
										An error occured while trying to add this topic to the
										database. Please use the link below to go back and try again.<br><br>
										<a href="javascript:history.go(-1)">GO Back</a>
									</p>
								<?php
							}
					}
			
			}
			
		function DeleteCat()
			{
				/*
					This function simply takes the pk_tId value parsed from our
					form as tId and deletes the category that matches this Id
				*/
				
				$tId = $_POST["tId"];
				
				if(!is_array($tId))
					{
						?>
							<p style="margin-left:15; margin-right:10" class="BodyText">
								<span class="BodyHeading">Invalid Topic Ids</span><br><br>
								The topics that you have chosen to delete are invalid or no longer
								exist in the database. Please use the link below to go back and try again.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>					
						<?php
					}
				else
					{
						// We have a valid topic that we can remove from the database
						$dbVars = new dbVars();
						@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
						if($svrConn)
							{
								// Connected to the database server OK
								$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
								if($dbConn)
									{
										// Execute our DELETE FROM query
                                        foreach($tId as $v)
                                        {
											$where .= " pk_tId = $v or";
										}
                                                                        
                                        $where = ereg_replace(" or$", "", $where);
										$strQuery = "delete from tbl_Topics where $where";

										mysql_query($strQuery);
										?>
											<p style="margin-left:15; margin-right:20" class="BodyText">
												<span class="BodyHeading">Topic Deleted OK</span><br><br>
												The selected topic has been successfully removed from the
												database. Please use the link below to continue.<br><br>
												<a href="topics.php">Continue</a>
											</p>
										<?php
									}
								else
									{
										// Couldnt connect to the database
										?>
											<p style="margin-left:15; margin-right:20" class="BodyText">
												<span class="BodyHeading">An Error Occured</span><br><br>
												An error occured while trying to connect to to the
												database. Please use the link below to go back and try again.<br><br>
												<a href="javascript:history.go(-1)">Continue</a>
											</p>
										<?php
									}
							}
						else
							{
								// Couldnt connect to the database server
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading">An Error Occured</span><br><br>
										An error occured while trying to connect to to the
										database. Please use the link below to go back and try again.<br><br>
										<a href="javascript:history.go(-1)">Continue</a>
									</p>								
								<?php
							}
					}
			}
			
		function UpdateCat()
			{
				/*
					This function will take the details for the category parsed and
					update its values in the tbl_Topics table
				*/
				
				$tId = @$_GET["tId"];
				$tName = @$_GET["tName"];
				
				$tName = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $tName));
				
				if( (!is_numeric($tId)) || ($tName == "") )
					{
						?>
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								It appears that you have not completed all fields required
								to modify this topic. Please use the link below to provide
								all details required.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>						
						<?php
					}
				else
					{
						// We can update the category data
						$dbVars = new dbVars();
						@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
						if($svrConn)
							{
								// Connected to the database server OK
								$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
								if($dbConn)
									{
										// Does this topic already exist in the database?
										$strQuery = "select pk_tId from tbl_Topics where tName='$tName'";
										$eResult = mysql_query($strQuery);
										
										if(mysql_num_rows($eResult) > 1)
										{
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">Topic Already Exists</span><br><br>
												It appears that this topic already exists in the database.
												You can modify the topic by using the link below.<br><br>
												<a href="topics.php">Continue</a>
											</p>
										<?php
										}
										else
										{
											// Execute our query
											$strQuery = "update tbl_Topics set tName='$tName' where pk_tId = $tId";
											mysql_query($strQuery);
											?>
												<p style="margin-left:15; margin-right:20" class="BodyText">
													<span class="BodyHeading">Topic Updated OK</span><br><br>
													The selected topic has been updated successfully.
													Please use the link below to continue.<br><br>
													<a href="topics.php">Continue</a>
												</p>
											<?php
										}
									}
								else
									{
										// Couldnt connect to the database
										?>
											<p style="margin-left:15; margin-right:20" class="BodyText">
												<span class="BodyHeading">An Error Occured</span><br><br>
												An error occured while trying to connect to to the
												database. Please use the link below to go back and try again.<br><br>
												<a href="javascript:history.go(-1)">Continue</a>
											</p>
										<?php
									}
							}
						else
							{
								// Couldnt connect to the database server
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading">An Error Occured</span><br><br>
										An error occured while trying to connect to to the
										database. Please use the link below to go back and try again.<br><br>
										<a href="javascript:history.go(-1)">Continue</a>
									</p>								
								<?php
							}
					}
			}
	?>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<?php include("templates/dev_bottom.php"); ?>