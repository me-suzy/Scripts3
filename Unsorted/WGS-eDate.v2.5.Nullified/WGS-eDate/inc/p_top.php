<?php
if (!$lnr_pt) $lnr_pt=3;

$r=q("select pic.id as id, pic.picture as picture, pic.id as pid, pic.details as details, pic.member as member, sum(ev.credits) as points from event ev, pictures pic where pic.type<>'Private' and ev.type='picreview' and ev.user_id=pic.id group by pic.id order by points desc limit 0, $lnr_pt");

if (!e($r)) 
while ($m=f($r))
{
 $mem=f(q("select * from members where id='$m[member]'"));
 $pr=f(q("select (YEAR(CURRENT_DATE)-YEAR(birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(birthdate,5)) as age from profiles where id='$m[member]'"));
 echo "<TABLE bgcolor=#E0E0E0 width=120 BORDER=0 CELLPADDING=1 CELLSPACING=1 align=center valign=top>";
 echo "<TR><TD BGCOLOR=#F0F0F0 width=50><a href='picture.php?pid=$m[pid]'> <IMG src='".piurl($m[picture])."'  width=50 border=1 alt=\"$m[details]\"></a></TD><TD BGCOLOR=#FAFAFA>$m[points] Points. <br> <a href='mem.php?mid=$mem[id]'>$mem[login]</A><br>$mem[fname]<br>$pr[age]</TD></TR>";
 echo "</TABLE><br>";
};

?>