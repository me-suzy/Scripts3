<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
    // We will include the top template as well as some javascript functions
    
    ob_start();
                
    include(realpath("templates/dev_top.php"));
    include(realpath("includes/jscript/adminuser.js"));

    if(is_numeric(strpos($_SERVER["HTTP_REFERER"], "setup.php")))
		$preFill = true;
	else
		$preFill = false;

    $struName = @$_POST["struName"];
    $strPass = @$_POST["strPass"];
    $strMethod = @$_POST["strMethod"];

    if($strMethod == "")
        $strMethod = @$_GET["strMethod"];

    if(!IsLoggedIn())
            {
                    switch($strMethod)
                            {
                                    case "check_login":
                                            ProcessLogin();
                                            break;
                                    default:
                                            GetLogin();
                                            break;
                            }
            }
    else
            {
                    switch($strMethod)
                            {
                                    case "logout":
                                            ProcessLogout();
                                    default:
                                            ShowLogOut();
                            }
            }

    /* Function declerations for index.php */

    function GetLogin()
            {
                    /*
                            Function Name: GetLogin()
                            Paramaters: N/A
                            Desc: Gets both username and password from the user
                                  and parses the results back in
                    */

                    global $appName;
                    global $appVersion;
                    global $siteName;
                    global $preFill;
            ?>

                    <form onSubmit="return CheckLogin()" name="frmLogin" action="index.php" method="post">
                    <input type="hidden" name="strMethod" value="check_login">
                    <div align="center">
                      <center>
                      <table border="0" width="95%" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="100%" colspan="2">
                                    <span class="BodyHeading">
                                            Welcome to <?php echo $appName . " v" . $appVersion; ?>
                                    </span>
                            <p class="BodyText"><img border="0" src="people.gif" align="left" width="156" height="123">Hi,
                            welcome to <?php echo $appName . " v" . $appVersion; ?>. No doubt, you've arrived at this
                            site because your are a registered content provider for
                            <?php echo $siteName; ?>. To use <?php echo $appName; ?>, you must first login
                            using the form below. Once you have logged in, you can use
                            the menu that will appear on the left to <?php echo $siteName; ?>.<br>
                            &nbsp;</p>
                          </td>
                        </tr>
                        <tr>
                          <td width="100%" background="doth.gif" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="26%" align="right"><font size="1" color="#000000" face="Verdana"><b>Username:</b></font></td>
                          <td width="74%">
                            <p style="margin-left: 10"><input name="struName" type="text" maxlength="20" value="<?php if($preFill == true) { echo "administrator"; } ?>"></p>
                          </td>
                        </tr>
                        <tr>
                          <td width="26%" align="right"><font size="1" face="Verdana"><b>Password:</b></font></td>
                          <td width="74%">
                            <p style="margin-left: 10"><input name="strPass" type="password" maxlength="20" value="<?php if($preFill == true) { echo "password"; } ?>"></td>
                        </tr>
                        <tr>
                          <td width="26%"></td>
                          <td width="74%">
                            <p style="margin-left: 10; margin-top: 5"><input type="submit" value="Login"></p>
                          </td>
                        </tr>
                        <tr>
                          <td width="100%" colspan="2">&nbsp;</td>
                        </tr>
                      </table>
                      </center>
                    </div>
                    </form>
    <?php
            }

    function ProcessLogin()
            {
                    /*
                            Function Name: ProcessLogin($strUser, $strPass)
                            Paramaters: N/A
                            Desc: Checks for a valid user in the tbl_AdminLogins
                                  table and sets the appropriate cookies, etc.
                    */

                            global $REMOTE_ADDR;
                            global $appName;
                            global $siteName;

                            $strUser = @$_POST["struName"];
                            $strPass = @$_POST["strPass"];

                            ?>
								<p class="BodyText" style="margin-left:15; margin-right:20; margin-bottom:20">
                            <?php

                            if(!isset($strUser) || !isset($strPass))
                                    {
                                            ?>
                                                            <span class="BodyHeading">Login Credentials Required<br><br></span>
                                                            It appears that you have not entered both your username and password.
                                                            Please use the link below to go back and complete the form.<br><br>
                                                            <a href="javascript:history.go(-1)">Go Back</a>
                                            <?php
                                    }

                            /*
                                    We will now have a username and password which we can
                                    check against the tbl_AdminLogins table in our database.
                            */
                                    $dbVars = new dbVars();
                                    @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                    if($svrConn)
                                            {
                                                    $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                    if($dbConn)
                                                            {
                                                                    $strQuery  = "select * from tbl_AdminLogins ";
                                                                    $strQuery .= "where alUserName = '$strUser' ";
                                                                    $strQuery .= "  and alPass = '$strPass' ";

                                                                    $results = mysql_query($strQuery);
                                                                    $result = mysql_fetch_row($results);

                                                                    if($result)
                                                                            {
                                                                                    // Write to the log file
                                                                                    $strLog = "Login OK: {$result[4]} {$result[5]}, {$result[1]}, $REMOTE_ADDR, " . date("d/m/Y h:i:m A");
                                                                                    AppendToLog($strLog);

                                                                                    // Set the login credentials for this user in the tbl_AdminSessions table
                                                                                    $blnSessStored = StoreSessionData($result[1], $result[2], $result[4], $result[5], '2');

                                                                                    if($blnSessStored)
                                                                                            {
                                                                                                    ?>
                                                                                                            <span class="BodyHeading">Hi <?php echo $result[4] ?>, welcome to <?php echo $appName; ?>.<br><br></span>
                                                                                                            <img src="people.gif" align="left">You have successfully logged into your account. You can begin content modification
                                                                                                            for <?php echo $siteName; ?> by clicking on the link below.<br><br>
                                                                                                            <a href="index.php">Start Session</a>

                                                                                                    <?php
                                                                                            }
                                                                                    else
                                                                                            {
                                                                                                    ?>
                                                                                                            <span class="BodyHeading">Couldn't start new session:<br><br></span>
                                                                                                            While trying to temporarily store your login details, an internal error occured.
                                                                                                            Please use the link below to try again.<br><br>
                                                                                                            <a href="javascript:history.go(-1)">Go Back</a>

                                                                                                    <?php
                                                                                            }
                                                                            }
                                                                    else
                                                                            {
                                                                                    $strLog = "Login Failed: $strUser, $strPass, $REMOTE_ADDR, " . date("d/m/Y h:i:m A");
                                                                                    AppendToLog($strLog);

                                                                                    ?>
                                                                                            <span class="BodyHeading">Login Failed<br><br></span>
                                                                                            The login details that you have entered are incorrect. Please use the link below to go
                                                                                            back and check your username and password again.<br><br>
                                                                                            <a href="javascript:history.go(-1)">Go Back</a>

                                                                                    <?php
                                                                            }
                                                            }
                                                    else
                                                            {
                                                                    $strLog = "Database Error: Login, $strUser, $strPass, $REMOTE_ADDR, " . mysql_error() . ", " . date("d/m/Y h:i:m A");
                                                                    AppendToLog($strLog);

                                                                    ?>
                                                                            <span class="BodyHeading">Database Error</span><br><br>
                                                                            An error occured while trying to verify your login credentials
                                                                            within the devAdmin database. Please use the link below to go back
                                                                            and try again.<br><br>
                                                                            <a href="javascript:history.go(-1)">Go Back</a>
                                                                    <?
                                                            }
                                            }
                                    else
                                            {
                                                                    $strLog = "Database Server Error: Login, $strUser, $strPass, $REMOTE_ADDR, " . mysql_error() . ", " . date("d/m/Y h:i:m A");
                                                                    AppendToLog($strLog);

                                                    ?>
                                                            <span class="BodyHeading">Database Error</span><br><br>
                                                            A connection couldn't be established with the devAdmin database.
                                                            Please use the link below to go back and try again.<br><br>
                                                            <a href="javascript:history.go(-1)">Go Back</a>
                                                    <?php
                                            }
            }

            // Flush the output buffer to the client
            ob_end_flush();

function ShowLogOut()
    {
            /*
                    Function Name: ShowLogOut()
                    Paramaters: N/A
                    Desc: Shows a screen with an option to logout.
            */

            global $siteName;

            ?>
                    <p class="BodyText" style="margin-left:15; margin-right:20; margin-bottom:20">
                    <span class="BodyHeading">You are currently logged in<br><br></span>
                    <img src="people.gif" align="left">If you have completed your content modifications for <?php echo $siteName; ?> and would
                    like to logout, then please click on the link below. If not, you can continue browsing
                    using the menu on the left.<br><br>
                    <a href="index.php?strMethod=logout">Logout Now</a>

            <?php
    }

function ProcessLogOut()
    {
            /*
                    Function Name: ProcessLogOut()
                    Paramaters: N/A
                    Desc: Kills the current session and redirects the user back to the login page
            */

            // Delete records matching this session id
            session_start();
            $dbVars = new dbVars();

            @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

            if($svrConn)
                    {
                            $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                            if($dbConn)
                                    {
                                            $strQuery  = "delete from tbl_AdminSessions ";
                                            $strQuery .= "where asSessId = '" . session_id() . "'";
                                            mysql_query($strQuery);
                                    }
                    }

            header("location: index.php");
    }



    ?>

<?php include(realpath("templates/dev_bottom.php")); ?>


