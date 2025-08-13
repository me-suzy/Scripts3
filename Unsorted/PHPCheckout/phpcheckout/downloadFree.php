<?php require_once("configure.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<script language="Javascript" src="phpcheckout.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<TITLE>Download Free -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
	</HEAD> 
<body>
<!-- START of body -->

<?php include("header.php");?>

<?php
$email = !isset( $_POST["email"])? NULL: $_POST["email"];
$method = !isset( $_POST["method"])? NULL: $_POST["method"];
echo"<p class=\"youselected\">You selected > $productname > $availability ";if($availability=="Retail"){echo"> \$$baseprice ";}echo"> $email > $method</p>";
?>

<?php //include("youSelected.php");?>

		
<blockquote>
	<h1>FREE Download</h1>


	<p><br></p>		

	<?php include_once("downloadSwitchVia.php");?>


<table style="border-style:dotted;" cellpadding=0 class="favcolor2">
	<tr>
		<td>
			<a href="surveyTake.php">Click here to take a quick survey</a>
		</td>
	</tr>
</table>





</blockquote>