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
				case "show2cents":
				{
					 if($userStatus < 3)
						if(in_array("edit_2cents", $publisherAccess))
							Show2Cents();
						else
							NoAccess();
					 else
						Show2Cents();
					break;
				}
				case "showtip":
				{
					 if($userStatus < 3)
						if(in_array("edit_tips", $publisherAccess))
							ShowTip();
						else
							NoAccess();
					 else
						ShowTip();
					break;
				}
				case "update2cents":
					Update2Cents();
					break;
				case "updatetip":
					UpdateTip();
					break;
				default:
					break;
			}
			
		function Show2Cents()
		{
		
			global $showMy2c;
				/*
					Firstly we will connect to the database and get the values
					of the current news items (if any)... if the nType value is
					1 then its a My 2 Cents post. If its 2 its a hand tip post.
				*/
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								// Connected to the database OK
								
								// Get the latest 2 cents post
								$strQuery = "select * from tbl_Personal where nType = 1 order by pk_nId desc limit 1";
								$results = mysql_query($strQuery);
								
								if($row = mysql_fetch_array($results))
								{
									$strCents = $row[1];
								}
								else
									{ $strCents = ""; }
							}
						else
							{
								// Couldnt connect to the database
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading">An Error Occured</span><br><br>
										An error occured while trying to connect to to the
										database. Please use the link below to go try again.<br><br>
										<a href="personalcontent.php?strMethod=show2cents">Continue</a>
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
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								An error occured while trying to connect to to the
								database server. Please use the link below to go try again.<br><br>
								<a href="personalcontent.php?strMethod=show2cents">Continue</a>
							</p>						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
			
				?>
					<?php include(realpath("includes/jscript/personal.js")); ?>
					<div align="center">
					<table width="95%" cellspacing="0" cellpadding="0" border="0">
						<form onSubmit="return CheckUpdate2Cents()" name="frmUpdate2Cents" action="personalcontent.php" method="post">
						<input type="hidden" name="strMethod" value="update2cents">
						<tr>
							<td width="100%" class="BodyText">
								<span class="BodyHeading">Update "<?php echo $showMy2c; ?>"</span><br><br>
								The contents of the current "<?php echo $showMy2c; ?>" column is shown in the
								textbox below. To update it, just change the text and click on
								the "Update '<?php echo $showMy2c; ?>'" button below.<br><br>
								<?php PrintWYSIWYGTable($strCents, 540, 150); ?>
								<input type="hidden" name="strCents">
								<br><div align="right"><input type="submit" value="Update '<?php echo $showMy2c; ?>' »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">&nbsp;&nbsp;<br><br>
							</td>
						</tr>
						</form>
					</table>
					</div>
				<?php
		}
		
		function ShowTip()
		{
		
			global $showHandy;
				/*
					Firstly we will connect to the database and get the values
					of the current news items (if any)... if the nType value is
					1 then its a My 2 Cents post. If its 2 its a hand tip post.
				*/
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								// Connected to the database OK
								
								// Get the latest 2 cents post
								$strQuery = "select * from tbl_Personal where nType = 2 order by pk_nId desc limit 1";
								$results = mysql_query($strQuery);
								
								if($row = mysql_fetch_array($results))
								{
									$strTip = $row[1];
								}
								else
									{ $strTip = ""; }
							}
						else
							{
								// Couldnt connect to the database
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading">An Error Occured</span><br><br>
										An error occured while trying to connect to to the
										database. Please use the link below to go try again.<br><br>
										<a href="personalcontent.php?strMethod=showtip">Continue</a>
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
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								An error occured while trying to connect to to the
								database server. Please use the link below to go try again.<br><br>
								<a href="personalcontent.php?strMethod=showtip">Continue</a>
							</p>						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
			
				?>
					<?php include(realpath("includes/jscript/personal.js")); ?>
					
					<div align="center">
					<table width="95%" cellspacing="0" cellpadding="0" border="0">
						<form onSubmit="return CheckUpdateTip()" name="frmUpdateTip" action="personalcontent.php" method="post">
						<input type="hidden" name="strMethod" value="updatetip">
						<tr>
							<td width="100%" class="BodyText">
								<span class="BodyHeading">Update <?php echo $showHandy; ?></span><br><br>
								The contents of the current hand tip column is shown in the
								textbox below. To update it, just change the text and click on
								the "Update <?php echo $showHandy; ?>" button below.<br><br>
								<?php PrintWYSIWYGTable($strTip, 540, 150); ?>
								<input type="hidden" name="strTip">
								<br><div align="right"><input type="submit" value="Update <?php echo $showHandy; ?> »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">&nbsp;&nbsp;<br><br>
							</td>
						</tr>
						</form>
					</table>
					</div>
				<?php
		}
			
		
		function Update2Cents()
			{
			
				global $showMy2c;
				/*
					This function will update the "My 2 Cents" row of the tbl_Personal database				
				*/
				
				$strCents = @$_POST["strCents"];
				
				if(strlen($strCents) > 5000)
					{
						?>
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">Invalid "<?php echo $showMy2c; ?>" News</span><br><br>
								You have entered a <?php echo $showMy2c; ?> news tip that is longer than 5,000
								characters. Please use the button below to remove <?php echo strlen($strCents)-5000 ?>
								characters from it. Please use the link below to go try again.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>						
						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								// Connected to the database OK
								
								// Get the latest 2 cents post
								$strQuery = "select count(*) from tbl_Personal where nType = 1";
								$results = mysql_query($strQuery);
								$row = mysql_fetch_row($results);
								
								if($row[0] > 0)
									{ $strQuery = "update tbl_Personal set nValue = '$strCents' where nType = 1"; }
								else
									{ $strQuery = "insert into tbl_Personal VALUES(0, '$strCents', 1)"; }

								mysql_query($strQuery);
								
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading"><?php echo $showMy2c; ?> Updated</span><br><br>
										The current "<?php echo $showMy2c; ?>" tip has been successfully updated.
										Please use the link below to continue.<br><br>
										<a href="personalcontent.php?strMethod=show2cents">Continue</a>
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
										database. Please use the link below to go try again.<br><br>
										<a href="personalcontent.php?strMethod=show2cents">Continue</a>
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
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								An error occured while trying to connect to to the
								database server. Please use the link below to go try again.<br><br>
								<a href="personalcontent.php?strMethod=show2cents">Continue</a>
							</p>						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
			}

		function UpdateTip()
			{
				/*
					This function will update todays tip in the tbl_Personal database				
				*/

				global $showHandy;

				$strTip = @$_POST["strTip"];
				
				if(strlen($strTip) > 5000)
					{
						?>
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">Invalid "<?php echo $showHandy; ?>" Post</span><br><br>
								You have entered a tip that is longer than 5,000
								characters. Please use the button below to remove <?php echo strlen($strTip)-5000 ?>
								characters from it. Please use the link below to go try again.<br><br>
								<a href="javascript:history.go(-1)">Go Back</a>
							</p>						
						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
						if($dbConn)
							{
								// Connected to the database OK
								
								// Get the latest 2 cents post
								$strQuery = "select count(*) from tbl_Personal where nType = 2";
								$results = mysql_query($strQuery);
								$row = mysql_fetch_row($results);
								
								if($row[0] > 0)
									{ $strQuery = "update tbl_Personal set nValue = '$strTip' where nType = 2"; }
								else
									{ $strQuery = "insert into tbl_Personal VALUES(0, '$strTip', 2)"; }

								mysql_query($strQuery);
								
								?>
									<p style="margin-left:15; margin-right:20" class="BodyText">
										<span class="BodyHeading"><?php echo $showHandy; ?> Updated</span><br><br>
										"<?php echo $showHandy; ?>" has been successfully updated.
										Please use the link below to continue.<br><br>
										<a href="personalcontent.php?strMethod=showtip">Continue</a>
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
										database. Please use the link below to go try again.<br><br>
										<a href="personalcontent.php?strMethod=showtip">Continue</a>
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
							<p style="margin-left:15; margin-right:20" class="BodyText">
								<span class="BodyHeading">An Error Occured</span><br><br>
								An error occured while trying to connect to to the
								database server. Please use the link below to go try again.<br><br>
								<a href="personalcontent.php?strMethod=showtip">Continue</a>
							</p>						
						<?php
								include(realpath("templates/dev_bottom.php"));
								exit;
					}
			}
		
	?>
		
		
		
		
		
		
		
		
		<?php include("templates/dev_bottom.php"); ?>
		