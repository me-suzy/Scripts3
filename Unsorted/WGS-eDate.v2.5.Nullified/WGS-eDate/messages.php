<?php
include "member.php";
$db = c();
if (!$tm0message) exit;
if ($send)
{
echo "<br><center>";
$r=q("select * from members where login='$to'");
if (e($r)) echo "Error : Member '$to' not found ! Please check the nickname !";
else 
{
$mem=f($r);
q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$auth', '$subject', '$message', 'message', '$mem[id]', '0', '1','".strtotime(date("d M Y H:i:s"))."')");
echo "Message sent to '$to' !";
$to="";
$message="";
$subject="";
};
echo "<br></center>";
}
?>

<form name="form1" method="post" action="messages.php">
  <table width="600" border="0" cellpadding="1" cellspacing="1" align="center">
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD colspan="2" bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Send 
        message &gt;&gt;</font></strong></TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>To</TD>
      <TD><input name="to" type="text" id="to" size="30" value="<?php echo $to; ?>"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Subject</TD>
      <TD><INPUT SIZE=60 NAME='subject' VALUE='<?php echo $subject; ?>'></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD><p>Message</p>
        </TD>
      <TD><TEXTAREA NAME='message' cols=60 rows='4' wrap='PHYSICAL' id="message"><?php echo $message; ?></TEXTAREA></TD>
    </TR>
    <tr bgcolor="#f0f0f0"> 
      <td colspan="2"><div align="center"> 
          <input name="send" type="hidden" id="send" value="1">
          <input type="reset" name="Reset" value="Reset">
          <input type="submit" name="Submit2" value="Send">
        </div></td>
    </tr>
  </table>
</form>

<?
if ($eid&&$delete) {q("DELETE from event where id='$eid'"); $eid="";};

$nr1=f(q("select count(id) as e from event where status>0 and type='message' and user_id='$auth'"));

echo "<table border=0 cellspacing=1 cellpadding=2 width=80% bgcolor=AAAAAA align=center>";
echo "<tr><td bgcolor='$color_head'><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp;<b>INBOX ($nr1[e])</B></td>";
if ($nr1[e])
{
if ($eid)
{
$r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where type='message' and user_id='$auth' and id='$eid'");
$ev = f($r);

$r1= q("select * from members where id='$ev[sender]'");
if (!e($r1)) $mem=f($r1);

	  echo "<td align=right><b>&nbsp;&nbsp; [<a href='messages.php?eid=$eid&reply=1&to=$mem[login]&subject=Re: $ev[title]'> reply </a>] &nbsp; [<a href='messages.php?eid=$eid&delete=1'> delete </a>] &nbsp; [<a href=messages.php> view all </a>]</b></td></tr></table>";
	  echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
  

			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><td>";
			if ($ev[type]=="message" && $ev[status]==1) $t1.=" New ! ";
			if ($ev[type]=="message" && $ev[status]==2) $t1.=" ";
			$t1.="</td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></table>";
			echo "<tr><td bgcolor=E0E0E0>$t1</td></tr>";
			echo "<tr><td bgcolor=F0F0F0 align=center><b>From $mem[login] : $ev[title]</b></td></tr>";
			echo "<tr><td align=justify><br><blockquote>".nl2br(Htmlspecialchars($ev[contents]))."</blockquote><br></td></tr>";
		
	echo "</td></tr></table>";
	q("UPDATE event set status='2' where id='$eid'");
	}else
	{
	  echo "<td align=right><b> &nbsp;&nbsp; </b></td></tr></table>";
	  echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
	  $r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where type='message' and user_id='$auth' ORDER BY rdate DESC");
			while  ($ev = f($r)){
			
			$r1= q("select * from members where id='$ev[sender]'");
			if (!e($r1)) $mem=f($r1);

			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><td><a href=messages.php?eid=$ev[id]><b>&gt;</b></a> &nbsp; ";
          	if ($ev[type]=="message" && $ev[status]==1) $t1.=" New ! ";
			if ($mem[login]) $t1.=" From : $mem[login]";
			$t1.="</td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></table>";
			echo "<tr><td bgcolor=F0F0F0>$t1</td></tr>";
			echo "<tr><td bgcolor=FFFFFF align=center><b>$ev[title]</b></td></tr>";
			};
	echo "</td></tr></table>";
	};
}else echo "</td></tr></table> No messages found. </td></tr>";
echo "</table><br>";
include "_footer.php";
?>

