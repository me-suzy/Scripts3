<? 
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

$thisversion = "6.1";
error_reporting(1);

// --------------------------------
// Register the necessary variables
   if (is_array($HTTP_GET_VARS)) {
     while(list($key,$value) = each($HTTP_GET_VARS)) {
        ${$key} = stripslashes($value);
     }
   }
   if (is_array($HTTP_POST_VARS)) {
     while(list($key,$value) = each($HTTP_POST_VARS)) {
        ${$key} = stripslashes($value);
     }
   }

// Go to the first step
  if ( (!$step) || ($option == "Check again") ){
    $step = 1;
    step1();
  }
  if ($option == "Verify url/path settings") {
    $step = 4;
  }
  if ($step == 2) {
	 if (!$tbprefix) { $tbprefix = "w3t_"; }
    step2($step,$dbserver,$dbname,$dbuser,$dbpass,$servererror,$nameerror,$usererror,$passerror,$tbprefix,$cookieprefix);
  }
  if ($step == 3) {
    step3($step,$dbserver,$dbname,$dbuser,$dbpass,$tbprefix,$cookieprefix);
  }
  if ($step == 4) {
    step4($step,$dbserver,$dbname,$dbuser,$dbpass,$phpurl,$images,$styledir,$path,$imagepath,$stylepath,$referer,$phpurlerror,$imageserror,$styledirerror,$patherror,$imagepatherror,$stylepatherror,$referererror,$tbprefix,$cookieprefix);
  }
  if ($step == 5) {
    step5($step,$dbserver,$dbname,$dbuser,$dbpass,$phpurl,$images,$styledir,$path,$imagepath,$stylepath,$referer,$tbprefix,$cookieprefix);
  }
  if ($step == 6) {
    step6($step,$dbserver,$dbname,$dbuser,$dbpass,$phpurl,$images,$styledir,$path,$imagepath,$stylepath,$referer,$tbprefix,$cookieprefix);
  }

// Print out the header
  function basic_header($step="") {
    echo "<HTML><HEAD>
          <STYLE TYPE=\"text/css\">
BODY,TABLE { color: #333333;
       font-family: verdana;
       font-size: 12px;
       font-weight: bold;
}

.HEADER { color: #CC9933;
	  font-size: 14px;
}
	  </STYLE>
          <TITLE>UBB.threads Installation Wizard</TITLE>
          </HEAD>
          <BODY BGCOLOR=\"#FFFFFF\">
      <table border=0 cellpadding=3><tr><td>
      <b>UBB.threads Installation Wizard</b>  
      </td></tr><tr><td>
      <img src=\"ipheader.gif\">
      </td></tr><tr><td>
      <br><b><span class=HEADER>Step $step</span></b>
      <br>
    ";
  }

// Print out the footer
  function basic_footer() {
    echo "</td></tr></table>";
    echo "</BODY></HTML>";
  }

// ----------------------------------------------------------------------
// STEP 1
  function step1() {
    $step = 1;
    $scriptname = find_environmental('PHP_SELF');
    basic_header($step);
    echo "
      <table width=600 border=1>
      <tr>
        <td colspan=2>
          In order to automate the installation procedure, we first need to make sure all of the proper files have write access.  If any of these checks fail you will need to change the permissions on these files.  (Chmod 666 or 664 on linux and granting write access to the files/directories on Windows.  Your stylesheets, images/icons, images/newicons and images/graemlins directories will probably need to be 777 so you can create new stylesheets, icons and graemlins from the admin section).
	<p>
	Note: The only files that are necessary at this point are main.inc.php, config.inc.php and theme.inc.php.  If any of the non-critical files fail you can continue the installation and change the permissions on these once the program is installed.
        </td>
      </tr>
      <tr>
        <td align=center colspan=2>
          <br>
          <span class=HEADER>Main Directory</span>
        </td>
      </tr>
      <tr>
        <td width=30%>
          <b>main.inc.php</b> 
        </td>
    ";
    $check = @fopen("main.inc.php","a");
    if (!$check) { 
      echo "<td><font color=red>FAILED</font></td>"; 
      $fail = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>config.inc.php</b> 
        </td>
    ";
    $check = @fopen("config.inc.php","a");
    if (!$check) { 
      echo "<td><font color=red>FAILED</font></td>"; 
      $fail = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>theme.inc.php</b> 
        </td>
    ";

    $check = @fopen("theme.inc.php","a");
    if (!$check) { 
      echo "<td><font color=red>FAILED</font></td>"; 
      $fail = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 
    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>filters/all</b>
        </td>
    ";

    $dir = opendir("filters");
    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $check = @fopen("filters/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ){ 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>includes/.php files</b>
        </td>
    ";

    $dir = opendir("includes");
    while ($file = readdir($dir)) {
      $check = "";
      if (!ereg(".php",$file) ) { continue; }
      $check = @fopen("includes/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ) { 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>stylesheets/all</b>
        </td>
    ";

    $dir = opendir("stylesheets");
    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $check = @fopen("stylesheets/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ) { 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 
    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>images/icons/all</b>
        </td>
    ";

    $dir = opendir("images/icons");
    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $check = @fopen("images/icons/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ) { 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 
    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>images/newicons/all</b>
        </td>
    ";

    $dir = opendir("images/newicons");
    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $check = @fopen("images/newicons/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ) { 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>images/graemlins/all</b>
        </td>
    ";

    $dir = opendir("images/graemlins");
    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $check = @fopen("images/graemlins/$file","a");
      if (!$check) {
        break;
      }
    }
    if ( (!$check) && ($file) ) { 
      echo "<td><font color=red>FAILED ($file: non-critical)</font></td>"; 
      $nocrit = 1;
    } else {
      echo "<td><font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <form method=post action=\"$scriptname\">
        <td colspan=2>
    ";

    if ($fail) {
      echo "One or more of the above files does not  have write permissions.  Please correct this and click the button below to try again.<br>
      <input type=hidden name=step value=1>
      <input type=submit name=option value=\"Check again\">
      ";
    } elseif ($nocrit) {
      echo "Some of the non-critical files do not have write permissions.  You can try to correct these now and check again or you can worry about these later and continue with the installation. NOTE: Permissions will need to be changed on these files in order to use some of the admin functions.<br>
      <input type=hidden name=step value=2>
      <input type=submit name=option value=\"Check again\">
      <input type=submit value=\"Proceed to next step\">
      ";
        
    } else {
      echo "All checks passed.<br>
      <input type=hidden name=step value=2>
      <input type=submit value=\"Proceed to next step\">
      ";
    }

    echo "
      </td></form></tr>
      </table>
    ";
    basic_footer();
  }


// -------------------------------------------------------------------------
// STEP 2
  function step2($step="",$dbserver,$dbname,$dbuser,$dbpass,$servererror,$nameerror,$usererror,$passerror) {

    if (!$dbserver) { $dbserver = "localhost"; }
    basic_header($step);
    $scriptname = find_environmental('PHP_SELF');
    echo "
      <table border=1 width=600>
        <tr>
          <td colspan=2>
            This next step consists of gathering the database information.  You will need to know the server the mysql engine is running on, the database name, the username, and the password if necessary.
          </td>
        </tr>
        <FORM METHOD=POST ACTION=\"$scriptname\">
        <input type=hidden name=step value=3>
        <tr>
          <td width=30%>
            <b>Database server</b>
          </td><td>
            <input type=text name=dbserver value=\"$dbserver\"> $servererror
          </td>
        </tr>
        <tr>
          <td width=30%>
            <b>Database name</b>
          </td><td>
            <input type=text name=dbname value=\"$dbname\"> $nameerror
          </td>
        </tr>
        <tr>
          <td width=30%>
            <b>Database user</b>
          </td><td>
            <input type=text name=dbuser value=\"$dbuser\"> $usererror
          </td>
        </tr>
        <tr>
          <td width=30%>
            <b>Database password</b>
          </td><td>
            <input type=password name=dbpass value=\"$dbpass\"> $passerror
          </td>
        </tr>
		  <tr>
			 <td width=30%>
				<b>Tablename prefix (no spaces)</b>
			 </td><td>
				<input type=text name=tbprefix value=\"$tbprefix\">
        <tr><td colspan=2>
          <INPUT TYPE=SUBMIT VALUE=\"Check database settings\">
        </FORM>
      </table>
    ";
    basic_footer();
  }



// ----------------------------------------------------------------------
// STEP 3
  function step3($step="",$dbserver="",$dbname="",$dbuser="",$dbpass="",$tbprefix="") {
    $scriptname = find_environmental('PHP_SELF');
	 $tbprefix = str_replace(" ","",$tbprefix);
	 if (!$tbprefix) { $tbprefix = "w3t_"; }

    basic_header($step);
    echo "
      <table width=600 border=1>
      <tr>
        <td colspan=2>
          Here we are checking your database connectivity and permissions.  If any of these checks fail you will need to correct the errors in order to proceed.
        </td>
      </tr>
      <tr>
        <td width=30% valign=top>
          <b>Connecting to server</b> 
        </td><td>
    ";
    $check = mysql_connect($dbserver,$dbuser,$dbpass); 
    if (!$check) { 
      echo "<font color=red>FAILED</font></td>"; 
      $servererror="<font color=red>?<font>";
      $usererror="<font color=red>?<font>";
      $passworderror="<font color=red>?<font>";
      $fail = 1;
    } else {
      echo "<font color=green>PASSED</green></td>"; 
    } 

    echo "
      </tr>
      <tr>
        <td width=30%>
          <b>Connecting to database</b> 
        </td><td>
    ";
    if (!$fail) {
       $check2 = mysql_select_db($dbname,$check);
    }
    if ( ($fail) || (!$check2) ) { 
      echo "<font color=red>FAILED</font></td>"; 
      $nameerror = "<font color=red>?<font>";
      $fail = 1;
    } else {
      echo "<font color=green>PASSED</green></td>"; 
    } 

    if ($fail) {
      echo "
      </tr>
      <tr>
        <form method=post action=\"$scriptname\">
        <td colspan=2>
      <input type=hidden name=step value=2>
      <input type=hidden name=servererror value=\"$servererror\">
      <input type=hidden name=nameerror value=\"$nameerror\">
      <input type=hidden name=usererror value=\"$usererror\">
      <input type=hidden name=passerror value=\"$passerror\">
      <input type=hidden name=dbserver value=\"$dbserver\">
      <input type=hidden name=dbname value=\"$dbname\">
      <input type=hidden name=dbuser value=\"$dbuser\">
      <input type=hidden name=dbpass value=\"$dbpass\">
		<input type=hidden name=tbprefix value=\"$tbprefix\">
      One or more of the above checks failed.  Click the button below to return to the previous screen to verify your settings.<br>
      <input type=submit value=\"Verify Settings\">
      ";
    } else {
      echo "
        </tr><tr>
        <td>
          <b>Creating a table</b>
        </td>
        <td>
      ";

      $query = "CREATE TABLE UBBT_TEST(TEST text)";
      $sth = mysql_query($query,$check);
      if (!$sth) {
        echo "<font color=red>FAILED</font>";
        $nocreate=1;
      }
      else {
        echo "<font color=green>PASSED</font>";
      }
      echo "
        </td>
        </tr><tr>
        <td>
          <b>Dropping a table</b>
        </td>
        <td>
      ";

      $query = "DROP TABLE UBBT_TEST";
      $sth = mysql_query($query,$check);
      if (!$sth) {
        echo "<font color=red>FAILED</font>";
        $nodrop=1;
      }
      else {
        echo "<font color=green>PASSED</font>";
      }

      if ( ($nocreate) || ($nodrop) ) {
        echo "
          </td></tr>
          <tr><td colspan=2>
          You do not have all of the necessary permissions to this database.  You need to make sure that the supplied username/password combination gives you full control to the specified database.
          <p>You may run the tests again if you have cleared this problem up, or you may return to the previous screen to change your database variables.<br>
          <form method=post action=\"$scriptname\">
          <input type=hidden name=step value=2>
          <input type=submit value=\"Return to previous step\">
          </form>
          <form method=post action=\"$scriptname\">
          <input type=hidden name=step value=3>
          <input type=hidden name=dbserver value=\"$dbserver\">
          <input type=hidden name=dbname value=\"$dbname\">
          <input type=hidden name=dbuser value=\"$dbuser\">
          <input type=hidden name=dbpass value=\"$dbpass\">
			 <input type=hidden name=tbprefix value=\"$tbprefix\">
          <input type=submit value=\"Run tests again\">
          </form>
        ";
      }
      else {
        echo "</td></tr>
           <form method=post action=\"$scriptname\">
           <tr><td colspan=2>
           <input type=hidden name=step value=4>
           <input type=hidden name=dbserver value=\"$dbserver\">
           <input type=hidden name=dbname value=\"$dbname\">
           <input type=hidden name=dbuser value=\"$dbuser\">
           <input type=hidden name=dbpass value=\"$dbpass\">
			  <input type=hidden name=tbprefix value=\"$tbprefix\">
           All test passed<br>
           <input type=submit value=\"Next step\">
           </form>
        ";
      }
    }
    echo "
      </td></form></tr>
      </table>
    ";
    basic_footer();
  }



// ----------------------------------------------------------------------
// STEP 4
  function step4($step="",$dbserver="",$dbname="",$dbuser="",$dbpass="",$phpurl="",$images="",$styledir="",$path="",$imagepath="",$stylepath="",$referer="",$phpurlerror="",$imageserror="",$styledirerror="",$patherror="",$imagepatherror="",$stylepatherror="",$referererror="",$tbprefix="") {
    $scriptname = find_environmental('PHP_SELF');
    basic_header($step);

    if (!$phpurl) {
      $What = find_environmental('REQUEST_URI');
      $script['0'] = "";
      preg_match("/(.*)\/(.*)\.php/",$What,$script);
      $url = $script['1'];
      $phpurl = $url;
    }
    if (!$images) {
      $images = "$url/images";
    }
    if (!$path) {
      $path = find_environmental('SCRIPT_FILENAME');
      $script['0'] = "";
      preg_match("/(.*)\/(.*)\.php/",$path,$script);
      $path = $script['1'];
    }
    if (!$imagepath) {
      $imagepath = $path . "/images";
    }
    if (!$styledir) {
      $styledir = "$url/stylesheets";
    }
    if (!$stylepath) {
      $stylepath = $path . "/stylesheets";
    }
    if (!$referer) {
      $referer = "http://" . find_environmental('HTTP_HOST') . "/";
    }
    
    echo "
      <table width=600 border=1>
      <tr>
        <td colspan=2>
          Next we need to gather information on the paths and url of your installation.  Some of these values are pre-filled but may not be correct depending on your system.  Please change if necessary.
        </td>
      </tr>
      <form method=post action=\"$scriptname\">
      <tr>
      <td><br>
        <b>The domain UBB.threads will be run on</b><br>
        <input name=referer type=text size=50 value=\"$referer\"> $referererror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Absolute URL to the UBB.threads install</b><br>
        <input name=phpurl type=text size=50 value=\"$phpurl\"> $phpurlerror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Absolute URL to image directory</b><br>
        <input name=images type=text size=50 value=\"$images\"> $imageserror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Absolute URL to stylesheets directory</b><br>
        <input name=styledir type=text size=50 value=\"$styledir\"> $styledirerror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Path to your UBB.threads install</b><br>
        <input name=path type=text size=50 value=\"$path\"> $patherror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Path to your images directory</b><br>
        <input name=imagepath type=text size=50 value=\"$imagepath\"> $imagepatherror
      </td>
      </tr>
      <tr>
      <td><br>
        <b>Path to your stylesheets directory</b><br>
        <input name=stylepath type=text size=50 value=\"$stylepath\"> $stylepatherror
      </td>
      </tr>
		<tr>
		<td><br>
			<b>Custom cookie prefix</b> (Can be left blank. Useful if you have 2 installs and don't want cookies from one causing problems with the other)<br>
			<input name=cookieprefix type=text value=\"$cookieprefix\">
		</td>
		</tr>
      
      <input type=hidden name=step value=5>
      <input type=hidden name=dbserver value=\"$dbserver\">
      <input type=hidden name=dbname value=\"$dbname\">
      <input type=hidden name=dbuser value=\"$dbuser\">
      <input type=hidden name=dbpass value=\"$dbpass\">
		<input type=hidden name=tbprefix value=\"$tbprefix\">
      <tr><td>
      <input type=submit value=\"Check settings\"></td></tr>
      </form>
    ";
    
    echo "</table>";
    basic_footer();
  }




// ----------------------------------------------------------------------
// STEP 5
  function step5($step="",$dbserver="",$dbname="",$dbuser="",$dbpass="",$phpurl="",$images="",$styledir="",$path="",$imagepath="",$stylepath="",$referer="",$cookieprefix="",$tbprefix="") {

    $scriptname = find_environmental('PHP_SELF');
    basic_header($step);
    echo "
      <table width=600 border=1>
      <tr>
        <td colspan=2>
          Checking all path/url information.  Please make sure you have all files uploaded, or you will get false reporing. NOTE: The first 3 might fail if PHP is unable to open files via a URL.  If that is the case you may continue to the next step.<br>
        </td>
      </tr>
   ";

   echo "
     <tr><td width=30%>
     <b>phpurl</b>
     </td><td>($referer$phpurl) 
   ";
   $check = @fopen("$referer/$phpurl/createtable.php","r");
   if (!$check) {
     echo "<font color=red>UNABLE TO OPEN</font>";
     $nocrit=1;
     $phpurlerror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   echo "
     <tr><td width=30%>
     <b>images</b>
     </td><td>($referer$images) 
   ";
   $check = @fopen("$referer/$images/flat.gif","r");
   if (!$check) {
     echo "<font color=red>UNABLE TO OPEN</font>";
     $nocrit = 1;
     $imageserror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   echo "
     <tr><td width=30%>
     <b>styledir</b>
     </td><td>($referer$styledir) 
   ";
   $check = @fopen("$referer/$styledir/infopop.css","r");
   if (!$check) {
     echo "<font color=red>UNABLE TO OPEN</font>";
     $nocrit = 1;   
     $styledirerror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   echo "
     <tr><td width=30%>
     <b>path</b>
     </td><td>($path) 
   ";
   $check = @fopen("$path/createtable.php","r");
   if (!$check) {
     echo "<font color=red>FAILED</font>";
     $fail = 1;
     $patherror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   echo "
     <tr><td width=30%>
     <b>imagepath</b>
     </td><td>($imagepath) 
   ";
   $check = @fopen("$imagepath/flat.gif","r");
   if (!$check) {
     echo "<font color=red>FAILED</font>";
     $fail = 1;
     $imagepatherror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   echo "
     <tr><td width=30%>
     <b>stylepath</b>
     </td><td>($stylepath) 
   ";
   $check = @fopen("$stylepath/infopop.css","r");
   if (!$check) {
     echo "<font color=red>FAILED</font>";
     $fail = 1;
     $stylepatherror="<font color=red>?</font>";
   }
   else {
     echo "<font color=green>PASSED</font>";
   }
   echo "</td></tr>";

   if ( ($fail) || ($nocrit) ) {
     echo "<tr><td colspan=2><br>One or more of the above checks have failed.  You might need to go back and verify your settings.<form method=post action=\"$scriptname\">
     <input type=hidden name=step value=6>
     <input type=hidden name=dbserver value=\"$dbserver\">
     <input type=hidden name=dbname value=\"$dbname\">
     <input type=hidden name=dbuser value=\"$dbuser\">
     <input type=hidden name=dbpass value=\"$dbpass\">
     <input type=hidden name=phpurl value=\"$phpurl\">
     <input type=hidden name=images value=\"$images\">
     <input type=hidden name=styledir value=\"$styledir\">
     <input type=hidden name=path value=\"$path\">
     <input type=hidden name=imagepath value=\"$imagepath\">
     <input type=hidden name=stylepath value=\"$stylepath\">
	  <input type=hidden name=cookieprefix value=\"$cookieprefix\">
     <input type=hidden name=referer value=\"$referer\">
     <input type=hidden name=phpurlerror value=\"$phpurlerror\">
     <input type=hidden name=imageserror value=\"$imageserror\">
     <input type=hidden name=styledirerror value=\"$styledirerror\">
     <input type=hidden name=patherror value=\"$patherror\">
     <input type=hidden name=imagepatherror value=\"$imagepatherror\">
     <input type=hidden name=stylepatherror value=\"$stylepatherror\">
     <input type=hidden name=referererror value=\"$referererror\">
     <input type=text name=tbprefix value=\"$tbprefix\">
     <input type=submit name=option value=\"Verify url/path settings\">
     <input type=submit name=option value=\"Proceed to next step\">
   ";
  }
  else {
    echo "<tr><td colspan=2><br>
      All checks passed.
     <form method=post action=\"$scriptname\">
     <input type=hidden name=step value=6>
     <input type=hidden name=dbserver value=\"$dbserver\">
     <input type=hidden name=dbname value=\"$dbname\">
     <input type=hidden name=dbuser value=\"$dbuser\">
     <input type=hidden name=dbpass value=\"$dbpass\">
     <input type=hidden name=phpurl value=\"$phpurl\">
     <input type=hidden name=images value=\"$images\">
     <input type=hidden name=styledir value=\"$styledir\">
     <input type=hidden name=path value=\"$path\">
     <input type=hidden name=imagepath value=\"$imagepath\">
     <input type=hidden name=stylepath value=\"$stylepath\">
     <input type=hidden name=cookieprefix value=\"$cookieprefix\">
     <input type=hidden name=referer value=\"$referer\">
     <input type=hidden name=tbprefix value=\"$tbprefix\">
     <input type=submit value=\"Next Step\">
   ";
  }

  echo "</td></tr>";

   echo "</table>";

    basic_footer();
  }




// ----------------------------------------------------------------------
// STEP 6
  function step6($step="",$dbserver="",$dbname="",$dbuser="",$dbpass="",$phpurl="",$images="",$styledir="",$path="",$imagepath="",$stylepath="",$referer="",$cookieprefix="",$tbprefix="") {

    global $thisversion;
    $scriptname = find_environmental('PHP_SELF');

  // -------------------------------------
  // Let's print out the basic config file
$newconfig = <<<EOF
<?

  \$VERSION = "$thisversion";

  // ------------------
  // Database Variables

  // Server hosting the database
    \$config['dbtype'] = "mysql";

    \$config['dbserver']=	"$dbserver";

  // Username that has permissions to the database
    \$config['dbuser']	=	"$dbuser";

  // Password for the database
    \$config['dbpass']	= 	"$dbpass";

  // Name of the database
    \$config['dbname']	=	"$dbname";

  // Table prefix (Do not edit after initial install)
	 \$config['tbprefix'] = "$tbprefix";

  // ----------------------
  // Path and Url variables

  // Url to the main UBB.threads php install
    \$config['phpurl']	=	"$referer$phpurl";

  // Absolute Url containing the images
    \$config['images']	=	"$images";

  // Full url to images.
	 \$config['imageurl'] = "$referer$images";

  // Path to your images directory, needed for calculating image sizes
    \$config['imagepath'] =	"$imagepath";

  // Url to your stylesheets directory
    \$config['styledir'] =       "$styledir"; 

  // Path to your UBB.threads php install
    \$config['path']	=	"$path";

  // Path to your stylesheets directory
    \$config['stylepath'] =	"$stylepath";

  // Domain that UBB.threads is running under.
    \$config['referer'] =	"$referer";

  // Do you want to use sessions or cookies to track your users
  // values = "cookies" or "sessions";
    \$config['tracking'] =	"cookies";

  // If you are using cookies you can specify a path to make them available
  // to other areas of your website.  If you only want them to be available
  // to things within your UBB.threads installation directory leave this blank
  // If you want them available to your whole website, specify a path of "/";
     \$config['cookiepath'] = "";

  // Custom cookie prefix.  Can be left blank.  Useful if you have 2 installs
  // and don't want the cookies from one overwriting the cookies from the other.
    \$config['cookieprefix'] = "$cookieprefix";

    \$config['language'] = "english";

  // --------------------------
  // Site specific / Navigation

  // Do you want to do full new post tracking? Show the number of new posts on
  // a board/thread and show which posts are new in a thread? This option can
  // contribute to high load on forums with hundreds of users online at once due
  // to the number of queries that are necessary.
    \$config['newcounter'] =    1;

  // What groups do you want new users to belong to?  THIS SHOULD ONLY
  // BE CHANGED IN THE ONLINE EDITOR, UNLESS YOU REALLY KNOW WHAT YOU ARE
  // DOING
    \$config['newusergroup'] = "-3-";

  // Title of site
    \$config['title']	=	"Your new forums";

  // Your Home page URL
    \$config['homeurl']	=	"http://www.yourdomain.com";

  // Title of Homepage link
    \$config['urltitle']=	"Title for link";

  // Email address
    \$config['emailaddy']=	"";

  // EMail Title
    \$config['emailtitle']=	"Contact Us";

  // Show privacy statement link in footer
    \$config['privacy'] = "0";

  // -----------------
  // Special Functions
  // Allow users to specify their password at the time of creating the userid
    \$config['userpass']	=	1;
 
  // Do you want to use sessions or cookies to track your users
  // values = "cookies" or "sessions";
    \$config['tracking'] =	"cookies";
 
  // This one variable is used to open or close your forums.  Set this to
  // 1 if you want to work on your forums.  If this is set to 1 then your
  // forums will only be accessible by the admin users.  Make sure you are
  // logged in as an admin before you close the forums.
    \$config['isclosed'] =	0;

  // Show the user list in the navigation menu?
  // 0 = No : 1 = Yes
    \$config['userlist'] =	1;

  // Allow users to turn markup / html (if enabled) on or off when they post
  // 0 = No : 1 = Yes
    \$config['markupoption'] = 0;

  // Do you want users to be able to mail posts to others
  // 0 = No ; 1 = Yes
    \$config['mailpost'] =  1;
 
  // What type of main page do you want to use.
  // 0 = all categories and forums	- mainpage = ubbthreads.php
  // 1 = categories only		- mainpage = categories.php
    \$config['catsonly'] =	0;

  // Allow all users to place polls in posts? If this is off, then only
  // admin and moderators may use the polls feature
    \$config['allowpolls'] =	1;

  // Do you want everyone to vote, or just registered users?
  // 1 = everyone : 0 = registered
    \$config['whovote'] =	1;

  // How long after a post has been made, can it be edited by the poster?
  // This variable is in hours, so you can also use .5 for values of less
  // than an hour for example
    \$config['edittime']=	"6";

  // Strip everything between < and > signs in email replies and subscriptions
    \$config['stripcodes'] =	0;

  // Check the user's age before creating an account to be compliant with the
  // COPPA law found at http://www.ftc.gov/opa/1999/9910/childfinal.htm
  // Values = 1 or 0 
    \$config['checkage']=	0;

  // Allow special characters in usernames
  // Values = 1 or 0 
    \$config['specialc']=	0;

  // Allow moderators to edit/delete regular users
  // Values = 1 or 0 
    \$config['modedit']	=	1;

  // Allow users that are not logged in to choose an unregistered name
  // for their post instead of the default Anonymous
    \$config['anonnames'] =	1;

  // Default language.  Options can be found in the languages directory
    \$config['language']= 	"english";

  // Censored replacement word.  If you have anything in your filters/badwords
  // file then any words matched when adding a new post will be replaced with
  // this word.  Set this to "" if you do not want to censor words.
    \$config['censored']=	"[censored]";

  // Max length of subjects
    \$config['subjectlength']=	"50";

  // Max length of signatures
    \$config['Sig_length']=	"100";

  // When persistent cookies will expire (Number of seconds from current time)
    \$config['cookieexp']=	"30758400";

  // If displayed times need to be adjusted, in hours (2,1,0,-1,-2)
    \$config['adjusttime']=	"0";		 

  // Show the ip address of the posters
  // 0 - Show to nobody
  // 1 - Show to everyone
  // 2 - Show to moderators and admins
  // 3 - Show to admins only
    \$config['showip']	=	1;

  // Allow subscriptions to boards.  If you allow this then you have to
  // have subscriptions.php in the cron directory setup to run nightly
    \$config['subscriptions']=	1;

  // Allow private messages.  User's will still receive the welcome message
  // and private messages will still be used for admin purposes, but general
  // users will not be able to send private messages.
    \$config['private']	=	1;

  // Allow the [image] markup tag.
    \$config['allowimages']=	1;

  // Allow File Attachemnts.  If you provide a path to a directory below
  // then File Attachments will be allowed.  If you leave it blank, then they
  // will not.  Make sure this directory is writeable by everyone
    \$config['files']	=	"";

  // We also need the url to the files directory
    \$config['fileurl']	=	"";

    \$config['allowfiles'] = ".gif,.jpg,.txt,.zip,.png";

  // Maximum filesize allowed
    \$config['filesize']	=	"100000";


  // Allow multiple usernames form the same email address
  // On- allow multiple usernames from same email.  off - disallow
    \$config['multiuser']	=	0;

  // Default to having a preview post screen?
    \$config['preview']	=	1;

  // Dateslip.  If this is on, when replies are made to a thread, this thread
  // will be moved up to the top of the post list when sorting by descending
  // date.  If this is off, then threads will stay in their original spot.
    \$config['dateslip']	=	1;

  // These are the extra fields for users to fill out in their profiles.
  // If you leave these blank they will not be users.  If you give them a
  // title then they will show up on the user's profile page.
    \$config['extra1']	=	"ICQ";
    \$config['extra2']	=	"";
    \$config['extra3']	=	"";
    \$config['extra4']	=	"";
    \$config['extra5']	=	"";

  // Use the ICQ status indicator.  Depending on the size of your user database
  // you might be required to turn this off.  Please read the ICQ license
  // agreemtn at http://www.icq.com/legal/indicator.html
    \$config['ICQ_Status']	= 1;

?>
EOF;

  // --------------------
  // Write the new config
     $fd = fopen("config.inc.php","w");
     fwrite($fd,$newconfig);
     fclose($fd);

  // -----------------------------------------------------------
  // Now let's write the main.inc.php file with the proper path
$mainfile = <<<EOF
<? 

// THIS NEEDS TO BE SET, OTHERWISE ADMIN SCRIPTS WILL NOT WORK
// THIS IS THE ACTUAL PATH TO YOUR PHP INSTALLATION
   \$thispath = "$path";

// PATH TO YOUR config.inc.php file.  BY DEFAULT THIS IS THE
// SAME AS $thispath, BUT IF YOU MOVE config.inc.php TO ANY
// OTHER LOCATION YOU MUST SPECIFY IT HERE.
   \$configdir = "$path";

// DO NOT EDIT ANYTHING BELOW THIS LINE!
   include("\$thispath/ubbt.inc.php");

// Page load times
   \$timea = getmicrotime();

?>
EOF;

   $fd = fopen("main.inc.php","w");
   fwrite($fd,$mainfile);
   fclose($fd);

   $step = 6;
    basic_header($step);
    echo "
      <table width=600 border=1>
      <tr>
        <td colspan=2>
          All config files have been created.  You may now proceed to <a href=\"createtable.php\">creating the database tables</a>.
        </td>
      </tr>
		</table>
   ";
   basic_footer();

}

function find_environmental ($name) {

	global $HTTP_SERVER_VARS;

	$this = "";

	// Regular way
	if(getenv($name) != '') {
		$this = getenv("$name");
	} // end if

	// Irregular way
	if(($this == '') && ($HTTP_SERVER_VARS["$name"] != '')) {
		$this = $HTTP_SERVER_VARS["$name"];
	} // end if

	// 4.1 way
	if(($this == '') && ($_ENV["$name"] != '')) {
		$this = $_ENV["$name"];
	} // end if

	return $this;
} // end func


?>
