<?
$old_user = $valid_user; //test if user *were* logged in
$result = session_unregister("valid_user");
session_destroy();
header("Location: index.php");
?>

<?
if (!empty($old_user))
{
if ($result)
{
// if user was logged in and are not logged out
echo 'You are now logged out.';
echo '<table align="right"><tr><td><a href="index.php?log=">Log in here.</a></td></tr></table>';
}
else
{
// user was logged in and could not be logged out
echo 'Could not log you out.';
}
}
else
{
// not logged in and accessed this page
echo 'You were not logged in.';
echo '<table align="right"><tr><td><a href="index.php?log=">Log in here.</a></td></tr></table>';
}
?>