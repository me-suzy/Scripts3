<?php
  require("admin.php");
  require("../settings.php");
  require("../lib/mysql.lib");
  require("_header.php");

$db = c();

if ($confirm&&$eid)
{
q("update event set status=2 where id='$eid'");
$md=f(q("select credits, user_id from event where id='$eid'"));
q("UPDATE profiles set type='$md[credits]' where id='$md[user_id]'");
echo "<p align=center><br> Transaction processed. Member has been promoted to rank $md[credits].<br></p>";
$cl="";
};

if ($delete&&$eid)
{
q("delete from event where id='$eid'");
$eid=0;
};

$nr1=f(q("select count(id) as e from event where status=1 and (type='withdraw' or type='payment') "));
echo "<table border=0 cellspacing=1 cellpadding=2 width=80% bgcolor=AAAAAA align=center>";
echo "<tr><td bgcolor=CCDCFC><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp;<b>TOTAL ITEMS TO PROCESS : $nr1[e]</B></td>";
if ($nr1[e])
{
if ($eid)
{
	  echo "<td align=right><b>&nbsp;&nbsp; [<a href=process.php?eid=$eid&delete=1> DELETE </a>] &nbsp;[<a href=process.php?eid=$eid&confirm=1> CONFIRM </a>] &nbsp; [<a href=process.php> view all </a>]</b></td></tr></table>";
	  echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
	  $r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where status=1 and (type='withdraw' or type='payment') and id='$eid' ORDER BY rdate DESC");
                 while  ($ev = f($r)){
			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><td>";
			if ($ev[credits]) $t1.= " ($ev[credits]) &nbsp; ";
			if ($ev[type]=="payment" && $ev[status]==1) $t1.=" Payment : Pending. ";
			if ($ev[type]=="payment" && $ev[status]==2) $t1.=" Payment : Processed. ";
			$t1.="</td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></table>";
			echo "<tr><td bgcolor=E0E0E0>$t1</td></tr>";
			echo "<tr><td bgcolor=F0F0F0 align=center><b>$ev[title]</b></td></tr>";
			echo "<tr><td bgcolor=FFFFFF>$ev[contents]</td></tr>";
			};
	echo "</td></tr></table>";
	}else
	{
	  echo "<td align=right><b>&nbsp;&nbsp; </b></td></tr></table>";
	  echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
	  $r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where status=1 and (type='withdraw' or type='payment') $c1 ORDER BY rdate DESC");
                 while  ($ev = f($r)){
			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><td>";
			if ($ev[credits]) $t1.= "<a href=process.php?eid=$ev[id]> (details) </a> &nbsp; ($ev[credits]) &nbsp; ";
			if ($ev[type]=="payment" && $ev[status]==1) $t1.=" Payment : Pending. ";
			if ($ev[type]=="payment" && $ev[status]==2) $t1.=" Payment : Processed. ";
			$t1.="</td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></table>";
			echo "<tr><td bgcolor=E0E0E0>$t1</td></tr>";
			echo "<tr><td bgcolor=F0F0F0 align=center><b>$ev[title]</b></td></tr>";
			};
	echo "</td></tr></table>";
	}
}else echo "</td></tr>";
echo "</table><br>";
include "_footer.php";
?>

