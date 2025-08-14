<?php

$r=q("select me.login, me.id, me.fname, me.lname, me.city, me.country, me.state, (YEAR(CURRENT_DATE)-YEAR(p.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(p.birthdate,5)) as age, pi.id as pid, p.details as details, pi.picture, pi.details as pdetails from members me, profiles p, pictures pi where p.id=me.id and pi.member=me.id and pi.type='Main' and p.sex='Male' order by me.rdate desc LIMIT 0,1");
if (!e($r))
{ 
    $m=f($r);
    echo "<TABLE width=90% BORDER=0 CELLPADDING=1 CELLSPACING=1 align=center valign=top>";
	echo "<tr BGCOLOR=#F0F0F0><TD width=100><a href='picture.php?pid=$m[pid]'><IMG src='".piurl($m[picture])."'  width=100 border=1 alt=\"$m[pdetails]\"></a></TD><td><a href=mem.php?mid=$m[id]>$m[login]</a> <br> $m[fname] $m[lname] ($m[age])<br> $m[country] $m[state] $m[city]</td></tr>";
	echo "<tr><td colspan=2>$m[details]</td></tr>";
    echo "</TABLE>";
}
?>
<blockquote><A HREF=search.php?search=3&sex=male>MORE MALES...</A></blockquote>