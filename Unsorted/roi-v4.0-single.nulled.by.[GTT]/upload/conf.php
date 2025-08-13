<?php
  include("smalltemplater.php");
  require("defines.php");

 function Assign($var,$data){
  global $VARS;
  $VARS[$var] = $data;
 }
 function ShowTemplate($data){
  global $VARS;
  $lines = File("templates/header.htm");
  $lines = implode('',$lines);

  foreach($VARS as $key => $val){
    $lines = str_replace("%$key%",$val,$lines);
  }

  echo $lines;
  echo $data;
  $lines = File("templates/footer.htm");
  $lines = implode('',$lines);

  foreach($VARS as $key => $val){
    $lines = str_replace("%$key%",$val,$lines);
  }

  echo $lines;
  exit(1);
 }


  function LoginTo(){
   session_start();
   if(!isset($_SESSION['pass']) || $_SESSION['pass'] != PASSWORD){
    header("Location: index.php");
    exit(1);
   }
  }

  LoginTo();
  
  Assign('image_label','label-configuration.gif');
  $lines = File('templates/conf.htm');

$myerror = array();

function addError($val){ global $myerror; $myerror[sizeof($myerror)] = $val; }

function pError($header,$body){
  $data = "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font size=6 face=verdana>$header</font><br>".
  "&nbsp;&nbsp;&nbsp;&nbsp;<font class=title>$body</font><br><br><br><br>";
  Assign('image_label','label-configuration.gif');
  ShowTemplate($data);
}

function outError(){ global $myerror; $rv = '';
         foreach ( $myerror as $key => $val ){
         $rv .= "<img src=\"images/bullet.gif\" width=16 height=10 border=0 align=absmiddle><span class=title><font color=#A70000>$val</font></span><br>";}
         return $rv;
}

  
$uid = $_SESSION['uid'];

if ($uid != 0) { pError("Access denied.","Sorry, only 'root' has access to this page"); }

   $res = @mysql_connect(DBHOST,DBUSER,DBPASS);
   $res = @mysql_selectdb(DBNAME);



##############################################################################
 if(isset($HTTP_POST_VARS['adduser']) && strlen($HTTP_POST_VARS['adduser'])>0 ||
  isset($HTTP_POST_VARS['adduser_x']) && strlen($HTTP_POST_VARS['adduser_x'])>0)
  {

   $pw_good = false;
   $name_good = false;

   if ($HTTP_POST_VARS['username'] == '') { addError("Name is required!"); }
    else
     {
	  if ( !ereg('^[A-Za-z0-9_\-]{0,20}$',$HTTP_POST_VARS['username']) ) {addError("Name must be only digits,<br>latin symbols and _ or - symbols"); }
          else { $name_good = true;  }
     }

   if ($HTTP_POST_VARS['userpw1'] != $HTTP_POST_VARS['userpw2'])
   { addError("Passwords isn't equal"); }
   else
    {
 	if ($HTTP_POST_VARS['userpw1'] == '') { addError("Password is required!"); }
	else
	 {
	   if (!ereg('^[A-Za-z0-9_\-]{0,20}$',$HTTP_POST_VARS['userpw1'])){
              addError("Password must be only digits,<br>latin symbols and _ or - symbols"); } else
		{		$pw_good = true;  }
	 }
    }

  if ($pw_good && $name_good)
   {

   $un = $HTTP_POST_VARS['username'];
   $upw = $HTTP_POST_VARS['userpw1'];
   $res = mysql_query("SELECT * FROM ROIusers WHERE name = '$un';");
    if (mysql_num_rows($res) != 0) { addError("User with this name already exist"); }
    else
     {

        $res = @mysql_query("INSERT INTO ROIusers(name,pw) VALUES('$un','$upw');");
        if (!$res) {addError("Error! Could add user to DB.");}
     }
   }

  }
##############################################################################################

  if(isset($HTTP_POST_VARS['userdel']) && strlen($HTTP_POST_VARS['userdel']) > 0 ||
  isset($HTTP_POST_VARS['userdel_x']) && strlen($HTTP_POST_VARS['userdel_x']) > 0 )
  {
   if ($HTTP_POST_VARS['delid'] == $_SESSION['uid']) {addError("You cannot delete yourself.");}
   else {
	$d_uid = $HTTP_POST_VARS['delid'];
        $res1 = @mysql_query("DELETE FROM ROIusers WHERE id = $d_uid");
        $res2 = @mysql_query("DELETE FROM ROIactions WHERE user_id = $d_uid");
        $res3 = @mysql_query("DELETE FROM ROIcampaigns WHERE user_id = $d_uid");
        $res4 = @mysql_query("DELETE FROM ROIclicks WHERE user_id = $d_uid");
        $res5 = @mysql_query("DELETE FROM ROIhostlogs WHERE user_id = $d_uid");
        $res6 = @mysql_query("DELETE FROM ROIiplogs WHERE user_id = $d_uid");
        $res7 = @mysql_query("DELETE FROM ROIlogs WHERE user_id = $d_uid");

	if (!($res1 && $res2 && $res3 && $res4 && $res5 && $res6 && $res7)) {addError("Error! Could delete user from DB.");}
 	}
  }
###############################################################################################


  $lines = implode('',$lines);
  $lines = str_replace("%user%",USER,$lines);
  $lines = str_replace("%dbname%",DBNAME,$lines);
  $lines = str_replace("%dbhost%",DBHOST,$lines);
  $lines = str_replace("%dbuser%",DBUSER,$lines);
  $lines = str_replace("%dbpass%",DBPASS,$lines);




  if(isset($HTTP_POST_VARS['password']) && strlen($HTTP_POST_VARS['password']) > 0 ||
  isset($HTTP_POST_VARS['password_x']) && strlen($HTTP_POST_VARS['password_x']) > 0 ){
   if($HTTP_POST_VARS['pass'] != PASSWORD)addError("Old password is not valid.");
   else{

    if(strlen($HTTP_POST_VARS['user']) == 0)addError("User field is empty.");
    else if(strlen($HTTP_POST_VARS['pass2']) == 0){
      if(strlen($HTTP_POST_VARS['user'])>0 && $HTTP_POST_VARS['user'] != USER)writePassword(PASSWORD,$HTTP_POST_VARS['user']);
      addError("Passwords fields is empty");
    }
    else if($HTTP_POST_VARS['pass2'] != $HTTP_POST_VARS['pass3'])addError("New passwords isn't equal");
    else if(!ereg('^[A-Za-z0-9_\-]{0,20}$',$HTTP_POST_VARS['pass2']))addError("Password must be only digits, latin symbols and _ or - symbols");

    if(sizeof($myerror)==0)writePassword($HTTP_POST_VARS['pass2'],$HTTP_POST_VARS['user']);
   }
  }
  
  if(isset($HTTP_POST_VARS['dbchange']) && strlen($HTTP_POST_VARS['dbchange'])>0 ||
  isset($HTTP_POST_VARS['dbchange_x']) && strlen($HTTP_POST_VARS['dbchange_x'])>0){
   writeDatabase($HTTP_POST_VARS['dbpass'],$HTTP_POST_VARS['dbhost'],$HTTP_POST_VARS['dbuser'],$HTTP_POST_VARS['dbname']);
  }

  function writePassword($pass,$user){
   $rv.='<?php
   define("PASSWORD","'.$pass.'");
   define("USER","'.$user.'");
   define("TIMEOUT",1800);
   define("DBHOST","'.DBHOST.'");
   define("DBUSER","'.DBUSER.'");
   define("DBPASS","'.DBPASS.'");
   define("DBNAME","'.DBNAME.'");
?>';
  WriteFile($rv);
  }
  function writeDatabase($dbpass,$dbhost,$dbuser,$dbname){
   $rv.='<?php
   define("PASSWORD","'.PASSWORD.'");
   define("USER","'.USER.'");
   define("TIMEOUT",1800);
   define("DBHOST","'.$dbhost.'");
   define("DBUSER","'.$dbuser.'");
   define("DBPASS","'.$dbpass.'");
   define("DBNAME","'.$dbname.'");
?>';
   WriteFile($rv);
  }
  function WriteFile(&$rv){
    $ls = fopen("defines.php","w");
    fwrite($ls,$rv);
    fclose($ls);
    Header("Location: conf.php");
    exit(1);
    //ShowTemplate("<center><span type=title>Your settings was changed</span></center>");
  }
  $lines = str_replace("%errors%",outError(),$lines);


$res = mysql_query("SELECT * FROM ROIusers;");
if (mysql_num_rows($res) == 0) {   $lines .= '</table></td></tr></table><br>'; }
else
{
$lines .= "<tr><td colspan=2><b>Delete user section</td></tr><tr><td>User name:</td><td><select name=delid class=txt width=50>";

while ( $uarr=mysql_fetch_array($res) )
{
$del_id = $uarr['id'];
$del_name = $uarr['name'];
 
$lines .= "<option value=$del_id>$del_name</option>";
}

$lines .= "</select></td></tr>
<tr>
<td>&nbsp;</td>
<td><input type=image src=\"images/button-delete.gif\" border=0 name=\"userdel\" value=change alt=\"Delete\" onclick=\"return confirm('Are you sure delete this user?');\"></td>
</tr>
</table></td></tr></table><br>";

}


  ShowTemplate($lines);
?>
