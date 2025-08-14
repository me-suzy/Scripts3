<?php
if (!$onlinetimeout) $onlinetimeout=60*3;
$sql_period = "ldate >=".(time()-$onlinetimeout);

if (!$lnr_mo) $lnr_mo=23;

$r=q("select me.login, me.id, me.fname, me.lname, me.city, me.country, me.state, (YEAR(CURRENT_DATE)-YEAR(pr.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(pr.birthdate,5)) as age from members me, profiles pr where me.id=pr.id and $sql_period order by pr.ldate desc LIMIT 0,$lnr_mo");

if (!e($r))
   { 
    echo "<TABLE width=90% BORDER=0 CELLPADDING=0 CELLSPACING=0 align=center valign=top>";
	echo "<tr><td><b>Nick</b></td><td><b>Name</b></td><td><b>Location</b></td><td><b>Age</b></td></tr>";
	while ($m=f($r))
	{
	echo "<tr><td><a href=mem.php?mid=$m[id]>$m[login]</a></td><td>$m[fname] $m[lname]</td><td>$m[country]</td><td>$m[age]</td></tr>";
	};
echo "</TABLE>";
  };

?>