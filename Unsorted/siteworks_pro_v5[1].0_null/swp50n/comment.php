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
	include(realpath("templates/top.php"));

	if(!$siteComments)
		header("Location: index.php");

	switch($_POST['strMethod'])
		{

			case "Process";
				ProcessForm();
				break;

			default;
				ShowForm();
				break;

		}

	function ProcessForm()
		{

			//check for errors

			global $siteCommentsApprove, $siteName;

			$strErr = '<ul>';

			if($_POST['strName'] == '')
				$strErr .= '<li>You must provide a name for your comment</li>';

			if($_POST['strComment'] == '')
				$strErr .= '<li>You must provide a comment</li>';

			if(strlen($_POST['strComment']) > 500)
				$strErr .= '<li>Your comment can be no longer then 500 characters long</li>';

			if($_POST['strEmail'] != '')
				{

					if(!is_numeric(strpos($_POST["strEmail"], "@")) || !is_numeric(strpos($_POST["strEmail"], ".")))
						$strErr .= '<li>Please provide a valid email</li>';

				}

			$strErr .= '</ul>';

			if($strErr != '<ul></ul>')
				ShowForm($strErr);
			else
				{

					//now that every things fine, lets start on our query

					if(!$siteCommentsApprove)
						$approve = '1';
					else
						$approve = '0';

					$strQuery = "INSERT INTO tbl_Comments VALUES (0, '{$_POST['id']}', '{$_POST['type']}', '{$_POST['strName']}', now(), '{$_POST['strComment']}', '{$_POST['strEmail']}', '$approve')";

					if(mysql_query($strQuery))
						{

							//comment added successfully

							?>

								<br>
							
								<div align="center"><center>
								<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td>
											<span class="BodyHeading">Successfully Added Comment</span>
										</td>
									</tr>
									<tr>
										<td>
											<br>
										</td>
									</tr>
									<tr>
										<td class="text1">
											You have successfully added your post to the article '<strong><?php echo $_POST['name']; ?></strong>'.
											
											<?php

												//show text based apon if comment need to be approved

												if($siteCommentsApprove)
													{
		
														?>
		
															Your post will now be checked by a moderator and should be approved with 36 Hours.
															
														<?php
		
													}
												else
													{
		
														?>
		
															Your comment is now live on <?php echo $siteName; ?>
		
														<?php
		
													}

												?>

											<br><br>

											<a href="articles.php?articleId=<?php echo $_POST['id']; ?>">Continue</a>
				
										</td>
									</tr>	
								</table>
								</center></div>


							<?php

						}
					else
						{

							?>

								<br>
							
								<div align="center"><center>
								<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td>
											<span class="BodyHeading">Could not add to database</span>
										</td>
									</tr>
									<tr>
										<td>
											<br>
										</td>
									</tr>
									<tr>
										<td class="text1">
											There was an internal error while trying to post your article. Please use the button below to go back and try again.<br><br>
				
											<a href="javascript: history.go(-1);">Back</a>
				
										</td>
									</tr>	
								</table>
								</center></div>

							<?php

						}

				}

		}

	function ShowForm($error = '')
		{

			//Shows comments form for articles

			global $siteCommentsApprove, $siteName;

			if(is_numeric($_GET['id']) || is_numeric($_POST['id']))
				{

					//ensure you have a vaild id in the get field

					if(!is_numeric($_GET['id']))
						$id = $_POST['id'];
					else
						$id = $_GET['id'];

					$strQuery = mysql_query("SELECT aTitle FROM tbl_Articles WHERE pk_aId = '$id'");

					if(mysql_num_rows($strQuery) == 1)
						{

							//make sure that the id given can be linked to a valid article

							$name = mysql_result($strQuery, 0, 0);
		
							?>
							
								<br>
							
								<div align="center"><center>
								<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td>
											<span class="BodyHeading">Add Comment</span>
										</td>
									</tr>
									<tr>
										<td>
											<br>
										</td>
									</tr>
									<tr>
										<td class="text1">
											Please add your comment for the article entitled '<strong><?php echo $name ?></strong>'. 
											<?php

												//show text based apon if comment need to be approved

												if($siteCommentsApprove)
													{
		
														?>
		
															Once you have placed your comment it will have to be approved before it is made live. 
															A moderator from <?php echo $siteName; ?> should approve your post within 48 Hours.
		
														<?php
		
													}
												else
													{
		
														?>
		
															Once you have posted your comment it will be instantly live on <?php echo $siteName; ?>.
		
														<?php
		
													}

												//show errors if there is one

												if($error != '') 
													echo "<span style='color: red;'>$error</span>";
												else
													echo '<br>';

											?>

											
											
											<br>
		
											<table border="0" width="96%" cellpadding="0" cellspacing="0">
												<form method="post" action="comment.php">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
												<input type="hidden" name="type" value="<?php if($_GET['type'] != '') echo $_GET['type']; else echo $_POST['type'];?>">
												<input type="hidden" name="name" value="<?php echo $name; ?>">
												<input type="hidden" name="strMethod" value="Process">
												<tr>
													<td width="25%" class="text1"><strong>Your Name</strong></td>
													<td><input type="text" name="strName" value="" size="30"></td>
												</tr>
												<tr>
													<td width="25%" class="text1"><strong>Your&nbsp;Email</strong>&nbsp;(optional)&nbsp;&nbsp;</td>
													<td><input type="text" name="strEmail" value="" size="30"></td>
												</tr>
												<tr>
													<td width="25%" class="text1"><strong>Your Comment</strong><br>Maximum Characters: 500</td>
													<td><textarea name="strComment" cols="30" rows="7"></textarea></td>
												</tr>
												<tr>
													<td width="25%" class="text1"></td>
													<td><br></td>
												</tr>
												<tr>
													<td width="25%" class="text1"></td>
													<td><input type="submit" value="Post Comment"><br><br></td>
												</tr>
												</form>
											</table>
		
										</td>
									</tr>	
								</table>
								</center></div>
				
							<?php
		
						}
					else
						{
		
							?>
				
								<br>
							
								<div align="center"><center>
								<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td>
											<span class="BodyHeading">Invalid Article Id</span>
										</td>
									</tr>
									<tr>
										<td>
											<br>
										</td>
									</tr>
									<tr>
										<td class="text1">
											The article that you have selected to post a comment for has an invalid 
											article Id. Please use the button below and select another article.<br><br>
				
											<a href="javascript: history.go(-1);">Back</a>
				
										</td>
									</tr>	
								</table>
								</center></div>
				
							<?php
		
						}
		
				}
			else
				{
		
					?>
		
						<br>
					
						<div align="center"><center>
						<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>
							<tr>
								<td>
									<span class="BodyHeading">Invalid Article Id</span>
								</td>
							</tr>
							<tr>
								<td>
									<br>
								</td>
							</tr>
							<tr>
								<td class="text1">
									The article that you have selected to post a comment for has an invalid 
									article Id. Please use the button below and select another article.<br><br>
		
									<a href="javascript: history.go(-1);">Back</a>
		
								</td>
							</tr>	
						</table>
						</center></div>
		
					<?php
		
				}

		}

	 include(realpath("templates/bottom.php")); 

?>