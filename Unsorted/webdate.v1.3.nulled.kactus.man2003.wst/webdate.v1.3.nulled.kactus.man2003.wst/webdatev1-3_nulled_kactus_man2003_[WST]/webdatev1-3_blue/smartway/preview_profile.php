<?
	header("Pragma: no-cache");

	require "lib/system.lib";
	require "lib/plug-ins.lib";
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";

	require "services/BD3LoadConfiguration.service";
	require "services/BD3LoadDBDevice.service";

	require "services/BD3Auth.service";
    session_start();
    
	$db = c();

    $profile = f(q("SELECT id, member_id FROM dt_profile WHERE id='$id'"));

	if($profile == "")
	{
		echo "<h3>Error: Nothing to preview</h3>";
		d($db);
		exit;
	}
?>
<html>
<head>
<title>Preview profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" href="style.css" type="text/css">
 <script language="JavaScript">

	function prevSlide() {
		if(document.images) {
			currentslide--;
			if(currentslide<1) currentslide=maxslides;
			document.images['slide'].src=slide[currentslide].src;
		}
	}
	function nextSlide() {
		if(document.images) {
			currentslide++;
			if(currentslide>maxslides) currentslide=1;
			document.images['slide'].src=slide[currentslide].src;
		}
	}

</script></head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="4" topmargin="4" marginwidth="4" marginheight="4">

<span class=big>Preview profile</span><hr noshade size=1><br><br>
<?
    $esc = $root_host;
	include "../engine/preview_profile.pml";
    include "../templates/preview_profile_wp.ihtml";
?>
<br>
<font class=small>
<hr noshade size=1>
<b>E-mail address: </b><? echo $profile[ email ] ?>
<br>
<div align=right>
<a href=#top>Go to top</a>
</div>
<br>
</font>
</body>
</html>
<?
	d($db);
?>
