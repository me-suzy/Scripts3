<?php
require "settings.php";
require "lib/mysql.lib";

$db = c(); 
$r = q("select status, rdate, login from members where id='$auth' and pswd='$pass'");
if (e($r)) {header("Location: login.php");exit;};

$mem=f($r);
q("update profiles set ldate='".strtotime(date("d M Y H:i:s"))."' where id='$auth'");

if (!$channel) $channel="NeoDate Main Area";

$input=str_replace ("\n","", $input);
$input=str_replace ("\r","", $input);
//$input = stripslashes ($input); 
if ($input) q("INSERT INTO `chatmessages` (`member` , `channel` , `target` , `line` , `rdate` ) VALUES ('$auth', '$channel', '$target', '$input', '".strtotime(date("d M Y H:i:s"))."');");

if (!$onlinetimeout) $onlinetimeout=60*3;
if (!$chatmessagetimeout) $chatmessagetimeout=60*1;
$logt= time()-$onlinetimeout;
$logt2= time()-$chatmessagetimeout;

q("delete from chatmessages where rdate < $logt2");

$users="";
$r1=q("select me.login as login, me.id from chatmessages cm, members me, profiles pr where cm.member=me.id and pr.id=me.id and cm.channel='$channel' and (pr.ldate > $logt) group by me.id");
if (!e($r1)) while ($me=f($r1)) $users.="<a href=\"mem.php?mid=$me[id]\" target=\"ntm3kmember\">$me[login]</a><br>";

$output="";
$r2=q("select me.login, me.id, cm.line, cm.target from chatmessages cm, members me where cm.member=me.id and cm.channel='$channel' group by cm.id order by cm.rdate desc limit 0,50");
if (!e($r2)) while ($me=f($r2)) 
{
if ($me[id]==$auth) $col="#336699";else $col="#339966";
$output.="<font color=\"$col\">$me[login]</font> : $me[line]<br>";
};

?>
&input=
&username=<?php echo urlencode($mem[login]);?>
&channel=<?php echo urlencode($channel);?>
&users=<?php echo urlencode($users);?>
&output=<?php echo urlencode($output);?>