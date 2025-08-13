<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Members -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
</HEAD> 

<body>
<!-- START of body -->


<?php include("header.php");?>

<blockquote>	

<?php if(FPSTATUS == 'Online' ):?>
<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>



		<!-- start MAIN COLUMN -->
		<td valign="top">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		





<h1>Members</h1>



<h3><A HREF="register.php">Register - Create a Brand New Free Membership</A></h3>

<h3><A HREF="login.php">Login - Use your Existing Account</A></h3>

<h3><a href="newsletter.php">Newsletter</a></h3>

	<ul>
		<li>Subscribe
		<li>Unsubscribe
	</ul>

<h3><a href="survey.php">Survey</a></h3>
	<ul>
		<li>Complete the Survey
		<li>View Survey Results
	</ul>		

<h3><A HREF="link2us.php">Link to us - build a link to us</A></h3>


<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>

		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	


<?php include("footer.php");?>

</body>
</html>