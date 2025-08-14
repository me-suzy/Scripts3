<?php
if (!$lnr_pn) $lnr_pn=5;
$r=q("select id, picture, details, member from pictures where type<>'Private' order by rdate desc LIMIT 0,$lnr_pn");

if (!e($r)) while ($m=f($r))
{
 $mem=f(q("select * from members where id='$m[member]'"));
 $pr=f(q("select (YEAR(CURRENT_DATE)-YEAR(birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(birthdate,5)) as age from profiles where id='$m[member]'"));
 echo "<TABLE bgcolor=#E0E0E0 width=120 BORDER=0 CELLPADDING=1 CELLSPACING=1 align=center valign=top>";
 echo "<TR><TD BGCOLOR=#F0F0F0 width=50><a href='picture.php?pid=$m[id]'> <IMG src='".piurl($m[picture])."'  width=50 border=1 alt=\"$m[details]\"></a></TD><TD BGCOLOR=#FAFAFA><a href='mem.php?mid=$mem[id]'>$mem[login]</A><br>$mem[fname]<br>$pr[age]</TD></TR>";
 echo "</TABLE><br>";
};

?>