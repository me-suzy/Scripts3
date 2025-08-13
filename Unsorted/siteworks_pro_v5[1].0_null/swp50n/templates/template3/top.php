<?php
    
	// First template
    ob_start();
    require_once(realpath("includes/php/functions.php"));
?>

<html>
<head>
<title><?php echo $siteName . " :: " . $siteURL; ?></title>
<meta name="keywords" value="<?php echo $siteKeywords; ?>">
<meta name="description" value="<?php echo $siteDescription; ?>">
<link rel="stylesheet" type="text/css" href="templates/<?php echo $template; ?>/styles/style.css">
<script language="JavaScript" src="includes/jscript/misc.js"></script>
<script language="JavaScript" src="includes/jscript/newsletter.js"></script>
</head>

<body <?php if($ezinePopup == true) { echo "onBeforeUnload=\"DoPopupNL()\""; } ?>>

<div align="center">
  <center>
  <table border="0" cellspacing="0" width="770" height="130">
	<form name="frmSearch" action="search.php" method="get">
	<input type="hidden" name="srchArticles" value="1">
	<input type="hidden" name="srchNews" value="1">
    <tr>
      <td width="133" height="81" rowspan="2" class="SideBody1">&nbsp;</td>
      <td width="283" height="81" background="images/rtbg1.gif" rowspan="2" align="center">
      <a href="index.php"><img border="0" src="images/logo.gif" width="180" height="35"></a></td>
      <td width="354" height="44">&nbsp;
      </td>
    </tr>
    <tr>
      <td width="354" height="37" background="images/rtbg3.gif">
      <p align="center">
      	<input type="text" name="query" value="SEARCH OUR SITE" size="30" style="font-size: 8pt; font-family: Verdana" onClick="if(this.value == 'SEARCH OUR SITE') { this.value = ''; }"> 
        <input type="submit" value="Go!" style="font-family: Verdana; font-size: 8pt">
     </td>
    </tr>
    <tr>
      <td width="133" height="24" class="SideBody1" background="images/rgtbg.gif">&nbsp;</td>
      <td width="633" height="24" class="SideBody1" colspan="2" background="images/rgtbg.gif">
      <div align="center">
        <center>
        <table border="0" cellspacing="0" width="97%" cellpadding="0" height="100%" background="images/rtgbg.gif">
          <tr>
            <td width="50%">
				<span class="Text2"><?php CheckShowUsersOnline(false); ?></span>
			</td>
            <td width="50%">
            <p align="right">
				<span class="Text4"><?php echo date("l, dS F Y"); ?></span></td>
          </tr>
        </table>
        </center>
      </div>
      </td>
    </tr>
</form>
    <tr>
      <td width="133" height="19" valign="top">
      <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
          <td width="100%" class="SideBody1" background="images/rlbg3.gif">
			<p style="margin-left: 10; margin-right: 10">
				<img src="blank.gif" width="1" height="10"><br>
				<a href="index.php"><span class="Link3">Home</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="about.php"><span class="Link3">About</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="authors.php"><span class="Link3">Authors</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="news.php"><span class="Link3">News</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="books.php"><span class="Link3">Books</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<?php 

					if($showXML)
						echo '<a href="rss.php"><span class="Link3">XML Feed</span></a><br><img src="blank.gif" width="1" height="3"><br>';
				
				?>
				<a href="sitemap.php"><span class="Link3">Site Map</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="privacy.php"><span class="Link3">Privacy</span></a><br><img src="blank.gif" width="1" height="3"><br>
				<a href="contact.php"><span class="Link3">Contact</span></a><br><img src="blank.gif" width="1" height="3">
				<br>
			</p>
		</td>
        </tr>
        <?php ShowRecentForumPosts(10); ?>
        <tr>
          <td width="100%" height="10" class="SideBody2">
			<div align="center">
              <center>
              <img src="blank.gif" width="1" height="10"><br>
              <table border="0" cellspacing="0" width="90%" cellpadding="0" height="59">
                <tr>
                  <td width="16%" height="20" valign="middle">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="20" valign="middle">
                  <span class="SideHeader"><?php echo $showHandy; ?></span></td>
                </tr>
                <tr>
                  <td width="100%" height="19" colspan="2">
					<span class="Text1"><?php ShowHandyTip(); ?></span>
				  </td>
                </tr>
                <tr>
                  <td width="16%" height="20" valign="middle">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="20" valign="middle">
					<span class="SideHeader"><?php echo $showMy2c; ?></span>
                  </td>
                </tr>
                <tr>
                  <td width="100%" height="19" colspan="2">
					<span class="Text1"><?php Show2Cents(); ?>
				  </td>
				</tr>
                <tr>
                  <td width="16%" height="20" valign="middle">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="20" valign="middle">
					<span class="SideHeader">AFFILIATES</span>
                  </td>
                </tr>
                <tr>
                  <td width="100%" height="19" colspan="2">
					<?php ShowAffiliates(false); ?>
                  </td>
                </tr>
               </table>
              <br><img src="blank.gif" width="1" height="10"><br>
               </center>
               </div>
          </td>
        </tr>
      </table>
      </td>
      <td width="633" height="19" colspan="2" valign="top">
      <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
          <td width="69%" valign="top">
