<?php include_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		
		<TITLE>phpcheckoutTM - a Dreamriver Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download</TITLE>

	</HEAD> 

<body>

<!-- START of body -->



<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>

		<!-- start MAIN COLUMN -->
		<td valign="top">
			<!-- PUT CONTENT BELOW HERE !!! -->

			<blockquote>
				<h1>View Download Log</h1>



				<h2>Download Log</h2>

				<?php
				chdir("./temp");
				if ($dir = opendir(".")) {
					echo"<table width=\"100%\">";
					$flipColor = 0;
					while (($file = readdir($dir)) !== false) {
						if($flipColor == 0 ) {
							echo"<tr><td style=\"font-size:x-small;background-color:honeydew;\">";
							$flipColor++;
						}else{
							echo"<tr><td style=\"font-size:x-small;\">";
							$flipColor = 0;
						}
						echo "$file\n";
						echo"</td></tr>";
					}  
					closedir($dir);
					echo"</table>";
				chdir("../");
				}?>


				<h2>Not Used Anymore ...</h2>

				<p>Over time the old files above build up as unnecessary and sizable garbage. 
				Also, users may try to bookmark the download url to come back later and 
				download another copy, or give the download url to a friend. Collecting 
				the garbage physically destroys the download url and helps prevent abuse. 
				</p>
				<p>Viewing the download log and collecting the garbage on a daily basis 
				can help keep you site more secure, and will give you a precise daily 
				snapshot of the download activity on your website. Use 
				the button below to collect the garbage:</p>


				<form name="garbageForm" method="post" action="adminresult.php">
					<input class="submit" type="submit" name="submit" value="Collect the Garbage">
					<input type="hidden" name="goal" value="Collect the Garbage">
				</form>

				<p class="note">
					Hint: use a low activity time to minimize affect on current downloaders.
				</p>


				<h3>Where do these files come from?</h3>

				<p>Downloaded files are copied to the /temp folder and have their name changed to a <b>unique 
				name</b> for each download.</p> 
				

				<p><b>How a unique name is made</b>
				<br><br>
				<code style="background-color:silver;">
					$dotExtension = stristr ( $url, "."); // get the extension
					<br>// rename the file
					<br>$prefix = strtoupper ($shortname);
					<br>$customeridTracker = trim($customerid);
					<br>$tmpName = $prefix .  "[" . $customeridTracker . "]" . md5(uniqid(time())) . $dotExtension;
					<br>rename ( $url, $tmpName);
					<br>$downloadPathFile = "temp/" . $tmpName;
				</code>
				</p>

				
				<p>The filenames are returned in the order in which they are stored 
				by the filesystem.</p>

			</blockquote>

			<!-- PUT CONTENT ABOVE HERE !!! -->
		</td>
	</tr>
</table>

</body>
</html>