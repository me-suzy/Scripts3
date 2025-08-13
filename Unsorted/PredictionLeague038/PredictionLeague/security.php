<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 9th December
 * File  : security.php
 * Desc  : usertypes:
 *       :     1 - Normal User
 *       :     2 - Priveleged User
 *       :     4 - Admin User
 *       :     8 - Root User
 *
 ********************************************************/

  function login($userid, $pwd) {
    global $User;

    // Try to register up to 3 times
    $errcnt = 0;
    while (!session_register("User")) {
      $errmsg = "Unable to register User variable during login for $userid : ".$User;
      LogMsg($errmsg);
      if ($errcnt >= 3) {
        // Try to register manually
        $HTTP_SESSION_VARS["User"] = $User;
        @mail("john@astill.org",$PredictionLeagueTitle." Error",$errmsg);
        break;
      }
      $errcnt++;
    }
    if ($errcnt == 0) {
      LogMsg("Successfully registered $userid during login");
    }

    // If the user login has timed out, forward to the
    // PredictionIndex.
    if (CheckUserLogin($userid, $pwd) == FALSE) {
      /* Redirect browser to Prediction Index web site */
      header("Location: PredictionIndex.php"); 
      /* Make sure that code below does not get executed when we redirect. */
      exit; 
    }

    /* Redirect browser to user predictions web site */
    header("Location: ShowMyPredictions.php?user=$userid"); 
    /* Make sure that code below does not get executed when we redirect. */
    exit; 
  }

 /********************************************************
  * Check if the given user has admin priveleges.
  * @param perms the users current permissions.
  *******************************************************/
  function CheckAdmin($perms) {
    global $dbaseUserData;

    $NormalUser = 1;
    $PrivelegedUser = 2;
    $AdminUser = 4;
    $RootUser = 8;
    
    return $perms >= $AdminUser;
  }

 /********************************************************
  * Check if the given user is logged in.
  * @param user the user to check.
  * @param pwd the password of the user to check.
  *******************************************************/
  function CheckUserLogin($userid, $pwd) {
    // Needs global include SystemVars.php
    global $dbaseUserData, $User, $useEncryption;

    // if encryption enabled encrypt the password
    if ($useEncryption == TRUE) {
      $pwd = md5($pwd);
    }

    // Default the login to false.
    $User = new User();
    $User->loggedIn = FALSE;
    $User->usertype = 1;

    $link = OpenConnection();
    if ($link == FALSE) {
      return FALSE;
    }

    $userquery = "SELECT * FROM $dbaseUserData where username = \"$userid\"";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");
    if ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      if ($pwd == $userline["password"]) {
        // The passwords are equal. Log the user in, and update the 
        // session data.
        $User->userid = $userid;
        $User->pwd = $pwd;
        $User->emailaddr = $userline["email"];
        $User->icon = $userline["icon"];
        $User->usertype = $userline["usertype"];
        $User->createdate = $userline["since"];
        $User->loggedIn = TRUE;

        return TRUE;
      }
    }
    CloseConnection($link);
    return FALSE;
  }
?>
