<?
require("config.php");

if ($action=="prev")
	{
		$sql = "SELECT * FROM $db_table WHERE id<$id AND QUEUE ='1' ORDER BY id DESC LIMIT 1";
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
			{
				$prev = $row["url"];
			}

		if($prev)
			{
				// forward to previous site
				echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$prev\">";
			}

		else
			{
				$sql = "SELECT * FROM $db_table WHERE QUEUE ='1' ORDER BY id DESC LIMIT 1";
				$result = mysql_query ($sql);
				while ($row = mysql_fetch_array($result))
					{
						$prev = $row["url"];
					}

				// forward to previous site
				echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$prev\">";
			}

		mysql_close();
	}

if ($action=="next")
	{
		$sql = "SELECT * FROM $db_table WHERE id>$id AND QUEUE ='1' ORDER BY id LIMIT 1";
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
			{
				$next = $row["url"];
			}

		if($next)
			{
				// forward to next site
				echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$next\">";
			}

		else
			{
				$sql = "SELECT * FROM $db_table WHERE QUEUE ='1' ORDER BY id LIMIT 1";
				$result = mysql_query ($sql);
				while ($row = mysql_fetch_array($result))
					{
						$next = $row["url"];
					}

				// forward to previous site
				echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$next\">";
			}

		mysql_close();
	}

if ($action=="rand")
	{
		$sql = "SELECT * FROM $db_table WHERE QUEUE ='1' ORDER BY rand(" . time() . " * " . time() . ") LIMIT 1";
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
			{
				$rand = $row["url"];

				// forward to random site
				echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$rand\">";
			}

		mysql_close();
	}

if ($action=="list")
	{
		// forward to members list
		echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$members_php#list\">";
	}

if ($action=="join")
	{
		// forward to members list
		echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$join_php\">";
	}

if ($action=="queue")
	{
		// forward to members list
		echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$queue_php\">";
	}

if ($action=="home")
	{
		// forward to members list
		echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$index_php\">";
	}
?>
