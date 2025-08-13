<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php include_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Register -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
	</HEAD> 

<body onload="window.document.enterEmailForm.email.focus()">

<!-- START of body -->


<?php include("header.php");?>

 		
<?php include("youSelected.php");?>
		
<blockquote>		
		
<h1>Product Download Registration</h1>


<h2>Why Register?</h2>
      <ul>
         <li>to set the privacy level you are comfortable with</li>
         <li>subscribe to our newsletter</li>
         <li>pre-fill basic customer details for future purchases or downloads</li>
         <li>complete this page to avoid seeing this screen again</li>
      </ul>
  
      <p class="systemMessage">
         <?php echo COMPANY;?> does NOT rent, sell, or exchange any of the information that you give, 
         or collect information "behind the scenes" to do so. Your privacy is of the utmost importance
         to us. 
      </p>


<?php include("enterEmailForm.php");?>

</blockquote>


<!-- END of Main Content -->

<p><br></p>
		


<?php include("footer.php");?>

	
