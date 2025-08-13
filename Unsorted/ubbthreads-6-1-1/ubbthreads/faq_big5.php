<?
/*
# UBB.threads,Version 6
# Official Release Date for UBB.threads Version6: December 12,2000.

# First version of UBB.threads created July 30,1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner,modified or otherwise,
# without the express,written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads¡Awe at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/


// Require the library
   require ("main.inc.php");
                                
// ---------------------
// Send the page to them
  $html = new html; 
  $html -> send_header("FAQ (±`¨£°ÝÃD¶°)",$Cat,0,$user);
  $html -> table_header("FAQ (±`¨£°ÝÃD¶°)");

  $phpurl = $config[phpurl];

  echo " 
  <table cellspacing=\"0\" border=\"0\" width=\"$theme[tablewidth]\" align=\"$theme[tablealign]\" class=\"darktable\">
  <tr><td>
  ¦b¤U­±±z¥i¥H¬Ý¨ì¤@¨Ç±`¨£ªº°ÝÃD. ¦pªG±z¦³Ãþ¦üªº°ÝÃD¡A±z¥i¥HÂI¿ï¨ä¤¤Æ[¬Ý¡C¦pªG±z¦³¨ä¥Lªº°ÝÃD¤£¦b¦¹­¶¤¤¡A½Ð±H«H¨ì <a href=\"mailto:$config[emailaddy]\">$config[emailaddy]</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">§Ú¥i¥H¬°§Úªº¤å³¹ªþ¥[¤@­ÓÀÉ®×¶Ü¡H</a><br />
  <a href=\"#html\">§Ú¥i¥H¦b§Úªº¤å³¹¤º¨Ï¥Î HTML ½X¶Ü¡H</a><br />
  <a href=\"#source\">§Ú¥i¥H¾Ö¦³¦P¼Ëªº¨t²Î¶Ü¡H</a><br />
  <a href=\"#cookies\">§ÚªºÂsÄý¾¹¤@©w­n±µ¨ü cookies ¶Ü¡H</a><br />
  <a href=\"#polls\">§Ú­n¦p¦ó¦b¤å³¹¤¤¿ì²zÅªªÌ§ë²¼¡H</a><br />
  <a href=\"#moreposts\">§Ú·Q­n¦b¨C­¶Åã¥Ü§ó¦h(©Î§ó¤Ö)ªº¤å³¹¡C</a><br />
  <a href=\"#buttons\">³o¨Ç«ö¶s¬O¤°»ò·N«ä©O¡H</a><br />
  <a href=\"#sortorder\">¬°¤°»ò ¼ÐÃD,±i¶KªÌ¥H¤Î±i¶K¤é´Á¥i¥H«ö©O¡H</a><br />
  <a href=\"#email\">¬°¤°»ò§A­n°Ý§Úªº Email ©O¡H</a><br />
  <a href=\"#register\">¬°¤°»ò§ÚÀ³¸Óµù¥U¤@­Ó±b¸¹¡H</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=\"0\" border=\"0\" width=\"$theme[tablewidth]\" align=\"$theme[tablealign]\" class=\"darktable\">
  <tr><td>
  <h3><a name=\"attach\">§Ú¥i¥H¬°§Úªº¤å³¹ªþ¥[¤@­ÓÀÉ®×¶Ü¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config[files]) {
    echo "
      ¦pªG±zªºÂsÄý¾¹¬Û®e©ó Mozilla 4+ ¨º»ò±z´N¥i¥H¡C·í±z¹wÄý±zªº¤å³¹®É±z´N¥i¥H¬°±zªº¤å³¹ªþ¥[¤@­ÓÀÉ®×¡C
    ";
  }
  else {
    echo "¤£¦æ¡A±z¤£¯à¦b¦¹ªþ¥[ÀÉ®×¡C";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\">§Ú¥i¥H¦b§Úªº¤å³¹¤º¨Ï¥Î HTML ½X¶Ü¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ¨C­Ó°Q½×°Ï³£¦³¨âºØ³]©w¤èªk¡C¦pªG HTML ³Q¶}±Ò¡A¨º»ò±z·|¬Ý¨ì<b>¤¹³\\¨Ï¥Î HTML</b>¡A±z´N¥i¥H¦b±zªº¤å³¹¤º¨Ï¥Î HTML¡C ¦pªG Markup ³Q¶}±Ò¡A¨º»ò±z·|¬Ý¨ì<b>¤¹³\\¨Ï¥Î Markup</b>¡C ¥H¤Uªº¼ÐÅÒ¬O¥i¥H¨Ï¥Îªº Markup ¼ÐÅÒ¡G
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  text
  <font class=\"standouttext\">
  [/b]
  </font>
         = ¨Ï¦rÅéÅÜ²Ê¡C<br />

  <font class=standouttext>
  [email]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/email] 
  </font>
  = Åý§A¥i¥H«ö¤U Email «H½c±H«Hµ¹¥L¡C<br />

  <font class=standouttext>
  [i]
  </font>
  text
  <font class=standouttext>
  [/i]
  </font>
         = ¨Ï¦rÅé¦¨¬°±×Åé¡C<br />

  ";
  if ($config[allowimages]) {
    echo"<font class=\"standouttext\">";
    echo"[image]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/image]</font>  = ½Ð¶ñ¤J¹Ï¤ùªººô§}¡C<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  text
  <font class=standouttext>
  [/pre]
  </font>
   = §â¤å¦r¨âºÝ¥[¤W pre ¼ÐÅÒ¡C<br />

  <font class=standouttext>
  [quote]
  </font>
  text
  <font class=standouttext>
  [/quote] 
  </font>
  = §â¤å¦rªº¨âºÝ¥[¤W¤ô¥­½u¡C³o­Ó¼ÐÅÒ³q±`¥i¥Î©ó¦^ÂÐ§O¤Hªº¤å³¹§@¤Þ¨¥¡C<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = Åý¤å¦r¦¨¬°ºô§}³sµ²¡C<br />


  <font class=standouttext>
  [blush]
  </font> = <img src=\"$config[images]/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [cool]
  </font> = <img src=\"$config[images]/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [crazy]
  </font> = <img src=\"$config[images]/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [frown]
  </font> = <img src=\"$config[images]/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [laugh]
  </font> = <img src=\"$config[images]/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [mad]
  </font> = <img src=\"$config[images]/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [shocked]
  </font> = <img src=\"$config[images]/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [smile]
  </font> = <img src=\"$config[images]/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [tongue]
  </font> = <img src=\"$config[images]/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [wink]
  </font> = <img src=\"$config[images]/graemlins/wink.gif\"><br />


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
  <h3><a name=\"source\">§Ú¥i¥H¾Ö¦³¦P¼Ëªº¨t²Î¶Ü¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ¥i¥H¡A³o®M UBB.threads ¦ì©ó <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\">§ÚªºÂsÄý¾¹¤@©w­n±µ¨ü cookies ¶Ü¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ¬Oªº¡ACookies ³Q¥Î¨Ó°O¿ý±zªº±b¸¹/±K½X¡AÁÙ¦³±z©Ò¾\\Åª¹Lªº¤å³¹. ­Y±z¤£±µ¨ü cookies¡A¬Y¨Ç¥\¯à·|¹B§@¤£¥¿±`.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">§Ú­n¦p¦ó¦b¤å³¹¤¤¿ì²zÅªªÌ§ë²¼¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if (!$config[allowpolls]) {
    echo "<i>¥Ø«e¥u¶}©ñªO¥D¨Ï¥Î³o¶µ¥\¯à¡C</i><br />";
  }
  echo "
  ¥u­n¦b¤å³¹¤¤¨Ï¥Î¥H¤U¥N½X½s¿è³]­p§Y¥i¡C¦ý¬O½Ðª`·N¡A¤@¦ýµo§G«á¡A´NµLªk¦A§ó§ï§ë²¼¶µ¥Ø¤Î¤º®e¡C
  <p>
  [pollstart]<br />
  [polltitle=§Aªº§ë²¼¥DÃD]<br />
  [polloption=¿ï¶µ¤@]<br />
  [polloption=¿ï¶µ¤G]<br />
  [polloption=¿ï¶µ¢Ü]<br />
  [pollstop]



                

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"moreposts\">§Ú·Q­n¦b¨C­¶Åã¥Ü§ó¦h(©Î§ó¤Ö)ªº¤å³¹¡C</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ±z¥i¥H½s¿è­Ó¤H¸ê®Æ¨Ó§ïÅÜ¨C­¶Åã¥Üªº¤å³¹¼Æ. ±z¥i¥H§â¦¹­È³]¬° 1 ¨ì 99 ¤§¶¡¡C·í±zµù¥U«á¡A¨t²Î¤º©w¬O¨C­¶Åã¥Ü $theme[postsperpage] ½g¤å³¹.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\"> 

  <h3><a name=\"buttons\">³o¨Ç«ö¶s¬O¤°»ò·N«ä©O¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  ³o¨Ç«ö¶s¬O¥Î©ó¾ÉÄýµe­±¥H¤ÎÅã¥Ü¤å³¹ªº. ¸Ñ»¡¦p¤U:
  <p>·íÂsÄý©Ò¦³¸ÜÃD®É:
  <br />- ¥ª¥k½bÀY¥Nªí«e¤@­¶¥H¤Î¤U¤@­¶. 
  <br />- ¤W½bÀY¥NªíÅã¥Ü©Ò¦³ªº°Q½×°Ï.
  <br />- ±z¥i¥H«ö \"New Post\" ªº«ö¶s±i¶K·s¤å³¹.
  <br />- ¥[¸¹(+) ©M´î¸¹(-) ªº«ö¶s¤¹³\\±z¤Á´«®i¶}©Î¬O§éÅ|©Ò¦³ªº¸ÜÃD¡C®i¶}¸ÜÃD·|§â©Ò¦³ªº¸ÜÃD¥H¤Î¦^ÂÐªº¤å³¹¥H¾ðª¬¼Ò¦¡Åã¥Ü. §éÅ|¸ÜÃD«h¥uÅã¥Ü¨C­Ó»õÃDªº²Ä¤@½g¤å³¹¼ÐÃD¡A®ÇÃä«h¼Ðµù¦^ÂÐ¤å³¹ªº½g¼Æ.
  <p>·íÂsÄý³æ¿W¸ÜÃD®É:
  <br />- ¥ª¥k½bÀY·|±a±z¨ì«e¤@­Ó©Î¬O¤U¤@­Ó¸ÜÃD.
  <br />- ¤W½bÀY·|±a±z¦^©Ò¦³ªº¸ÜÃD¦Cªí.
  <br />- \"¥­©Z¼Ò¦¡\" «ö¶s·|§â¾ã­Ó¸ÜÃD¥H¤Î©Ò¦³¦^ÂÐªº¤å³¹¥þ³¡¦b¤@­¶¤¤Åu¶}¨Ó.
  <br />- \"¾ðª¬¼Ò¦¡\" «ö¶s«h¨C­¶¥uÅã¥Ü¤@½g¤å³¹¡A¦Ó¨ä¥Lªº¦^ÂÐ¤å³¹«h¦b©³¤U¥H¾ðª¬¼Ò¦¡Åã¥Ü¼ÐÃD.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\">¬°¤°»ò ¼ÐÃD,±i¶KªÌ¥H¤Î±i¶K¤é´Á¥i¥H«ö©O¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ±z¥i¥H«ö³o¨Ç¤å¦r¨Ó§ïÅÜÅã¥Üªº¶¶§Ç. ¦pªG±z«ö ¼ÐÃD ¡A¨t²Î·|§â¸ÜÃD±Æ§Ç¡A¥H¦r¥À¥Ñ«á(z)¨ì«e(a)Åã¥Ü¡C¦pªG±z¤S«ö¤@¦¸¼ÐÃD¡A¨t²Î·|¥H¦r¥À¥¿±`ªº¶¶§Ç(a ¨ì z) Åã¥Ü¸ÜÃD. ±i¶KªÌ¥H¤Î±i¶K¤é´Á«ö¤U¥hªº®ÄªG¥ç¦P.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\">¬°¤°»ò§A­n°Ý§Úªº email ©O¡H</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ¯u¹êªº email ¬O¥Î¨Ó³qª¾±z±K½X¡A¥H¤Î­Y¬O±z©Ò­q¾\\ªº°Q½×°Ï¦³·s¤å³¹¡A¨t²Î¤]·|³qª¾±z. ¥t¤@­Ó email (Fake Email) ¬O¥Î¨ÓÅã¥Üµ¹¨ä¥L¨Ï¥ÎªÌ¬Ýªº¡C§Ú­Ìª¾¹D¦³¨Ç¤H¥i¯à¤£§Æ±æ¤j®aª¾¹D¥Lªº Email «H½c¡A¦ý¬O¥»¨t²Î¥²¶·­nª¾¹D¡A¦]¬°±z¥i¯à·|­q¾\\°Q½×°Ï©Î¬O§Æ±æ¨t²Î¦Û°Ê§â¦³¤H¦^À³±zªº¤å³¹±Hµ¹±z. ¬°¦¹­ì¦]¡A±z¥²¶·­n´£¨Ñ¨t²Î±zªº¯u¹ê email ¦Ó´£¨Ñµ¹¤j®a¥t¤@­Ó email . ·íµM¡A±z¤]¥i¥H¦b Fake email ¸Ì­±¶ñ¤J±z¥¿±`¥i¥H¦¬«Hªº email ¡A¦pªG±z§Æ±æ¤j®a³£¯àª¾¹D±zªº email ªº¸Ü.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"register\">¬°¤°»ò§ÚÀ³¸Óµù¥U¤@­Ó±b¸¹¡H</a></h3>
<p>
</td></tr><tr><td class=\"lighttable\">
±zµù¥U¤§«á´N¥i¥H½s¿è±zªº­Ó¤HÀÉ®×¥H¤ÎÅã¥Ü³ß¦n¥H²Å¦X±zªº­Ó¤H­·®æ¡C¦b­Ó¤HÀÉ®×¸Ì­±¦³³\\¦h¿ï¶µ±z¥i¥Hªá´X¤ÀÄÁ¹Á¸Õ¬Ý¬Ý¡CÁÙ¦³¡Aµù¥U¨Ï¥ÎªÌÁÙ¥i¥H¦b¨C¦¸¤W¯¸µn¤J¨t²Î®É±oª¾¦³­þ¨Ç¤å³¹¬O·sªº¡A¥H¨¾¤îº|¹L­«­n¤å³¹. ³o¬O¥¼µù¥U¨Ï¥ÎªÌµLªk¨É¨ü¨ìªº­«­n¥\¯à.

</td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
