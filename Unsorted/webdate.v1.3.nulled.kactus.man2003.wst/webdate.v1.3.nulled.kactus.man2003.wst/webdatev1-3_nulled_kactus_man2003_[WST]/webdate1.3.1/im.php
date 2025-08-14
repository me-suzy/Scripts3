<?
require "engine/load_configuration.pml";
	$smiles_list = array(   ":-)",
							":)",
							";-)",
							";)",
							"B-)",
							"B)",
							":-(",
							":(",
							":-O",
							":O",
							":-P",
							":P",
							":-X",
							":X",
							":-D",
							":D",
							":shock:"
                                 );

	$slile_images_list = array(
							"<img src='images/icon_smile.gif' border=0>",
							"<img src='images/icon_smile.gif' border=0>",
							"<img src='images/icon_wink.gif' border=0>",
							"<img src='images/icon_wink.gif' border=0>",
							"<img src='images/icon_cool.gif' border=0>",
							"<img src='images/icon_cool.gif' border=0>",
							"<img src='images/icon_sad.gif' border=0>",
							"<img src='images/icon_sad.gif' border=0>",
							"<img src='images/icon_surprised.gif' border=0>",
							"<img src='images/icon_surprised.gif' border=0>",
							"<img src='images/icon_razz.gif' border=0>",
							"<img src='images/icon_razz.gif' border=0>",
							"<img src='images/icon_mad.gif' border=0>",
							"<img src='images/icon_mad.gif' border=0>",
							"<img src='images/icon_biggrin.gif' border=0>",
							"<img src='images/icon_biggrin.gif' border=0>",
							"<img src='images/icon_eek.gif' border=0>");
$esc_symbols_list = array(
								"/\\\\/",
								"/\\^/",
								"/\\\$/",
								"/\\./",
								"/\\[/",
								"/\\]/",
								"/\\|/",
								"/\\(/",
								"/\\)/",
								"/\\?/",
								"/\\*/",
								"/\\+/",
								"/\\{/",
								"/\\}/",
								"/\\//"
							);
$esc_repl_list = array(
								"\\\\",
								"\\\^",
								"\\\\$",
								"\\\.",
								"\\\[",
								"\\\]",
								"\\\|",
								"\\\(",
								"\\\)",
								"\\\?",
								"\\\*",
								"\\\+",
								"\\\{",
								"\\\}",
								"\\\/"
							);
session_start();
$db = c();
$id = $sAuth;
if ($action == "check" && $id != "")
{
   $rMessages = q("select id from dt_im_messages where rid=$id");
   if ((int)nr($rMessages) > 0)
   {
    header('Content-Type: image/jpeg');
    $im = imagecreate(2, 2);

    imagejpeg($im);
   }
   else
   {
    header('Content-Type: image/jpeg');
    $im = imagecreate(1, 1);

    imagejpeg($im);

   }
}
else if ($action == "send_im")
{
    if ($sAuth)
    {
        echo "<html><head><script language=javascript>window.close();</script></head></html>";
        $fBlocked = f(q("SELECT id FROM dt_blocked WHERE member_id = '$rid' and blocked_id = '$sAuth'"));
        if ($fBlocked[id] == "")
        {
           q("insert into dt_im_messages (rid, sid, message) values ($rid, $sAuth, '$message')");
        }
    }
}
else if ($action == "message")
{
    $fMember = f(q("select * from dt_members where id=".$rid));
    include "templates/im_send.ihtml";
}
else if ($action == "block_user")
{
    $f_blocked = f(q("SELECT id FROM dt_blocked WHERE member_id=$sAuth and blocked_id = '$blocked_id'"));
    if ($f_blocked["id"] == "")
    {
        q("insert into dt_blocked (member_id, blocked_id) values ($sAuth, '$blocked_id')");
    }
    echo "<html><head>";
    echo '<script language=javascript>alert("The user has been blocked!");</script><script language=javascript>window.close();</script></head></html>';
}
else if ($action == "unblock_user")
{
    echo "<html><head>";
    echo '<script language=javascript>alert("The user has been unblocked!");</script><script language=javascript>window.close();</script></head></html>';
}
else
{
   $rMessages = q("select * from dt_im_messages where rid=$id");
   $fMessage = f($rMessages);
   $sid = $fMessage["sid"];
   $fMember = f(q("select * from dt_members where id=".$fMessage["sid"]));
   $message = $fMessage["message"];
   $message = stripslashes($message);
   while(list($k, $v) = each ($smiles_list))
   {
      $smiles_list[$k] = preg_replace($esc_symbols_list, $esc_repl_list, $v);
	  $smiles_list[$k] = "/".$smiles_list[$k]."/";
   }
   $message = preg_replace($smiles_list, $slile_images_list, $message);

   $message_text .= "<b>".$fMember["login"]."</b>: ".$message."<br>";
   q("delete from dt_im_messages where id=$fMessage[id]");
   $rMessages = q("select * from dt_im_messages where rid=$id and sid=$sid");
   while ($fMessage = f($rMessages))
   {
         $message = $fMessage["message"];
         $message = stripslashes($message);
         $message = preg_replace($smiles_list, $slile_images_list, $message);
         $message_text .= "<b>".$fMember["login"]."</b>: ".$message."<br>";
         q("delete from dt_im_messages where id=$fMessage[id]");
   }
   //q("delete from dt_im_messages where rid=$id and sid=$sid");
   include("templates/im_box.ihtml");
}
 d($db);
