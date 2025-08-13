<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Link 2 Us -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
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
		

			



<script type="text/javascript" language="JavaScript1.2">
<!-- // hide from those shoddy netscape browsers
var msg = "Link 2 Us";

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
// -->
</script>




<h2>Link from your website to Ours</h2>


<table>
<tr>
	<td width="200">
		<img src="appimage/holdingHands.gif" width="189" height="189" border=0 alt="Link to us">
	</td>
	<td align="left">
		To link to us follow these three (3) easy steps:
		<ol>
			<li>copy the green code below</li>
			<li>paste the copied code into your web page</li>
			<li>save your web page and put it on your web server</li>
		</ol>
	</td>
</tr>
<tr>
	<td colspan=2>
			<div class="favcolor2" style="font-size:medium;color:green;">
			<?php echo"&lt;!-- START of make a link to " . ORGANIZATION . " --&gt;";?><br>
			&lt;!-- copy this code from the START to the END, paste into your web page and save it on your server --&gt;&lt;br&gt;<br>
			Visit &lt;a href="<?php echo HOMEPAGE;?>"&gt;<?php echo ORGANIZATION;?>&lt;/a&gt; for free digital downloads
			<br>&lt;!-- This script by: http://www.dreamriver.com --&gt;<br>
			<?php echo"&lt;!-- END of make a link to " . ORGANIZATION . " --&gt;";?>
		</div>
	</td>

</tr>
</table>

		

		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>

<p><br></p>



</blockquote>	

</body>
</html>