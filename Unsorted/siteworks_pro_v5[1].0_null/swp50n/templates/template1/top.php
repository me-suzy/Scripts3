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

<body <?php if($ezinePopup == true) { echo "onBeforeUnload=\"DoPopupNL()\""; } ?> topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">

<table border="0" cellspacing="0" width="100%" cellpadding="0" height="79">
  <tr>
    <td width="100%" height="26">
    <p align="right" style="margin-right: 20">
    <span class="Text1">
            <?php echo date("l, dS F Y"); ?>
    </span>
	</td>
  </tr>
  <tr>
    <td width="100%" height="23" bgcolor="#111111">
		&nbsp;&nbsp;
		<a href="index.php"><span class="Link3">home</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="about.php"><span class="Link3">about</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="authors.php"><span class="Link3">authors</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="news.php"><span class="Link3">news</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="books.php"><span class="Link3">books</span></a>
		<?php 
		
			if($showXML)
				echo '&nbsp;&nbsp;&nbsp;
				<a href="rss.php"><span class="Link3">xml feed</span></a>';
		?>
		&nbsp;&nbsp;&nbsp;
		<a href="sitemap.php"><span class="Link3">sitemap</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="privacy.php"><span class="Link3">privacy</span></a>
		&nbsp;&nbsp;&nbsp;
		<a href="contact.php"><span class="Link3">contact us</span></a>
	</td>
  </tr>
  <tr>
    <td width="100%" height="10">
    <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" height="143" cellpadding="0">
      <tr>
        <td width="25%" height="96" align="center" bgcolor="#376631">
        <a href="index.php"><img border="0" src="images/logo.gif"></a></td>
        <td width="75%" height="96" bgcolor="#298363">
        <img border="0" src="images/topr.gif" width="433" height="96"></td>
      </tr>
      <tr>
        <td width="25%" height="25" bgcolor="#828282">
			<p style="margin-left: 10">
			<span class="Link3"><?php CheckShowUsersOnline(false); ?></span>
        </td>
        <td width="75%" height="25" bgcolor="#828282">
        <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="300" cellpadding="0" height="100%">
          <tr>
            <td width="20">
            <img border="0" src="images/folder.gif" width="15" height="15"></td>
            <td width="120">
				<a href="javascript:window.external.addFavorite('<?php echo $siteURL; ?>', '<?php echo $siteName; ?>')"><span class="Link3">add to favorites</span></a><br>
            </td>
            <td width="20">
            <img border="0" src="images/folder.gif" width="15" height="15"></td>
            <td width="140">
				<a style="behavior:url(#default#homepage)" onClick="setHomePage('<?php echo $siteURL; ?>')" href="#"><span class="Link3">make home page</span></a><br>
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td width="25%" height="19" bgcolor="#FFFFFF" valign="top">
        <table border="0" cellspacing="0" width="100%" cellpadding="0">
          <tr>
            <td width="100%" class="SideBody" valign="top">
				<p style="margin-left: 10; margin-right: 10">
					<img src="blank.gif" width="1" height="10"><br>
					<?php ShowTopics(); ?>
				</p>
			</td>
          </tr>
          <?php ShowRecentForumPosts(10); ?>
          <tr>
            <td width="100%" height="25" class="SideHeader">
				<p style="margin-left: 10">
					<span class="SideHeading">Our Newsletter</span>
				</p>
			</td>
          </tr>
			<form name="frmEmail" action="newsletter.php" method="post">
			<tr>
			  <td width="100%" valign="middle" class="SideBody" height="40">
				<table width="95%" border="0" align="center">
					<tr>
						<td>
							<span class="Text1">
			  					<img src="blank.gif" width="1" height="10"><br>
			  					<input size="12" type="text" name="strEmail" value="YOUR EMAIL" onClick="if(this.value == 'YOUR EMAIL') { this.value = ''; }"> <input type="submit" value="Go!"><br>
			  					<input checked type="radio" name="submit" value="1"> Subscribe<br>
			  					<input type="radio" name="submit" value="0"> Unsubscribe
			  					<br><img src="blank.gif" width="1" height="5">
			  				</span>
			  			</td>
			  		</tr>
			  	</table>
			  </td>
			</tr>
			</form>
          <?php ShowVotingPoll(); ?>
          <?php ShowFeaturedBook(); ?>
			<tr>
			    <td width="100%" class="SideHeader">
		            <span class="SideHeading">&nbsp;&nbsp;<?php echo $showHandy; ?></span>
			    </td>
			</tr>
			<tr>
			    <td width="100%" valign="top" class="SideBody">
			        <p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
		                <?php ShowHandyTip(); ?>
			        </p>
			    </td>
			</tr>
			<tr>
			    <td width="100%"class="SideHeader">
		            <span class="SideHeading">&nbsp;&nbsp;<?php echo $showMy2c; ?></span>
			    </td>
			</tr>
			<tr>
			<td width="100%"valign="top" class="SideBody">
				<p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
					<?php Show2Cents(); ?>
				</p>
			</td>
			</tr>

          <tr>
            <td width="100%">&nbsp;</td>
          </tr>
          <tr>
            <td width="100%">&nbsp;</td>
          </tr>
          <tr>
            <td width="100%">&nbsp;</td>
          </tr>
        </table>
        </td>
        <td width="75%" height="19" bgcolor="#FFFFFF" valign="top">
        <table border="0" cellspacing="0" width="100%" cellpadding="0">
          <tr>
            <td width="60%" valign="top">
