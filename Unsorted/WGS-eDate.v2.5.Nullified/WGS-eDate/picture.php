<?php
include "member.php";
$db = c();

if (!$pid) exit;

$r=q("select picture, details from pictures where id='$pid'");
if (!e($r))
{
	$m=f($r);
    echo "<br><center><IMG src='".piurl($m[picture])."' border=1 alt=\"$m[details]\"></center><br>";
};


if ($send)
{
echo "<br><center>";


$mem=f($r);
q("delete from event where user_id='$pid' and sender='$auth'");
q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$auth', '$subject', '$message', 'picreview', '$pid', '$rating', '1','".strtotime(date("d M Y H:i:s"))."')");
echo "Review posted or replaced !";

$message="";
$subject="";

echo "<br></center>";
}
?> 

<?
$nr1=f(q("select count(id) as e from event where status>0 and type='picreview' and user_id='$pid'"));

echo "<table border=0 cellspacing=1 cellpadding=1 width=80% bgcolor=AAAAAA align=center>";
echo "<tr><td bgcolor='$color_head'><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp;<b>Comments and reviews</B></td></tr></table></td></tr>";
if ($nr1[e])
{
	  echo "<tr><td><table border=0 cellspacing=0 cellpadding=0 width=100%>";
	  $r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where type='picreview' and user_id='$pid' ORDER BY rdate DESC");
			while  ($ev = f($r)){
			
			$r1= q("select * from members where id='$ev[sender]'");
			if (!e($r1)) $mem=f($r1);

			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td align=left>&nbsp; ";
          	if ($mem[login]) $t1.=" From : $mem[login] ($ev[credits])";
			$t1.="</td><td align=center><b>$ev[title]</b></td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></tr></table>";
			echo "<tr bgcolor='#F0F0F0'><td bgcolor='#F0F0F0'>$t1</td></tr>";
			echo "<tr bgcolor='#FFFFFF'><td align=justify><br><blockquote>".nl2br(Htmlspecialchars($ev[contents]))."</blockquote><br></td></tr>";
			};
	echo "</td></tr></table></td></tr>";

} else echo "<tr><td><br><center> No comments found.</center> </td></tr>";
echo "</table><br>";
?>

<form name="form1" method="post" action="picture.php">
  <br>
  <table width="600" border="0" cellpadding="1" cellspacing="1" align="center">
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD colspan="2" bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Rate 
        picture &gt;&gt;</font></strong></TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Rate</TD>
      <TD><select name="rating" id="rating">
          <option value="0" selected>No rating.</option>
          <option value="-2">-2 Remove it, now !</option>
          <option value="-1">-1 Bad !</option>
          <option value="1">+1 It's ok.</option>
          <option value="2">+2 This is good !</option>
          <option value="3">+3 Excellent !</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Subject</TD>
      <TD><INPUT SIZE=60 NAME='subject' VALUE='<?php echo $subject; ?>'></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD><p>Comments</p>
        </TD>
      <TD><TEXTAREA NAME='message' cols=60 rows='4' wrap='PHYSICAL' id="message"><?php echo $message; ?></TEXTAREA></TD>
    </TR>
    <tr bgcolor="#f0f0f0"> 
      <td colspan="2"><div align="center"> 
          <input name="send" type="hidden" id="send" value="1">
		  <input name="pid" type="hidden" id="pid" value="<?php echo $pid; ?>">
          <input type="reset" name="Reset" value="Reset">
          <input type="submit" name="Submit2" value="Send">
        </div></td>
    </tr>
  </table>
  <br>
</form>

<?
include "_footer.php";
?>

