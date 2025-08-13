<?php

	// Second template
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
<table border="0" cellpadding="0" cellspacing="0" width="760" height="80">
  <tr>
    <td width="115" height="50" rowspan="2">
    <p align="center">&nbsp;</p></td>
    <td width="455" height="50" valign="bottom">
                <p style="MARGIN-LEFT: 20px">
                        <a href="index.php"><img border="0" src="<?php echo $siteLogo; ?>"></a>&nbsp;
                </p>
    </td>
    <td width="190" height="50" valign="bottom">
                <p align="right" style="MARGIN-BOTTOM: 5px">
                        <span class="Text1">
                                <?php echo date("l, dS F Y"); ?>
                        </span>
                </p>
        </td>
  </tr>
  <tr>
    <td width="645" height="11" colspan="2">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td width="7%" class="TopHeader">
                <IMG border="0" src="images/rt2.gif"></td>
        <td width="93%" valign="center" class="TopHeader">
                        <a href="index.php"><span class="Link3">home</span></a>
                        <span class="Text2"> | </span>
                        <a href="about.php"><span class="Link3">about</span></a>
                        <span class="Text2"> | </span>
                        <a href="authors.php"><span class="Link3">authors</span></a>
                        <span class="Text2"> | </span>
                        <a href="news.php"><span class="Link3">news</span></a>
                        <span class="Text2"> | </span>
                        <a href="books.php"><span class="Link3">books</span></a>
                        <span class="Text2"> | </span>
						<?php 
		
							if($showXML)
								echo '<a href="rss.php"><span class="Link3">xml feed</span></a><span class="Text2"> | </span>';
						
						?>
                        <a href="sitemap.php"><span class="Link3">sitemap</span></a>
                        <span class="Text2"> | </span>
                        <a href="privacy.php"><span class="Link3">privacy</span></a>
                        <span class="Text2"> | </span>
                        <a href="contact.php"><span class="Link3">contact us</span></a>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" width="760">
        <tr>
                <td width="150" valign="top">
                        <!-- Start Left Side Bar -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                        <td width="4%" class="SideHeader" height="21">
                                                <img border="0" src="images/rt.gif">
                                        </td>
                                        <td width="96%" class="SideHeader">
                                                <span class="SideHeading">&nbsp;&nbsp;Article Topics</span>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" valign="top" class="SideBody">
                                                <p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
                                                        <?php ShowTopics(); ?>
                                                </p>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" class="SideHeader">
                                                <span class="SideHeading">&nbsp;&nbsp;<?php echo $showHandy; ?></span>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" valign="top" class="SideBody">
                                                <p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
                                                        <?php ShowHandyTip(); ?>
                                                </p>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" class="SideHeader">
                                                <span class="SideHeading">&nbsp;&nbsp;<?php echo $showMy2c; ?></span>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" valign="top" class="SideBody">
                                                <p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
                                                        <?php Show2Cents(); ?>
                                                </p>
                                        </td>
                                </tr>
                                <?php ShowVotingPoll(); ?>
                                <tr>
                                        <td width="100%" colspan="2" class="SideHeader">
                                                <span class="SideHeading">&nbsp;&nbsp;Our Affiliates</span>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" valign="top" class="SideBody">
                                                <p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
                                                        <?php ShowAffiliates(); ?>
                                                </p>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="100%" colspan="2" valign="bottom" class="SideBody"><img src="images/rt1.gif"></td>
                                </tr>
                        </table>
                        <!-- End Left Side Bar -->
                </td>
                <td width="450" valign="top" class="BodyHeader">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form name="frmSearch" action="search.php" method="get">
						<input type="hidden" name="srchArticles" value="1">
						<input type="hidden" name="srchNews" value="1">
						<tr>
						    <td width="100%" height="25" class="BodyHeader1">
								&nbsp;
								<span class="SideHeading">
									Search <?php echo $siteName; ?>:&nbsp; <input size="25" name="query" type="text" style="height:18" maxlength="25"> <input type="submit" value="Go!" style="height:22">
								</span>
							</td>
						</tr>
						</form>
					</table>