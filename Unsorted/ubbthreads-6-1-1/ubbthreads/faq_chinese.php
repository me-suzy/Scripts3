<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/


// Require the library
   require ("main.inc.php");

// ---------------------
// Send the page to them
  $html = new html; 
  $html -> send_header("³£¼ûÎÊÌâ",$Cat,0,$user);
  $html -> table_header("³£¼ûÎÊÌâ");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  ÒÔÏÂÎªÊ¹ÓÃÌÖÂÛÇøÊ±µÄ³£¼ûÎÊÌâ¡£ÈçÓÐÆäËûÎÊÌâÒàÇë¸æÖª£¬ÒÔ±ãÕûÀí¹éÄÉ£»ÆäËûÎÊÌâ£º<a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>¡£
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\"></a>ÎÒ¿ÉÒÔÔÚÌù×ÓÉÏ¸½¼ÓÎÄ¼þÂð£¿<br />
  <a href=\"#html\">¿É·ñÔÚÎÄÕÂÖÐÊ¹ÓÃ HTML Âë»òÌØÊâ·ûºÅ£¿</a><br />
  <a href=\"#cookies\">ÊÇ·ñ±ØÐë½ÓÊÜcookies£¿</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Ã¿Ò³ÏÔÊ¾µÄÎÄÕÂÊý¿É·ñÉè¶¨£¿</a><br />
  <a href=\"#buttons\">ÌÖÂÛÇøÉÏµÄ°´Å¥ÓÐÊ²Ã´×÷ÓÃ£¿</a><br />
  <a href=\"#sortorder\">ÎªºÎÖ÷Ìâ¡¢¼ÓÌùÕßºÍ¼ÓÌùÊ±¼ä¶¼ÊÇ¿Éµã»÷µÄ£¿</a><br />
  <a href=\"#email\">ÎªºÎÒªÊäÈëÁ½×éµç×ÓÓÊ¼þÐÅÏä£¿</a><br />
  <a href=\"#register\">ÎªºÎÐèÒª×¢²áÓÃ»§Ãû£¿</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\"> 
  <tr><td>
  <a name=\"attach\"><h3>ÎÒ¿ÉÒÔÔÚÌù×ÓÉÏ¸½¼ÓÎÄ¼þÂð£¿</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Ê¹ÓÃ Mozilla 4+ ÏàÈÝµÄä¯ÀÀÆ÷¼´¿É¸½¼Óµµ°¸£¬ÔÚÕÅÌùÎÄÕÂÊ±½«»á³öÏÖ´ËÒ»Ñ¡Ïî¡£
    ";
  }
  else {
    echo "±§Ç¸£¬¸½¼Óµµ°¸µÄ¹¦ÄÜÒÑ¾­¹Ø±Õ¡£";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>¿É·ñÔÚÎÄÕÂÖÐÊ¹ÓÃ HTML Âë»òÌØÊâ·ûºÅ£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ½øÈë¸÷ÌÖÂÛÇøºó£¬¼´¿ÉµÃÖª¸ÃÌÖÂÛÇøÊÇ·ñÔÊÐí HTML Âë»òÌØÊâ·ûºÅµÄÎÄÕÂÕÅÌù¡£ÒÔÏÂÎªÊ¹ÓÃÕß¿ÉÊ¹ÓÃµÄ HTML Âë¼°ÌØÊâ·ûºÅ£º
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  text
  <font class=standouttext>
  [/b]
  </font>
         = ÎÄ×Ö¼Ó´Ö<br />

  <font class=standouttext>
  [µç×ÓÓÊ¼þ]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/µç×ÓÓÊ¼þ] 
  </font>
  = ½«µç×ÓÓÊ¼þÐÅÏäÉèÎª¿ÉµãÑ¡µÄÁ´½á<br />

  <font class=standouttext>
  [i]
  </font>
  text
  <font class=standouttext>
  [/i]
  </font>
         = ÎÄ×ÖÇãÐ±<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[Í¼Æ¬]</font>";
    echo"url";
    echo "<font class=standouttext>";
    echo "[/Í¼Æ¬]</font>  = ÏÔÊ¾Ä³ÍøÖ·ËùÖ¸¶¨µÄÍ¼Æ¬<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  text
  <font class=standouttext>
  [/pre]
  </font>
   = ½«ÌØ¶¨ÎÄ×ÖÒÔÔ­ÊäÈëµÄ¸ñÊ½ÏÔÊ¾£¬²»ÒªÒÀÕÕ°æÃæÖØÐÂÅÅÁÐ<br />

  <font class=standouttext>
  [ÒýÊö]
  </font>
  text
  <font class=standouttext>
  [/ÒýÊö] 
  </font>
  = ½«ÌØ¶¨¶ÎÂäÉÏÏÂ¸÷¼ÓÈëÒ»Ìõ·Ö¼ÊÏß£¬²¢×¢Ã÷ÎªÔ­ÎÄÄÚÈÝ<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = ½«Ä³ÍøÖ·ÉèÎª¿ÉµãÑ¡µÄÁ´½á<br />


  <font class=standouttext>
  [Á³ºì]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [×°¿á]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [·èµß]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [õ¾Ã¼]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [´óÐ¦]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [·¢¿ñ]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [Õð¾ª]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [Î¢Ð¦]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [ÍÂÉà]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [ÃÄÑÛ]
  </font> = <img src=\"{$config['images']}/graemlins/wink.gif\"><br />

  <font class=\"standouttext\">
  [color:red]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Makes the given text red.<br />

  <font class=\"standouttext\">
  [color:#00FF00]
  </font>
  text
  <font class=\"standouttext\">
  [/color]
  </font>
  = Makes the given text green.<br />


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"cookies\"><h3>ÊÇ·ñÒ»¶¨Òª½ÓÊÜ cookies µÄÐ´Èë£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ÊÇµÄ¡£ÏµÍ³ÀûÓÃ Cookies µÄÐ´Èë£¬¿ÉÔÚÊ¹ÓÃÕßµÄµçÄÔÉÏ¼ÇÂ¼Ê¹ÓÃÕßµÄÕÊºÅÓëÃÜÂë£¬Èô¾Ü¾øÐ´Èë£¬²¿·Ý¹¦ÄÜ¿ÉÄÜÎÞ·¨Õý³£ÔË×÷¡£

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">How do i put a poll in my post?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Putting a pull in your post is simple, but remember: Posts with polls  in them
 cannot be edited but they may be deleted.<br />
  ";
  if (!$config['allowpolls']) {
    echo "<i>Only admins and moderators may use this feature.</i><br />";
  }
  echo " 
  To add a poll to your post, use this format:<p>
  [pollstart]<br />
  [polltitle=Name of your poll]<br />
  [polloption=First Choice]<br />
  [polloption=Second Choice]<br />
  [polloption=As many choices as you would like]<br />
  [pollstop]


                

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"moreposts\"><h3>Ã¿Ò³ÏÔÊ¾µÄÎÄÕÂÊý¿É·ñÉè¶¨£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Äã¿ÉÒÔÔÚ¸öÈË×ÊÑ¶ÖÐÉè¶¨Ã¿Ò³ÏÔÊ¾µÄÎÄÕÂÊý£¬ÎÄÕÂÊý¿ÉÉè¶¨ÔÚ 1 ÖÁ 999 ÆªÖ®¼ä£¬Ô¤ÉèÖµÊÇ {$theme['postsperpage']} Æª¡£


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>ÌÖÂÛÇøÉÏµÄ°´Å¥ÓÐÊ²Ã´×÷ÓÃ£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  ÕâÐ©°´Å¥ÊÇÓÃÀ´ÓÎÀÀ¶ÁÌù×ÓµÄ£¬¸ù¾ÝËùÔÚÆÁÄ»£¬ËüÃÇ¿ÉÓÐ²»Í¬×÷ÓÃ¡£
  <p>µ±ÏÔÊ¾Ìù×ÓÁÐ±íÊ±£º
  <br />- ×óÓÒ¼ýÍ·ÈÃÄú×ª»»Ìù×ÓÁÐ±íÒ³¡£
  <br />- ÏòÉÏ¼ýÍ·´øÄúµ½ËùÓÐÌù×ÓÂÛÌ³Ä¿Â¼¡£
  <br />-  \"New Post\" °´Å¥ÈÃÄú¿ªÊ¼ÌùÐÂÌù¡£
  <br />- + ºÍ - °´Å¥ÈÃÄúÔÚÀ©Õ¹ÏÔÊ¾Ä£Ê½ºÍÑ¹ËõÏÔÊ¾Ä£Ê½Ö®¼ä×ª»»¡£À©Õ¹ÏÔÊ¾Ä£Ê½ÁÐ³öÔ­ÌùÖ÷Ìå¼°ËùÓÐ¸úÌùÖ÷Ìâ¡£  ºÍÑ¹ËõÏÔÊ¾Ä£Ê½ÁÐ³öÔ­ÌùÖ÷Ìå¼°¸úÌùÊýÄ¿¡£
  <p>µ±ÏÔÊ¾µ¥¸öÌù×ÓÊ±£º
  <br />- ×óÓÒ¼ýÍ·ÈÃÄú×ª»»²»Í¬²ã´ÎÌù×Ó¡£
  <br />- ÏòÉÏ¼ýÍ·´øÄúµ½´ËÒ³ËùÓÐÌù×ÓÄ¿Â¼¡£
  <br />-  \"Flat Mode\" °´Å¥ÈÃÄú°ÑÒ»¸öÖ÷ÌâµÄËùÓÐÌù×ÓÏÔÊ¾ÔÚÍ¬Ò»Ò³ÉÏ¡£
  <br />-  \"Threaded Mode\" °´Å¥ÈÃÄú°ÑÒ»¸öÖ÷ÌâµÄËùÓÐÌù×Ó°´²ã´ÎÏÔÊ¾¡£


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>ÎªºÎÖ÷Ìâ¡¢¼ÓÌùÕßºÍ¼ÓÌùÊ±¼ä¶¼ÊÇ¿Éµã»÷µÄ£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
    Äú¿ÉÒÔµã»÷ÕâÐ©À´¸Ä±äÌù×ÓÏÔÊ¾µÄÅÅÁÐ·½Ê½¡£ Èç¹ûÄúµã»÷Ö÷ÌâÒ»´Î£¬Ìù×Ó¾Í»á°´Ö÷Ìâ¿ªÍ·×Ö·´ÏòÅÅÁÐ¡£ÔÙµãÒ»´Î¾Í»áÕýÏòÅÅÁÐ¡£¼ÓÌùÕß¼°¼ÓÌùÊ±¼äÀàËÆ¡£



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\"> 
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>ÎªºÎÒªÊäÈëÁ½×éµç×ÓÓÊ¼þÐÅÏä£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
    ÕæµÄemailÊÇÓÃÀ´¸øÄú·¢Í¨Öª¼°ÃÜÂëµÈ¡£ÁíÒ»¸ö¡°¼ÙµÄ¡±emailÊÇ´ó¼Ò¿´ÄúµÄ¼ò½éÊ±ËùÄÜ¿´µ½µÄ¡£ ÎÒÃÇÈÏÊ¶µ½ÓÐÐ©ÓÃ»§²»ÏëÈÃ±ðÈË¿´µ½ËûµÄEMAILµØÖ·£¬µ«ÎÒÃÇÐèÒª¡°Õæ¡±EMAILµØÖ·À´¸øÄú¼ÄÃÜÂë¡¢Í¨Öª¡¢»ØÌû¡£ËùÒÔÄúÔÚ¼ÓÈëÊ±Òª¸øÎÒÃÇÌá¹©¡°Õæ¡±EMAILµØÖ·£¬µ«ÄúÏÔÊ¾¸øÆäËüÈËµÄµØÖ·¿ÉÒÔÊÇ¼ÙµÄ¡£ÓÐÐ©ÈËÏ²»¶ÓÃÏósportschina\@no.spam.yahoo.comÕâÑùµÄ¡°¼Ù¡±email¡£ ÕâÑù´ó¼Ò»¹¿ÉÒÔÍÆ¶Ï³öÄúµÄÕæÊµEMAIL£¬µ«
À¬»øÓÊ¼þµÄ×Ô¶¯EMAILÊÕ¼¯Æ÷¾ÍÕÒ²»µ½ÄúÁË¡£


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>ÎªºÎÐèÒª×¢²áÓÃ»§Ãû£¿</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
   ×¢²áÓÃ»§Ãûºó£¬Äú¿ÉÒÔ±à¼­¸öÈË¼ò½é¼°ÏÔÊ¾·½Ê½Éè¶¨À´ÊÊºÏÄú¸öÈËµÄ¿ÚÎ¶¡£ ÔÚ¸öÈË¼ò½éÓÐºÜ¶à¿ÉÑ¡Ïî¿ÉÊ¹ÄúÔÚÕâ¶ùµÄ¾­Àú¸üÓä¿ì£¬×¢²áºóÄú¿ÉÒÔ¿´µ½ÄúÉÏ´ÎÀ´¹ýºóÓÐ¶àÉÙÐÂÌù×Ó¡£ ¸Ï½ô»¯¼¸·ÖÖÓÊÔÊÔ²»Í¬µÄÓÐÈ¤ÉèÖÃ°É£¡

  </td></tr></table  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
