<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Company Page -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
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
		

	



<h1>Company</h1>



<h3><a href="contact.php">Contact</a></h3>

<h3><a href="privacyPolicy.php">Privacy Policy</a></h3>

<h3><a href="shipping.php">Shipping Policy</a></h3>

		

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

<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>

<?php include("footer.php");?>


</body>
</html>