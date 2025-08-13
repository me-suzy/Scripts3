<?php

    // Set error reporting only above warnings and start output caching
    error_reporting(E_WARNING);
    ob_start();

    require_once("config.php");
    require_once("includes/php/funcs.php");
    
    // Do we need to get the security information?
    if($isSetup == "yes" && !is_numeric(strpos($_SERVER["SCRIPT_NAME"], "setup.php")))
    {
	    require_once("includes/php/security.php");
		$accessLevel = IsLoggedIn();
	}
	else
	{
		$accessLevel = 0;
	}

    global $publisherAccess;
?>

        <html>

        <head>
        <title><?php echo $appName . " v" . $appVersion; ?> :: The driving force behind <?php echo $siteName; ?>!</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <script language="JavaScript">
        <!--

                var colorWin;
                var imageWin;

                window.onerror = killError;

                function killError()
                {
                        return true;
                }

                function closeWindows()
                {
                        if(colorWin)
                                colorWin.close();

                        if(imageWin)
                                imageWin.close();
                }

        // -->
        </script>
        </head>

        <body onBeforeUnload="closeWindows()" background="bg.gif">
        <a name="top"></a>

        <div align="center">
          <center>
          <table border="0" width="750" cellspacing="0" cellpadding="0">
            <tr>
              <td width="76"><img border="0" src="logo.jpg" width="292" height="115"></td>
            </center>
            <td width="674" background="topbg.gif" valign="top" align="right">
            </td>
            <td width="16" background="topbg.gif">
              <p align="right"><img border="0" src="topr.gif" width="10" height="115"></td>
          </tr>
          <center>
          <tr>
            <td width="768" colspan="3">
              <div align="right">
                <table border="0" width="97%" cellspacing="0" cellpadding="0" height="241">
                  <tr>
                    <td width="21%" bgcolor="#CECFCE" valign="top" rowspan="2" height="241">
                      <table border="0" width="100%" cellspacing="0" cellpadding="0" height="87">
                        <tr>
                          <td width="100%" bgcolor="#C7E5C7" valign="top">
                                <p style="margin: 10"><font size="1" face="Verdana">

                                <?php if(!$accessLevel) { ?>
                                                                Once you've logged in using the form on the right, you will
                                                                be able to use the menu that will appear here.<br>
                                                        <?php } else { ?>
                                                                Use the menu below to modify the content of <?php echo $siteName; ?>.<br><br>
                                                                <input onClick="document.location.href='index.php?strMethod=logout'" type="button" value="Logout Now" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        <?php } ?>
                                </font>
                            </td>
                        </tr>
                                        <tr>
                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                        <p style="margin-left:10">
                                                                <span class="MenuHeading">Home</span>
                                                        </p>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                · <a href="index.php">Home Page</a><br>
                                                        </p>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                · <a target="_blank" href="<?php echo $siteURL; ?>">Visit Your Site</a>
                                                        </p>
                                                </td>
                                        </tr>
                                    <?php if($accessLevel == 3) { ?>
                                        <tr>
                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                · <a href="setup.php">Update Configuration</a>
                                                        </p>
                                                </td>
                                        </tr>
                                    <?php } ?>

                        <?php

                        // We're working with a multi-access user system, so what can various
                        // users access? The menu options for publishers can be set in the
                        // config.php file. Administrators (obviously) have access to all menus

                        if($accessLevel)
                        {
                                                // Can they view and modify about/contact/privacy details?
                                                if($accessLevel == 3 || in_array("sitedetails", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Site Details</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("about", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="sitedetails.php?strMethod=showabout">About Us</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("contact", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="sitedetails.php?strMethod=showcontact">Contact Us</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("privacy", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="sitedetails.php?strMethod=showprivacy">Privacy</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify users?
                                                if($accessLevel == 3 || in_array("users", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Users</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_users", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="users.php">View Users</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_users", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="users.php?strMethod=addnew">Add User</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify articles?
                                                if($accessLevel == 3 || in_array("articles", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Articles</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_articles", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="articles.php">View Articles</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_articles", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="articles.php?strMethod=addnew">Add Article</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify article topics?
                                                if($accessLevel == 3 || in_array("topics", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Article Topics</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_topics", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="topics.php">View Topics</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_topics", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="topics.php?strMethod=addnew">Add Topic</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify news posts?
                                                if($accessLevel == 3 || in_array("news", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">News</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_news", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="news.php">View News</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_news", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="news.php?strMethod=addnew">Add News</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify article topics?
                                                if($accessLevel == 3 || in_array("personalContent", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Personal Content</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("edit_2cents", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="personalcontent.php?strMethod=show2cents">Update "My 2 Cents"</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("edit_tips", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="personalcontent.php?strMethod=showtip">Update Tip</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify article topics?
                                                if($accessLevel == 3 || in_array("books", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Books</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_books", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="books.php">View Books</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_books", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="books.php?strMethod=addnew">Add Book</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify affiliates?
                                                if($accessLevel == 3 || in_array("affiliates", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Affiliates</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_affiliates", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="affiliates.php">View Affiliates</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_affiliates", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="affiliates.php?strMethod=addnew">Add Affiliate</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }

                                                // Can they view and modify affiliates?
                                                if($accessLevel == 3 || in_array("newsletter", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Newsletter</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_newsletter", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="newsletter.php?strMethod=view">View Subscribers</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("export_newsletter", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="newsletter.php?strMethod=export">Export Addresses</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                // Can they view and modify affiliates?
                                                if($accessLevel == 3 || in_array("polls", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#8CD78C" valign="bottom" height="21">
                                                                        <p style="margin-left:10">
                                                                                <span class="MenuHeading">Polls</span>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("view_polls", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="polls.php">View Polls</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }
                                                if($accessLevel == 3 || in_array("add_polls", $publisherAccess))
                                                {
                                                ?>
                                                        <tr>
                                                                <td width="100%" bgcolor="#C6E7C6" valign="middle" height="21">
                                                                        <p class="BodyText" style="margin-left:10; margin-right:10">
                                                                                · <a href="polls.php?strMethod=addnew">Add Poll</a>
                                                                        </p>
                                                                </td>
                                                        </tr>
                                                <?php
                                                }



                                        }
                                                ?>
                      </table>
                        <p align="center">&nbsp;
                <p>&nbsp;</p>
                <p>&nbsp;</p>
        </td>
        </center>
        <td width="79%" bgcolor="#FFFFFF" valign="top" height="700">