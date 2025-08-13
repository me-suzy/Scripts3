<?

function do_html_heading($heading)
{
  // print heading
?>
  <h2><?=$heading?></h2>
<?
}


function display_login_form()
{
?>
  <a href="register.php">Not a member?</a>
  <form method=post action="member.php">
  <table bgcolor="#cccccc" width="100%">
   <tr>
     <td colspan=2>Members log in here:</td>
   <tr>
     <td>Username:</td>
     <td><input type=text name=username></td></tr>
   <tr>
     <td>Password:</td>
     <td><input type=password name=passwd></td></tr>
   <tr>
     <td colspan=2 align=center>
     <input type=submit value="Log in"></td></tr>
   <tr>
     <td colspan=2><!-- <a href="forgot_form.php">Forgot your password?</a> --></td>
   </tr>
 </table></form>
<?
}

function display_registration_form()
{
?>
 <form method=post action="register_new.php">
 <table width="100%">
   <tr>
     <td>Email address:</td>
     <td><input type=text name=email size=30 maxlength=100></td></tr>
   <tr>
     <td>Preferred username (max 16 chars):</td>
     <td valign=top><input type=text name=username size=16 maxlength=16></td></tr>
   <tr>
     <td>Password (between 6 and 16 chars):</td>
     <td valign=top><input type=password name=passwd size=16 maxlength=16></td></tr>
   <tr>
     <td>Confirm password:</td>
     <td><input type=password name=passwd2 size=16 maxlength=16></td></tr>
   <tr>
   <tr>
     <td>I want admin newsletter/messages delivered:</td>
     <td>
     <input type="radio" name="prefs" value='local' checked>Deliver it locally<br />
     <input type="radio" name="prefs" value='email'>Deliver it to emailaddress<br />
     
     </td></tr>
   <tr>
   
     <td colspan=2 align=left>
     <input type=submit value="Register"></td></tr>
 </table></form>
<? 

}

function member_menu()
{

?>
<table border=0 width="90%" cellpadding=0 cellspacing=0><tr><td align=left valign=top>
	<a href="member.php">My Page</a> | <a href="mailbox.php">Mail Box</a> | 	<a href="place.php">Category</a> | <a href="search.php">Search</a> |	<a href="match.php">Match List</a> | 	<a href="favorites.php">Favorites</a> | 	<a href="visitors.php">Visitors</a>  | <a href="profile.php">My profile</a>
| 	<a href="profile_lo.php">Wish-partner</a>  | <a href="help.php">Userguide</a> | <a href="logout.php">Logout</a><br></td></tr></table>


<?
}
?>
