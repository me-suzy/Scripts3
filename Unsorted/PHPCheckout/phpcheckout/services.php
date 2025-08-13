<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Services -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		<meta name="Generator" content="Custom Handmade">
		<meta name="Author" content="DreamRiver.com, Richard Creech, http://www.dreamriver.com">
		<meta name="Keywords" content="PHPCHECKOUT, checkout, php, check, out, php checkout, contact, shop, shopping, shopper, sales, catalog, online, retail, retailer, download, downloading, make, money, sell, product, products">
		<meta name="Description" content="Contact DreamRiver.com about PHPCHECKOUT by visiting this page. Select one of many communication methods including phone, stealth email or snail mail options. Profit from your own digital downloads with PHPCHECKOUT.">
	</HEAD> 
<body onload="colorize()";>
<!-- START of body -->

<?php include("header.php");?>

<blockquote>

<?php if(FPSTATUS == 'Online' ):?>
<table width="100%">
	<!-- start of primary content FOR PAGE -->
	<tr>
		<td valign="top">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		
					

<script language="JavaScript1.2">
var msg = "Services";

var colorTimer = null;

for (var i=0; i<msg.length; i++){
	document.write("<span id = \"vjeko" + i + "\" style = \"color:blue; font-size:xx-large;\">" + msg.charAt(i) + "</span>");
}

function toHex(n){
	var hexChars = "0123456789ABCDEF";
	if (n == 0) return n;
	var j, k;
	var temp = "";
	while (n != 0){
		j = n % 16;
		n = (n - j)/16;
		temp = hexChars.charAt(j) + temp;
	}
	return temp;
}

function colorize(){
if (!document.all) return;
for (i=0; i<msg.length; i++){
	k = Math.round(Math.random() * 16777215);
	k = toHex(k);
	while (k.length < 6){
		k = k + "0";
	}
document.all["vjeko" + i].style.color = '#' + k;
}
colorTimer = window.setTimeout("colorize()", 200);
}
</script>


			<h2>A Full Range of Value Added Services</h2>


		<?php if (ORGANIZATION == "DreamRiver"):?>
			<h3>Custom Apps</h3>
				
			<p>
				<i>DreamRiver</i> can build you a custom internet application. Just ask.
			</p>

			<h3><a href="showcase.php?productnumber=12">Gateway Setup</a></h3>

			<p>
					We can connect your <i>DreamRiver</i> software with your favorite payment gateway 
					for as low as $99.00. Ask us. 
			</p>

			<h3><a href="showcase.php?productnumber=12">Soft Installs</a></h3>
			<p>
					We do software installations for our own <i>DreamRiver</i> software. But first, try it yourself! 
					DreamRiver software is well known for its user friendly installation software and easy to follow 
					directions.
			</p>

			<h3>Refer</h3>
				
			<p>
					If we can not directly assist you with your project, we can refer you to qualified 
					folks who can. Our extended network of programmers, web organizations and local 
					internet educational institutions can pay dividends for YOU. Contact us to get started 
					finding the right person for your requirements. This is a FREE service.
			</p>

			<h3><a href="contact.php">Get a Quote</a></h3>

			<p>
				Send us a quick email to get a quote on your project.
			</p>
		<?php endif;?>
				
				
				
				<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');?>

<?php endif; // on or offline ?>

</blockquote>

<p><br></p>


<?php include("footer.php");?>