<table width="200" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<!-- Start Menus   -->
<?php
$menubox = "<br>";
$menubox2= "&nbsp;&nbsp;&nbsp;";
?>
<font size="2" face="arial">
<font size="2"><b><a href="index.php">Main Controls</a></b></font>
<br>

<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Your TGP Index</b></font><br>
<?=$menubox2?><a href="../index.php" target="_blank">TGP index</a><br>

<?php if($access[cansettings]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Site Details</b></font><br>
<?=$menubox2?><a href="options.php">TGP Settings</a><br>
<?php
}
if($access[canadmin]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Admin Editors</b></font><br>
<?=$menubox2?><a href="editadmin.php?actions=addadmin">Add Admin</a><br>
<?=$menubox2?><a href="editadmin.php">Edit Admins</a><br>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Admin Groups</b></font><br>
<?=$menubox2?><a href="editadmin.php?action=groups">Edit Groups</a><br>
<?=$menubox2?><a href="editadmin.php?action=addgroup">Add Groups</a><br>
<?php
}
if($access[canbanwords]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Banned Words</b></font><br>
<?=$menubox2?><a href="banned.php">View Words</a><br>
<?=$menubox2?><a href="banned.php?add=banned">Add Word</a><br>
<?php
}
if($access[cancategory]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Categories</b></font> <br>
<?=$menubox2?><a href="addcategory.php">Add</a><br>
<?=$menubox2?><a href="categories.php">View/Edit/Delete</a><br>
<?php
}
if($access[cantemplates]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Templates</b></font> <br>
<?=$menubox2?><a href="templates.php">View/Edit</a><br>
<?php
}
if($access[cangalleries]=="1"){ ?>
<?=$menubox?><font face="arial" color="<?=$adminmenutext1?>" size="2"><b>Galleries</b></font><br>
<?=$menubox2?><a href="links.php?actions=validate">Validate Links</a><br>
<?=$menubox2?><a href="links.php?actions=addlink">Add</a><br>
<?=$menubox2?><a href="links.php?actions=links">Search</a><br>
<?=$menubox2?><a href="links.php?actions=top20">Top 20 galleries</a><br>
<?php
}
if($access[canstats]=="1"){ ?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><b>Site Statistics</b></font><br> 
<?=$menubox2?><a href="siteref.php?actions=count">View last 30 days</a><br>
<?=$menubox2?><a href="siteref.php?actions=ref">Refferences</a><br>
<?php
}
?>
<?=$menubox?><font face="arial" size="2" color="<?=$adminmenutext1?>"><a href='logout.php'><b>Logout</b></a></font> 
</font>
<!-- End Menus  -->
<br><br><br>
<a href="http://www.sextraffic.net"><img src="images/poweredby.jpg" border="0" ALT="Powered By SexTraffic TGP"></a>
<div align="center"><font face="arial" size="2"><?=$stversion?><br>
<a href="mailto:support@sextraffic.net"><b>Email Support</b></a></font></div>
</td>
  </tr>
</table>