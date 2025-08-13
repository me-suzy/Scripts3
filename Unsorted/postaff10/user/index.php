<?
session_start();
include "../affconfig.php";
include "./lang/$language";

if ($_POST['userid']!='' && $_POST['password']!='')
{
  // protection against script injection
  $userid = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['userid']);
  $password = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['password']);
    
  // if the user has just tried to log in
  $db_conn = mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 11)"); 
  mysql_select_db($database, $db_conn);
  $query = "select * from affiliates where refid='$userid' and pass='$password'";
  $result = mysql_query($query, $db_conn);
  if (mysql_num_rows($result) >0 )
  {
    // if they are in the database register the user id
    $_SESSION['aff_valid_user'] = $userid;
    // logout admin if he was logged in before
    $_SESSION['aff_valid_admin'] = '';
    unset($_SESSION['aff_valid_admin']);  
    echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=0;URL=members_only.php>";
    exit;
  }
}
 
  include "header.php"; 
 

  if(aff_check_security())
  {
    aff_redirect('members_only.php');
    exit;
  }
  else
  {
    if (isset($_POST['userid']))
    {
      // if they've tried and failed to log in
      echo AFF_I_CANNOTLOG;
    }
    else 
    {
      // they have not tried to log in yet or have logged out
      echo AFF_I_NOTLOGGED;
    }

    // provide form to log in
?>    
    <form method=post action="index.php">
    <table align=center border=0>
    <tr><td><?=AFF_G_AFFID?>:</td>
    <td><input type=text name=userid></td></tr>
    <tr><td><?=AFF_G_PASSWORD?>:</td>
    <td><input type=password name=password></td></tr>
    <tr><td colspan=2 align=center>
    <input type=submit value="Log in"></td></tr>
    </table></form>

    <p>
    <?=AFF_I_NOTAFFILIATE?> <a href=signup.php><?=AFF_I_SIGNUP?></a>
    </p>

<?    
  }
?>
</p>
<br>
<?PHP         
include "footer.php"; 
?>