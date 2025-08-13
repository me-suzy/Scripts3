<?php include("templates/dev_top.php"); ?>

        <?php

                /*
                        Firstly, we will make sure that the user is a super-administrator
                        (has an IsLoggedIn value of 3). If they arent, we will redirect them
                        to the main page.
                */

                $userStatus = IsLoggedIn();

                if($userStatus == 0)
                        header("Location: $siteURL/admin/index.php");

                function NoAccess()
                {
                        ?>
                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                        <span class="BodyHeading">Unauthorised</span><br><br>
                                        You do not have sufficient login privledges to access this
                                        part of the site. This breach has been logged.<br><br>
                                        <a href="index.php">Return Home</a>
                                </p>

                        <?php

                                // Don't process the rest of the script

                                global $PHP_SELF;

                                $usrCredentials = new usrCredentials();
                                $strPage = $PHP_SELF;
                                $strUser = $usrCredentials->GetData();
                                $strLog = "Unauthorised: {$strUser[0]}, $strPage, $REMOTE_ADDR, " . date("d/m/Y h:i:m A");

                                AppendToLog($strLog);
                                include("templates/dev_bottom.php");
                                exit;
                        }

                $strMethod = @$_GET["strMethod"];

                if($strMethod == "")
                    $strMethod = @$_POST["strMethod"];

                switch($strMethod)
                {
					case "showabout":
					     ShowUpdateAbout();
					     break;
					case "showcontact":
					     ShowUpdateContact();
					     break;
					case "showprivacy":
					     ShowUpdatePrivacy();
					     break;
					case "updateabout":
					        UpdateAbout();
					        break;
					case "updatecontact":
					        UpdateContact();
					        break;
					case "updateprivacy":
					        UpdatePrivacy();
					        break;
					default:
					        ShowUpdateSiteDetails();
					        break;
                }

                function ShowUpdateAbout()
                {
                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "select * from tbl_SiteDetails limit 1";
                                                                $results = mysql_query($strQuery);

                                                                if($row = mysql_fetch_array($results))
                                                                {
                                                                        $strAbout = $row[1];
                                                                }
                                                                else
                                                                        { $strAbout = ""; }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showabout">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showabout">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                ?>
                                        <?php include("includes/jscript/sitedetails.js"); ?>
                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0">
                                                <form onSubmit="return CheckUpdateAbout()" name="frmUpdateAbout" action="sitedetails.php" method="post">
                                                <input type="hidden" name="strMethod" value="updateabout">
                                                <tr>
                                                        <td width="100%" class="BodyText">
                                                                <span class="BodyHeading">Update "About Us"</span><br><br>
                                                                The contents of your "About Us" page is shown in the
                                                                textbox below. To update it, just change the text and click on
                                                                the "Update 'About Us'" button below.<br><br>
                                                                <?php PrintWYSIWYGTable($strAbout, 540, 150); ?>
                                                                <input type="hidden" name="strAbout">
                                                                <br><div align="right"><input type="submit" value="Update 'About Us' »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">&nbsp;&nbsp;<br><br>
                                                        </td>
                                                </tr>
                                                </form>
                                        </table>
                                        </div>
                                <?php
                }

        function ShowUpdateContact()
                {
                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "select * from tbl_SiteDetails limit 1";
                                                                $results = mysql_query($strQuery);

                                                                if($row = mysql_fetch_array($results))
                                                                {
                                                                        $strContact = $row[2];
                                                                }
                                                                else
                                                                        { $strContact = ""; }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                ?>
                                        <?php include("includes/jscript/sitedetails.js"); ?>
                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0">
                                                <form onSubmit="return CheckUpdateContact()" name="frmUpdateContact" action="sitedetails.php" method="post">
                                                <input type="hidden" name="strMethod" value="updatecontact">
                                                <tr>
                                                        <td width="100%" class="BodyText">
                                                                <span class="BodyHeading">Update "Contact Us"</span><br><br>
                                                                The contents of your "Contact Us" page is shown in the
                                                                textbox below. To update it, just change the text and click on
                                                                the "Update 'Contact Us'" button below.<br><br>
                                                                <?php PrintWYSIWYGTable($strContact, 540, 150); ?>
                                                                <input type="hidden" name="strContact">
                                                                <br><div align="right"><input type="submit" value="Update 'Contact Us' »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">&nbsp;&nbsp;<br><br>
                                                        </td>
                                                </tr>
                                                </form>
                                        </table>
                                        </div>
                                <?php
                }

        function ShowUpdatePrivacy()
                {
                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "select * from tbl_SiteDetails limit 1";
                                                                $results = mysql_query($strQuery);

                                                                if($row = mysql_fetch_array($results))
                                                                {
                                                                        $strPrivacy = $row[3];
                                                                }
                                                                else
                                                                        { $strPrivacy = ""; }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showprivacy">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                ?>
                                        <?php include("includes/jscript/sitedetails.js"); ?>
                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0">
                                                <form onSubmit="return CheckUpdatePrivacy()" name="frmUpdatePrivacy" action="sitedetails.php" method="post">
                                                <input type="hidden" name="strMethod" value="updateprivacy">
                                                <tr>
                                                        <td width="100%" class="BodyText">
                                                                <span class="BodyHeading">Update "Privacy"</span><br><br>
                                                                The contents of your "Privacy" page is shown in the
                                                                textbox below. To update it, just change the text and click on
                                                                the "Update 'Privacy'" button below.<br><br>
                                                                <?php PrintWYSIWYGTable($strPrivacy, 540, 150); ?>
                                                                <input type="hidden" name="strPrivacy">
                                                                <br><div align="right"><input type="submit" value="Update 'Privacy' »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">&nbsp;&nbsp;<br><br>
                                                        </td>
                                                </tr>
                                                </form>
                                        </table>
                                        </div>
                                <?php
                }

                    function UpdateContact()
                        {
                                /*
                                        This function will update the "Contact" row of the tbl_SiteDetails database
                                */

                                $strContact = @$_POST["strContact"];

                                if(strlen($strContact) > 50000)
                                        {
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">Invalid "Contact Us"</span><br><br>
                                                                You have entered text that is longer than 50,000
                                                                characters. Please use the button below to remove <?php echo strlen($strCents)-50000 ?>
                                                                characters from it.<br><br>
                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                        </p>

                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "update tbl_SiteDetails set sdContact = '$strContact'";

                                                                mysql_query($strQuery);

                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">"Contact Us" Updated</span><br><br>
                                                                                The current "Contact Us" page has been successfully updated.
                                                                                Please use the link below to continue.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                                        </p>
                                                                <?php
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showcontact">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }
                        }

                    function UpdateAbout()
                        {
                                /*
                                        This function will update the "My 2 Cents" row of the tbl_Personal database
                                */

                                $strAbout = @$_POST["strAbout"];

                                if(strlen($strAbout) > 50000)
                                        {
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">Invalid "About Us"</span><br><br>
                                                                You have entered text that is longer than 50,000
                                                                characters. Please use the button below to remove <?php echo strlen($strCents)-50000 ?>
                                                                characters from it.<br><br>
                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                        </p>

                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "update tbl_SiteDetails set sdAbout = '$strAbout'";

                                                                mysql_query($strQuery);

                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">"About Us" Updated</span><br><br>
                                                                                The current "About Us" page has been successfully updated.
                                                                                Please use the link below to continue.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showabout">Continue</a>
                                                                        </p>
                                                                <?php
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showabout">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showabout">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }
                        }

                    function UpdatePrivacy()
                        {
                                /*
                                        This function will update the "Contact" row of the tbl_SiteDetails database
                                */

                                $strPrivacy = @$_POST["strPrivacy"];

                                if(strlen($strPrivacy) > 50000)
                                        {
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">Invalid "Privacy"</span><br><br>
                                                                You have entered text that is longer than 50,000
                                                                characters. Please use the button below to remove <?php echo strlen($strCents)-50000 ?>
                                                                characters from it.<br><br>
                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                        </p>

                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);
                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK

                                                                // Get the latest 2 cents post
                                                                $strQuery = "update tbl_SiteDetails set sdPrivacy = '$strPrivacy'";

                                                                mysql_query($strQuery);

                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">"Privacy" Updated</span><br><br>
                                                                                The current "Privacy" page has been successfully updated.
                                                                                Please use the link below to continue.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showprivacy">Continue</a>
                                                                        </p>
                                                                <?php
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                An error occured while trying to connect to to the
                                                                                database. Please use the link below to go try again.<br><br>
                                                                                <a href="sitedetails.php?strMethod=showprivacy">Continue</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">An Error Occured</span><br><br>
                                                                An error occured while trying to connect to to the
                                                                database server. Please use the link below to go try again.<br><br>
                                                                <a href="sitedetails.php?strMethod=showprivacy">Continue</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }
                        }


        ?>








                <?php include("templates/dev_bottom.php"); ?>
