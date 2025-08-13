<?
session_start();

if ($setlang)
{
	$la = $setlang;
	session_register("la");	
}

$fp = 1;
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");

print "<!-- ## INDEX.PHP START ## -->";

if (!$special_mode)
{
	include("navigation.php");
//        print("<p /> $menu_ordinary ");
}

require ("link_title.php");

print '<table border="0" width="100%" cellspacing="0" cellpadding="10"><tr><td width="100%">';	     

if (!$kid AND !$special_mode)
{

?>
   
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">     
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>
          
<?

	echo "$welcome_message";

?>

</td>
    <td valign="middle" align="right">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img border="0" src="images/spacerbig.gif" width="25" height="10"></td>
          <td><a href="index.php?setlang=eng"><img border="0" src="images/flag_english.gif" hspace="2" alt="[ English ]" width="26" height="15" /></a></td>
          <td><a href="index.php?setlang=fre"><img border="0" src="images/flag_french.gif" hspace="2" alt="[ Française ]" width="25" height="15" /></a></td>
          <td><a href="index.php?setlang=ger"><img border="0" src="images/flag_german.gif" hspace="2" alt="[ Deutsch ]" width="26" height="15" /></a></td>
          <td><a href="index.php?setlang=ita"><img border="0" src="images/flag_italian.gif" hspace="2" alt="[ Italiano ]" width="26" height="15" /></a></td>
          <td><a href="index.php?setlang=nor"><img border="0" src="images/flag_norwegian.gif" hspace="2" alt="[ Norwegian ]" width="26" height="15" /></a></td>
          <td><a href="index.php?setlang=swe"><img border="0" src="images/flag_swedish.gif" hspace="2" alt="[ Swedish ]" width="25" height="15" /></a></td>
        </tr>
      </table>
</td>
  </tr>
</table>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>

<?
 
}

include("catcol.php");
$frontpage=1;


include("links.php");

if (!$kid AND $latest)
{

     require ("latest.php");
     
}

if ($auto)
{
 include_once("update.php");
}


include_once("admin/config/counter.inc.php");
$la_s_bar = ereg_replace("\{users\}", "$users", $la_s_bar);
$la_s_bar = ereg_replace("\{ads\}", "$ads", $la_s_bar);
$la_s_bar = ereg_replace("\{detailed\}", "$detailed", $la_s_bar);
$show_bar = 1;
print "</td></tr></table>\n";
print "<!-- ## INDEX.PHP END ## -->";


include_once("admin/config/footer.inc.php");

?>
