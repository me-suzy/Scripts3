<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Newsletter -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
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



		<!-- start MAIN COLUMN -->
		<td valign="top">
		<!-- PUT CONTENT RIGHT HERE !!! -->
		

<script language="JavaScript1.2" type="text/javascript">
<!--
var msg = "Newsletter";
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
//-->
</script>		

			<?php if(OFFERNEWSLETTER == "yes"):?>

				<H3>About the Newsletter</H3>

				<P>We offer a refreshing newsletter delivered right to your inbox 
				on a FREE and infrequent basis. The newsletter is designed to:

				<ul>
					<li>enable you to get the most out of your digital downloads
					<li>provide links and download URL's for new or updated products or services
					<li>supply you with useful tips, tricks and techniques
					<li>keep you informed about our activities
					<li>gain insight into events affecting our industry
				</ul>

				</P>

				<H3>Subscribe</H3>

				<p><a href="newsSubscribe.php?goal=Subscribe">Click here to <b>subscribe</b>.</a></p>

				<H3>Unsubscribe</H3>

				<p><a href="newsUnsubscribe.php?goal=Unsubscribe">Click here to <b>unsubscribe</b>.</a></p>


				
				<span style="font-size:52;color:orange;font-weight:bold;"><blink>BONUS !!!</blink></span>
				<p>
				Automatically receive a <b>FREE article</b> about <i>'Don't Let Your Website Embarrass You</i>' 
				with your new subscription. This article is written by the Duke of URL.
				</p>
				<p><a href="newsSubscribe.php?goal=Subscribe">Click here to <b>subscribe</b> and automatically receive your bonus article.</a> 
				You may <a href="newsUnsubscribe.php?goal=Unsubscribe">unsubscribe</a> at any time.</p>

			<?php else:?>
			
				<P>The newsletter is not offered at this time.</P>

			<?php endif;?>

		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	

</body>
</html>