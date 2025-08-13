<?php require_once("configure.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
	<script language="Javascript" src="phpcheckout.js"></script>
	<script language="Javascript">loadCSSFile();</script>
		
<?php

/* This code is for the 'Hits This Product' HIT COUNTER and is the generic module.
   Increment the total hit counter for the product:	

*/
// pconnect, select and query
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ( mysql_select_db(DBNAME, $link_identifier)) {
		// get the current total and anything else we need
      $productnumber= FEATURERESOURCE; // set the product to retrieve
		$query = "SELECT hits FROM " . TABLEITEMS . " WHERE productnumber='$productnumber'";
   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
		$rows = mysql_num_rows($queryResultHandle);
		switch($rows) {
			case 1:
				$hits = mysql_result($queryResultHandle,0 );
				++$hits;
				$query = "UPDATE " . TABLEITEMS . " SET hits = $hits WHERE productnumber='$productnumber' ";
	   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				break;
			case 0:
				$strMessage = "No rows found.";
				$_POST['hits'] = "No hits";
				break;
			default:
				echo"Out of range.";
		}
	}else{ // select
		echo mysql_error();
	}
}else{ //pconnect
	echo mysql_error();
}
?>
		
		<TITLE>Feature Product -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

	</HEAD> 

<body>

<!-- START of body -->

<?php include("header.php");?>


<h1>Feature Product</h1>


<h3 align="center">Surf in to <i>phpcheckout<sup>TM</sup></i> ... enjoy the good life!</h3>


<table align="center" border=0>
<tr width="100%">
<!-- left column -->
<td width="30%" align="center" class="favcolor2">
	<img src="user/surfer.jpg" width="166" height="195" border=0 vspace=0 hspace=0 alt="Surf in to phpcheckout . . .">
</td>





<td valign="top" align="center" width="40%">
<!-- middle column -->
<div align="left">
<i>Why use an old shopping cart?<br>
With <i>phpcheckout</i> you can have:</i>
<ul>
<li>more ways to deliver digital product</li>
<li>a quicker path to the checkout counter</li>
<li>easy 4 star internet install script</li>
<li>features galore!</li>
<li>FREE DOWNLOAD !!!</li>
</ul>
</div>


<b><i>phpcheckout<sup>TM</sup></i></b>
<br><br>
					<span style="font-size:12px;">
						<a href="showcase.php?productnumber=5">More info | Download</a> 
					</span>


<br><br>
<?php include("hitsCounter.php");?>


</td>



<!-- right column -->
<td align="right" width="30%" class="favcolor">
	<div align="center">
		<img src="user/enjoy.jpg" width="170" height="197" border=0 alt=". . . enjoy the good life!">
	</div>
</td>
</tr>
</table>		
		
<p><br></p>


<?php include("footer.php");?>

	
