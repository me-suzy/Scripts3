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
	require_once(realpath("includes/php/functions.php"));

	// This script takes the users vote and adds it to the
	// tbl_PollVotes table. That's it.
	
	$pollId = @$_POST["pollId"];
	$vote = @$_POST["vote"];
	
	if($pollId == "" || $vote == "")
	{
		header("Location: index.php?poll_$pollId=1");
	}
	else
	{
		// Check whether or not this user has already voted
		// by checking the tbl_PollIP table and checking if
		// there is a cookie on this users machine.
		
		$ip = @$_SERVER["REMOTE_ADDR"];
		
		$iResult = mysql_query("select count(*) from tbl_PollAnswers where paPollId = '$pollId' and paVisitorIP = '$ip'");
		$iRow = mysql_fetch_row($iResult);
		
		if($iRow[0] > 0)
		{
			// This user has already voted
			header("Location: index.php?poll_$pollId=1");
		}
		else
		{
			// Is there a cookie set for this users poll vote?
			if(@$_COOKIE["poll_$pollId"] != "")
			{
				header("Location: index.php?poll_$pollId=1");
			}
			else
			{
				// This user hasn't voted. We will add his vote and then
				// add a record to tbl_PollIPs and also set a cookie
				
				// Are we working with a single/multi answer poll?
				if(!is_array($vote))
				{
					$query = "insert into tbl_PollAnswers values(0, '$pollId', '$vote', '$ip')";
					@mysql_query($query);
				}
				else
				{
					for($i = 0; $i < sizeof($vote); $i++)
					{
						$query = "insert into tbl_PollAnswers values(0, '$pollId', '" . $vote[$i] . "', '$ip')";
						@mysql_query($query);
					}
				}

				setcookie("poll_$pollId", "1", time() + 3600 * 24 * 30);
				?>
					<html>
						<head>
							<meta http-equiv="refresh" content="0; url=index.php">
						</head>
					</html>
				<?php
			}
		}
	}
	
?>