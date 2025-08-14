<?php
if (!$lnr_mn) $lnr_mn=20;
$r=q("select me.login, me.id, me.fname, me.lname, me.city, me.country, me.state, (YEAR(CURRENT_DATE)-YEAR(p.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(p.birthdate,5)) as age from members me, profiles p where p.id=me.id order by me.rdate desc LIMIT 0,$lnr_mn");
if (!e($r))
{ 
    echo "<TABLE width=90% BORDER=0 CELLPADDING=0 CELLSPACING=0 align=center valign=top>";
	echo "<tr><td><b>Nick</b></td><td><b>Name</b></td><td><b>Location</b></td><td><b>Age</b></td></tr>";
	while ($m=f($r))
	{
	echo "<tr><td><a href=mem.php?mid=$m[id]>$m[login]</a></td><td>$m[fname] $m[lname]</td><td>$m[country]</td><td>$m[age]</td></tr>";
	};
echo "</TABLE>";
}
?>
<br><blockquote><A HREF=search.php?search=3>BROWSE...</A></blocquote>