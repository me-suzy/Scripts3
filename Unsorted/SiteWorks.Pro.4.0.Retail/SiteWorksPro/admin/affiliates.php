<?php include("templates/dev_top.php"); ?>

	<?php
	
		/*
			Firstly, we will make sure that the user is a content modifier
			(has an IsLoggedIn value of 2 or more). If they arent, we will redirect them
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
						if(in_array("add_affiliates", $publisherAccess))
							ProcessNew();
						else
							NoAccess();
					 else
						ProcessNew();
					break;
				}
				case "addnew1":
					ProcessNewContent();
					break;
				case "delete":
				{
					 if($userStatus < 3)
						if(in_array("delete_affiliates", $publisherAccess))
							DelAffiliate();
						else
							NoAccess();
					 else
						DelAffiliate();
					break;
				}
				case "update":
				{
					 if($userStatus < 3)
						if(in_array("edit_affiliates", $publisherAccess))
							UpdateAff();
						else
							NoAccess();
					 else
						UpdateAff();
					break;
				}
				default:
				{
					 if($userStatus < 3)
						if(in_array("view_affiliates", $publisherAccess))
							ShowAffiliateList();
						else
							NoAccess();
					 else
						ShowAffiliateList();
					break;
				}
			}

		function ShowAffiliateList()
			{
			
				/*
					This function will display a list of the affiliates in the database and
					provide a link for the current user to update the affiliate if needed
				*/

				global $recsPerPage;
				global $siteName;
				
				$page = @$_GET["page"];
				$start = @$_GET["start"];
				
				?>
					<?php include("includes/jscript/affiliates.js"); ?>
					
					<form onSubmit="return ConfirmDelAff()" name="frmUpdateAff" action="affiliates.php?strMethod=delete" method="post">
					<div align="center">
						<table width="95%" border="0" cellspacing="0" cellpading="0">
							<tr>
								<td width="100%" colspan="3">
									<span class="BodyHeading"><?php echo $siteName; ?> Affiliates</span>
								</td>
							</tr>
							<?php
							
								/*
									We will display a list of affiliates with the ability to modify them
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
												$numRows = mysql_num_rows(mysql_query("select pk_aId from tbl_Affiliates"));

												$strQuery = "select * from tbl_Affiliates order by aName asc limit $start, $recsPerPage";
												$results = mysql_query($strQuery);
												$bgColor = "#ECECEC";
												
												?>
												<tr>
													<td width="100%" height="20" colspan="3" align="right" class="BodyText" valign="top">
													<?php

														if($page > 1)
														  $nav .= "<a href='affiliates.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

														for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
														  if($i == $page)
														    $nav .= "<a href='affiliates.php?page=$i'><b>$i</b></a> | ";
														  else
														    $nav .= "<a href='affiliates.php?page=$i'>$i</a> | ";
																					  
														if(($start+$recsPerPage) < $numRows && $numRows > 0)
														  $nav .= "<a href='affiliates.php?page=" . ($page+1) . "'><u>Next »</u></a>";
																					
														if(substr(strrev($nav), 0, 2) == " |")
														  $nav = substr($nav, 0, strlen($nav)-2);
																					  
														echo $nav . "<br>&nbsp;";
													?>
													</td>
												</tr>
												<tr>
												  <td width="5%" bgcolor="#333333" height="21">
												  </td>
												  <td width="40%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Affiliate
												    Name</span>
												  </td>
												  <td width="55%" bgcolor="#333333" height="21">
												    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Affiliate
												    URL</span>
												  </td>
												  
												</tr>
							
												<?php
												
												if($numRows == 0)
												{
												?>
													<tr>
													  <td width="100%" bgcolor="#FFFFFF" height="21" colspan="3">
														<span class="BodyText">No affiliates found in the database.</span>
													  </td>
													</tr>
												<?php
												}
												
												while($row = mysql_fetch_row($results))
													{
														$bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
														?>
															<tr>
																<td width="5%" height="28" bgcolor="<?php echo $bgColor; ?>">
																	&nbsp;&nbsp;<input type="checkbox" name="aId[]" value="<?php echo $row[0]; ?>">
																</td>
																<td width="40%" height="28" bgcolor="<?php echo $bgColor; ?>">
																	<p style="margin-left:10">
																		<input name="strAff_<?php echo $row[0]; ?>" type="text" size="30" maxlength="50" value="<?php echo $row[1]; ?>">
																</td>
																<td width="55%" height="28" bgcolor="<?php echo $bgColor; ?>">
																	<input name="strURL_<?php echo $row[0]; ?>" type="text" size="30" maxlength="50" value="<?php echo $row[2]; ?>">
																	<input onClick="JumpToUpdateAff(<?php echo $row[0]; ?>, frmUpdateAff.strAff_<?php echo $row[0]; ?>.value, frmUpdateAff.strURL_<?php echo $row[0]; ?>.value)" type="button"  style="font-size: 8pt; font-family: Verdana; font-weight: normal" value="Update »">
																</td>
															</tr>
														<?php
													}
												if($numRows > 0)
												{
												?>
												<tr>
													<td width="45%" colspan="2">
														<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
													</td>
													<td width="55%" height="20" colspan="1" align="right" class="BodyText" valign="top">
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
							
							</table>
							<table width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
							<tr>
							  <td colspan="2" width="20%" height="21">
								<br><input onClick="JumpToAddAff()" type="button" value="Add Affiliate »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
							  </td>
							  <td colspan="2" width="80%" height="21">
							    <div align="right">
									<br><a href="#top">^ Top</a>
							    </div>
							  </td>
							</tr>
						</table>
						<br>&nbsp;
					</div>
					</form>
				<?php
			}
		
		function ProcessNew()
		{
			/*
				This function will allow the user to add a new affiliate
				to the tbl_Affiliates table
			*/
			?>
					
				<?php include("includes/jscript/affiliates.js"); ?>
					
				<form onSubmit="return CheckAddAff()" name="frmAddNewAff" action="affiliates.php" method="post">
				<input type="hidden" name="strMethod" value="addnew1">
				<div align="center">					
					<table width="95%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="100%" colspan="2">
								<p class="BodyText">
									<span class="BodyHeading">Add Affiliate</span><br><br>
									Use the form below to an affiliate site. Click on the Add Affiliate
									button shown below to update the database with this affiliate.<br><br>
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
									Affiliate Name:&nbsp;&nbsp;										
								</div>
							</td>
							<td width="80%">
								<input name="strName" type="text" maxlength="50" size="65">
								<span class="Error">*</span>
							</td>
						</tr>
						<tr>
							<td width="20%" class="BodyText">
								<div align="right">
									Affiliate URL:&nbsp;&nbsp;										
								</div>
							</td>
							<td width="80%">
								<input name="strURL" type="text" maxlength="250" size="65">
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
								<input type="submit" value="Add Affiliate »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
							</td>
						</tr>
					</table>
				</div>
				</form>
					
			<?php
		}
		
		function ProcessNewContent()
		{
			/*
				This function will add the new category to the database
				if it doesnt already exist.
			*/
				
			$strName = @$_POST["strName"];
			$strURL = @$_POST["strURL"];
				
			$strName = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strName));
			$strURL = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strURL));
				
			if($strName == "" || $strURL == "")
				{
					?>
						<p style="margin-left:15; margin-right:10" class="BodyText">
							<span class="BodyHeading">An Error Occured</span><br><br>
							It appears that you have not completed all fields required
							to add a new affiliate. Please use the link below to provide
							all details required.<br><br>
							<a href="javascript:history.go(-1)">Go Back</a>
						</p>						
					<?php
				}
			else
				{
					// Everything is there, we can add the new affiliate to the database
					$dbVars = new dbVars();
					@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
					if($svrConn)
						{
							// Connected to the database server ok
							$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
							if($dbConn)
								{
									$strQuery = "select count(*) from tbl_Affiliates where aName = '$strName' and aURL = '$strURL'";
									$results = mysql_query($strQuery);
									$result = mysql_fetch_row($results);
										
									if($result[0] > 0)
										{
											// This affiliate already exists in the database
											?>
												<p style="margin-left:15; margin-right:10" class="BodyText">
													<span class="BodyHeading">Affiliate Already Exists</span><br><br>
													It appears that this affiliate already exists in the database.
													You can modify the affiliate by using the link below.<br><br>
													<a href="affiliates.php">Continue</a>
												</p>
											<?											
										}
									else
										{
											// This affiliate doesnt exist, we can add it
											$strQuery = "insert into tbl_Affiliates VALUES(0, '$strName', '$strURL')";

											if(mysql_query($strQuery))
												{
													// Affiliate added successfully
													?>
														<p style="margin-left:15; margin-right:10" class="BodyText">
															<span class="BodyHeading">Affiliate Added</span><br><br>
															The affiliate that you entered has been successfully added
															to the database. Use the link below to return to the affiliate page.<br><br>
															<a href="affiliates.php">Continue</a>
														</p>
															<?php
												}
											else
												{
													// An error occured adding this affiliate
													?>
														<p style="margin-left:15; margin-right:10" class="BodyText">
															<span class="BodyHeading">An Error Occured</span><br><br>
															An error occured while trying to add this affiliate to the
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
											An error occured while trying to add this affiliate to the
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
									An error occured while trying to add this affiliate to the
									database. Please use the link below to go back and try again.<br><br>
									<a href="javascript:history.go(-1)">GO Back</a>
								</p>
							<?php
						}
				}
		}

		function DelAffiliate()
			{
				/*
					This function simply takes the pk_aId value parsed from our
					form as aId and deletes the affiliate that matches this Id
				*/
				
				$aId = @$_POST["aId"];
				
				if(!is_array($aId))
					{
						?>
							<p style="margin-left:15; margin-right:10" class="BodyText">
								<span class="BodyHeading">Invalid Affiliate Id</span><br><br>
								The affiliate that you have chosen to delete is invalid or no longer
								exists in the database. Please use the link below to go back and try again.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>					
						<?php
					}
				else
					{
						// We have a valid affiliate that we can remove from the database
						$dbVars = new dbVars();
						@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
						if($svrConn)
							{
								// Connected to the database server OK
								$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
								if($dbConn)
									{
										// Execute our DELETE FROM query
										foreach($aId as $v)
										{
											$where .= " pk_aId = $v or";
										}
										                                            
										$where = ereg_replace(" or$", "", $where);
										$strQuery = "delete from tbl_Affiliates where $where";
										
										mysql_query($strQuery);
										?>
											<p style="margin-left:15; margin-right:20" class="BodyText">
												<span class="BodyHeading">Affiliate Deleted OK</span><br><br>
												The selected affiliate has been successfully removed from the
												database. Please use the link below to continue.<br><br>
												<a href="affiliates.php">Continue</a>
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
			
		function UpdateAff()
			{
				/*
					This function will take the details for the category parsed and
					update its values in the tbl_Topics table
				*/
				
				$aId = @$_GET["aId"];
				$aName = @$_GET["aName"];
				$aURL = @$_GET["aURL"];
				
				$aName = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $aName));
				$aURL = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $aURL));
				
				if( (!is_numeric($aId)) || ($aName == "") || ($aURL == "") )
					{
						?>
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								It appears that you have not completed all fields required
								to modify this affiliate. Please use the link below to provide
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
										$strQuery = "select pk_aId from tbl_Affiliates where aName='$aName'";
										$aResult = mysql_query($strQuery);
										
										if(mysql_num_rows($aResult) > 1)
										{
										?>
											<p style="margin-left:15; margin-right:10" class="BodyText">
												<span class="BodyHeading">Affiliate Already Exists</span><br><br>
												It appears that this affiliate already exists in the database.
												You can modify the affiliate by using the link below.<br><br>
												<a href="affiliates.php">Continue</a>
											</p>
										<?php
										}
										else
										{
											// Execute our query
											$strQuery = "update tbl_Affiliates set aName='$aName', aURL='$aURL' where pk_aId = $aId";
											mysql_query($strQuery);
											?>
												<p style="margin-left:15; margin-right:20" class="BodyText">
													<span class="BodyHeading">Affiliate Updated OK</span><br><br>
													The selected affiliate has been updated successfully.
													Please use the link below to continue.<br><br>
													<a href="affiliates.php">Continue</a>
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