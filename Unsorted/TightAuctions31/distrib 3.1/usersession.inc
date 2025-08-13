<?

function getUserSessionExpiration()
{
  return 1200;
}

function getUserSessionKey()
{
  return "myjklfjasfkey";		// replace with your own private encoding key
}

function encode($encode_str, $pass_str) 
{ 
  $data = mysql_query("select encode('$encode_str', '$pass_str')"); 
  $row = mysql_fetch_row($data); 
  return $row[0]; 
} 

function decode($decode_str, $pass_str) 
{ 
  $data = mysql_query("select decode('$decode_str', '$pass_str')"); 
  $row = mysql_fetch_row($data); 
  return $row[0]; 
} 

function GetSessionID()
{
  $SessionIDLen = 10;

  $Pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $Pool .= "abcdefghijklmnopqrstuvwxyz";
  
  for ($index = 0; $index < $SessionIDLen; $index++ )
  {
    $sid .= substr($Pool, (rand() % (strlen($Pool))), 1);
  }

  return $sid;
}

function NewLogin($MemberID, $SavePwd)
{
  global $DB_Username, $DB_Password;

  // A DB connection is needed for the encode/decode functions

  $link = mysql_pconnect("localhost", $DB_Username, $DB_Password); 
  mysql_select_db($DB_Database, $link );

  if ( $SavePwd )
    $SavePwdTime = 60 * 60 * 24 * 365 * 4;		// 4 years
  else
    $SavePwdTime = 0;

  setcookie( "memCookie", encode($MemberID, getUserSessionKey()), time() + getUserSessionExpiration() + $SavePwdTime, "/" );  

  $Encoding = $MemberID;
  $Encoding .= "<>";
  $Encoding .= sprintf("%d", time());
  
  setcookie( "memkCookie", encode($Encoding, getUserSessionKey()), time() + getUserSessionExpiration() + $SavePwdTime, "/" );  

  if ( $SavePwd )
    setcookie( "SavePwdCookie", "true", time() + getUserSessionExpiration() + $SavePwdTime, "/" );      
  else
    setcookie( "SavePwdCookie", "", time()-3600, "/" );
}

function Logout()
{
  setcookie( "memCookie", "", time()-3600, "/" );
  setcookie( "memkCookie", "", time()-3600, "/" );
  setcookie( "SavePwdCookie", "", time()-3600, "/" );
}

function IsLoggedIn()
{  
  global $memCookie, $memkCookie;  
  global $DB_Username, $DB_Password;

  $memberIDValid = 0;

  $cookiesSet = isset($memCookie) && isset($memkCookie);

  if ( $cookiesSet )
  {
    // A DB connection is needed for the encode/decode functions

	$link = mysql_pconnect("localhost", $DB_Username, $DB_Password); 
	mysql_select_db($DB_Database, $link );

    $temp = decode($memkCookie, getUserSessionKey());
    $temp = strtok( $temp, "<>" );

    $memberID =  decode($memCookie, getUserSessionKey());

    if ( strcmp( $memberID, $temp ) == 0 )
      $memberIDValid = 1;
    else   
      $memberIDValid = 0;               
  }

  return ($cookiesSet && $memberIDValid);
}

function GetSessionMemberID()
{  
  global $memCookie, $memkCookie;
  global $DB_Username, $DB_Password;

  if ( IsLoggedIn() )
  {
    // A DB connection is needed for the encode/decode functions

    $link = mysql_pconnect("localhost", $DB_Username, $DB_Password); 
    mysql_select_db($DB_Database, $link );

    $temp = decode($memkCookie, getUserSessionKey());
    $temp = strtok( $temp, "<>" );

    if ( strcmp( decode($memCookie, getUserSessionKey()), $temp ) == 0 )
      $memberIDValid = true;
    else
      $memberIDValid = false;                      
    
    return $temp;
  }

  return "";
}

function GetSessionUserID()
{
  global $memCookie, $memkCookie;
  global $DB_Username, $DB_Password;

  $MemberID = GetSessionMemberID();
  if ( $MemberID == "" )
	return -1;

  $link = mysql_pconnect("localhost", $DB_Username, $DB_Password); 
  mysql_select_db($DB_Database, $link );

  $query = "SELECT UserAccountID FROM UserAccounts WHERE MemberID='$MemberID'";
  $result = mysql_query( $query, $link );

  if ( !$result )
	echo mysql_error();

  if ( $row = mysql_fetch_row( $result ) )
    return $row[0];
  else
    return -1;
}

/*
  Refreshes the user session cookie.  
*/
function UpdateUserSession()
{
  global $memCookie, $memkCookie, $SavePwdCookie;

  if ( IsLoggedIn() && !isset($SavePwdCookie) )
  {
    setCookie( "memCookie", $memCookie, time() + getUserSessionExpiration(), "/" );  
    setCookie( "memkCookie", $memkCookie, time() + getUserSessionExpiration(), "/" );  
  }
}

/*
  Validates to see if the user is logged in, and redirects the user to the signin page if not.
*/
function ValidateLoginRedirect()
{
  global $HomeInstall;

  if ( !IsLoggedIn() )
  {
    print("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=$HomeInstall/signin.php\">"); 
    print("<body><p><font color=\"#FF0033\">Not logged in. Redirecting to signin page.</font></p></body></html>\n");    
    exit;
  }
}

function GetNumAdsToDisplay()
{
  global $AdsCookie;

  if ( !isset($AdsCookie) )
  {
    SaveNumAdsToDisplay( 25 );
    return 25;
  }
  else
    return $AdsCookie;
}

function SaveNumAdsToDisplay( $NumToDisplay )
{
    setcookie( "AdsCookie", $NumToDisplay, time() + 60 * 60 * 24 * 365 * 4, "/" );  
}

function GetAdDisplayOptions()
{
  $DisplayOptions = array( 25, 50 );

  return $DisplayOptions;
}

?>