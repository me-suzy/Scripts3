<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Contact -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
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
		

	



<h1>Contact</h1>

<p>
<?php
if( LEGALNAME != NULL ): echo "Name: " . LEGALNAME;endif;
if( DOINGBUSINESSAS != NULL ):echo "<br>Doing Business As (DBA): " . DOINGBUSINESSAS;endif;
if( EXECNAME != NULL ):echo "<br>Executive: " . EXECNAME;endif;
if( EXECTITLE != NULL ):echo "<br>Title: " . EXECTITLE;endif;
if( ADDRESS != NULL ):echo "<br>Address: " . ADDRESS;endif;
if( PHONE != NULL ):echo "<br>Phone: " . PHONE;endif;
if( FAX != NULL ):echo "<br>Fax: " . FAX;endif;
?>
<BR><A HREF="email.php?to=<?php echo TO;?>&domain=<?php echo DOMAIN;?>">Send Email</a>
</p>

		
		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	

</body>
</html>