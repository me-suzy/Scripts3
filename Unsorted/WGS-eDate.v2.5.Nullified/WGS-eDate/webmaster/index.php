<?php
require("admin.php");
include("_header.php");
require "../lib/mysql.lib";

$color=#333333;

$db = c();

$r=q("select * from menus order by id asc");
while ($m=f($r))
{
if ($m[level]>=100) $pro="+";
 if ($m[link]) 
   echo "<li type=square><a href=\"".$pro.$m[link]."\"><font color='$color'class=adminlinks><font size=-1>$m[topic]</a></font></font></l; i>";
   else echo "<BR><BR> <b><font class=admintitle><font size=2>$m[topic]</font></font></b>";


}
include("_footer.php");
?>
