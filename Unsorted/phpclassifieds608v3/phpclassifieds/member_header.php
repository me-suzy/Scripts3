<?
session_start();
include_once("functions.php");


if (!session_is_registered("admin"))
{
	
	check_valid_user();
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">

<table>
<tr>
<td valign="top" width="200" height="100%">

  <table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#A9B8D1">
    <tr>
      <td width="100%" bgcolor="#A9B8D1">
        <table border="0" width="100%" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100%"><b><? echo $la_member_area ?>
        </b>
            </td>
          </tr>
        </table>
        <table border="0" width="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100%">
              <table border="0" width="100%" cellspacing="0" cellpadding="5">
                <tr>
                  <td align="left" nowrap><a href = "member.php"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><? echo $la_admin_frontpage
                    ?></a></td>
                </tr>
                <tr>
                  <td align="left" nowrap><a href = "add_ad_cat.php"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><? echo $la_new_ad
                    ?></a></td>
                </tr>
                <tr>
                  <td align="left" nowrap><a href = "ads.php"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><? echo $la_edit_ad
                    ?></a></td>
                </tr>
                <tr>
                  <td align="left" nowrap><a href = "change.php"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><? echo $change_user
                    ?></a></td>
                </tr>
                <tr>
                  <td align="left" nowrap><a href = "pass.php"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><? echo $la_change_pass
                    ?></a></td>
                </tr>
                <tr>
                  <td align="left" nowrap><a href = "member_login.php?logout=1"><img border="0" src="images/pointer.gif" border="0" width="7" height="8"><?
                    echo $la_log_out ?></a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>

</td>
    </tr>
  </table>

  <!-- Table menu -->

<!-- END Table menu -->
</td>
<td valign="top" width="10" height="100%">

  <img border="0" src="http://www.wirpoint.ch/images/spacerbig.gif" width="5" height="5">
</td>
<td align="left" valign="top" width="100%">
<!-- // Member header -->

  <table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#A9B8D1">
    <tr>
      <td width="100%" bgcolor="#A9B8D1">
        <table border="0" width="100%" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100%"><b><? print "$la_logged_in: $valid_user"; ?></b></td>
          </tr>
        </table>
        <table border="0" width="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100%">





