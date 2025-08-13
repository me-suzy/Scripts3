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
// Îòñûëàåì ñòðàíèöó
   $html = new html;
   $html -> send_header("FAQ (Frequently Asked Questions)",$Cat,0,$user);
   $html -> table_header("FAQ (Frequently Asked Questions)");

   $phpurl = $config['phpurl'];

echo "
<table cellspacing=0 border=0 width=100% class=\"darktable\">
<tr><td>
Íèæå Âû âèäèòå ñïèñîê ÷àñòî çàäàâàåìûõ âîïðîñîâ.  Âû ìîæåòå êëèêíóòü ïî ëþáîìó èç íèõ è ïîëó÷èòü íåáîëüøóþ ñïðàâêó.  Åñëè Âû ñ÷èòàåòå, ÷òî íà ýòîé ñòðàíöå íåäîñòàòî÷íî èíôîðìàöèè, ïîæàëóéñòà, íàïèøèòå îá ýòîì ïî àäðåñó <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
</p><p>
</td></tr><tr><td class=\"lighttable\">
<a href=\"#attach\">Ìîãó ëè ÿ ïðèêðåïèòü ôàéë ê ñâîåìó ñîîáùåíèþ?</a><br />
<a href=\"#html\">Ìîãó ëè ÿ èñïîëüçîâàòü HTML â ìîèõ ñîîáùåíèÿõ?</a><br />
<a href=\"#source\">Ìîãó ëè ÿ ñîçäàòü ñîáñòâåííûé ôîðóì?</a><br />
<a href=\"#cookies\">Æåëàòåëüíî ëè ðàçðåøèòü ïðè¸ì &quot;cokies&quot;?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
<a href=\"#moreposts\">ß õî÷ó âèäåòü áîëüøå (ìåíüøå) çàïèñåé íà îäíîé ñòðàíèöå.</a><br />
<a href=\"#buttons\">Êàêîâî íàçíà÷åíèå êíîïîê?</a><br />
<a href=\"#sortorder\">Êóäà âåäóò ññûëêè &quot;Òåìà&quot;, &quot;Àâòîð&quot; è &quot;Îòïðàâëåíî&quot;?</a><br />
<a href=\"#email\">Çà÷åì íóæíû ÄÂÀ email-àäðåñà?</a><br />
<a href=\"#register\">Ïî÷åìó ÿ äîëæåí ðåãèñòðèðîâàòüñÿ íà ôîðóìå?</a>
<p>
</td></tr></table>


<p>&nbsp;
<table cellspacing=0 border=0 width=100% class=\"darktable\">
<tr><td>
<a name=\"attach\"><h3>Ìîãó ëè ÿ ïðèêðåïèòü ôàéë ê ñâîåìó ñîîáùåíèþ?</a></h3>
<p>
</td></tr><tr><td class=\"lighttable\">
";
  if($config['files']) {
  echo "
Åñëè Âàø áðàóçåð ñîâìåñòèìûé ñ Mozilla 4+  òîãäà îòâåò: &quot;Äà, Âû ìîæåòå&quot;.  Âî âðåìÿ ïðåäâàðèòåëüíîãî ïðîñìîòðà Âû ìîæåòåâîñïîëüçîâàòüñÿ ïðèâåä¸ííîé òàì ôîðìîé.
";
}
  else {
  echo "Íåò. Ïðèêðåïëåíèå ôàéëîâ çàïðåùåíî íà ýòîì ôîðóìå.";
}

  echo "
<p>&nbsp;

</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">
<a name=\"html\"><h3>Ìîãó ëè ÿ èñïîëüçîâàòü HTML â ìîèõ ñîîáùåíèÿõ?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
Ñìîòðÿ êàê íàñòðîåíà äîñêà, íà êîòîðîé Âû õîòèòå ýòî äåëàòü. Åñòü äâå âîçìîæíîñòè äëÿ ðàçìåòêè òåêñòà.  Åñëè ðàçìåòêà HTML âêëþ÷åíà, âîçëå îïèñàíèÿ Äîñêè âû óâèäèòå íàäïèñü <b>Ðàçìåòêà HTML Âêë</b> è âû ìîæåòå èñïîëüçîâàòü îáû÷íûå òåãè HTML â Âàøèõ ñîîáùåíèÿõ.  Åñëè Âû âèäèòå âîçëå îïèñàíèÿ äîñêè íàäïèñü <b>Ñïåöðàçìåòêà Âêë</b>, òî Âû ìîæåòå âîñïîëüçîâàòüñÿ ñïåöèàëüíûì ÿçûêîì Ñïåöðàçìåòêè.  ßçûê ñîñòîèò èç ñëåäóþùèõ ñîêðàùåíèé:
<br /><br />
<font class=standouttext>
[b]
</font>
óòîëùàåìûé òåêñò
<font class=standouttext>
[/b]
</font>
 = äåëàåò òåêñò óòîëù¸ííûì.<br />

<font class=standouttext>
[email]
</font>
  vasya\@pupkin.ru
<font class=standouttext>
[/email]
</font>
= ñîçäà¸ò ññûëêó íà óêàçàííûé àäðåñ ýëåêòðîííîé ïî÷òû.<br />

<font class=standouttext>
[i]
</font>
Íàêëîíÿåìûé Òåêñò
<font class=standouttext>
[/i]
</font>
 = Äåëàåò ïðèâåä¸ííûé òåêò íàêëîííûì.<br />

";
  if ($config['allowimages']) {
  echo"<font class=standouttext>";
  echo"[image]</font>";
  echo"http://pupkin.ru/foto.gif";
  echo "<font class=standouttext>";
  echo "[/image]</font>= ïðåâðàùàåò ïðèâåä¸ííóþ ññûëêó â òåã &lt;img src=&quot;http://pupkin.ru/foto.gif&quot;&gt; tag.<br />";
}

  echo "

<font class=standouttext>
[pre]
</font>
ôîðìàòèðîâàííûé òåêñò
<font class=standouttext>
[/pre]
</font>
 = îêðóæàåò òåêñò òåãàìè &lt;pre&gt; b &lt;/pre&gt;.<br />

<font class=standouttext>
[quote]
</font>
Îòñòóïëåííûé òåêñò
<font class=standouttext>
[/quote]
</font>
 = Îêðóæàåò òåêñò òåãàìè îòñòóïà è ãîðèçîíòàëüíûìè ðàçäåëèòåëÿìè.  Î÷åíü óäîáíî äëÿ öèòèðîâàíèÿ â îòâåòàõ.<br />

<font class=standouttext>
[url]
</font>
http://pupkin.ru/
<font class=standouttext>
[/url]</font>= Ïðåâðàùàåò äàííûé àäðåñ â ññûëêó.<br />


<font class=standouttext>
[ñìóù]
</font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

<font class=standouttext>
[ñïîê]
</font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

<font class=standouttext>
[áåçóì]
</font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

<font class=standouttext>
[õììì]
</font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

<font class=standouttext>
[õàõà]
</font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

<font class=standouttext>
[çëî]
</font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

<font class=standouttext>
[øîê]
</font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

<font class=standouttext>
[óëûá]
</font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

<font class=standouttext>
[áåáå]
</font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

<font class=standouttext>
[ìèã]
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
<a name=\"source\"><h3>Ìîãó ëè ÿ ñîçäàòü ñîáñòâåííûé ôîðóì?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Äà, UBB.threads ìîæíî ñêà÷àòü ñ ñàéòà <a href=\"http://www.infopop.com\">www.infopop.com</a>

<p>&nbsp;
</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">
<a name=\"cookies\"><h3>Æåëàòåëüíî ëè ðàçðåøèòü ïðè¸ì &quot;cookies&quot;?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Äà. &quot;Cookies&quot; èñïîëüçóþòñÿ äëÿ îòñëåæèâàíèÿ Âàøåãî Èìåíè/Ïàðîëÿ , êîëè÷åñòâà ïðî÷èòàííûõ Âàìè ñîîáùåíèé è ìíîãèõ äðóãèõ ïðèíîñÿùèõ óäîáñòâî çàäà÷.  Áåç ðàçðåøåíèÿ ïðè¸ìà ôàéëîâ &quot;cookies&quot; íåêîòîðûå ôóíêöèè ðàáîòàþò íå ñîâñåì êîððåêòíî.


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
<a name=\"moreposts\"><h3>ß õî÷ó âèäåòü áîëüøå (ìåíüøå) çàïèñåé íà îäíîé ñòðàíèöå.</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Âû ìîæåòå èçìåíèòü êîëè÷åñòâî îòîáðàæàåìûõ íà îäíîé ñòðàíèöå ñîîáùåíèé ðåäàêòèðóÿ Âàø ëè÷íûé ïðîôèëü.  Âû ìîæåòå óñòàíîâèòü ýòî çíà÷åíèå îò 1 äî 999 ñîîáùåíèé íà ñòðàíèöó.  Êîãäà Âû ïåðâûé ðàç ïîïàäàåòå íà ôîðóì, ýòî çíà÷åíèå ñîîòâåòñòâóåò {$theme['postsperpage']} ñîîáùåíèÿì íà îäíó ñòðàíèöó(çíà÷åíèå ïî óìîë÷àíèþ).


<p>&nbsp;
</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">

<a name=\"buttons\"><h3>Êàêîâî íàçíà÷åíèå êíîïîê?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">

  Êíîïêè èñïîëüçóþòñÿ äëÿ íàâèãàöèè è îòîáðàæåíèÿ ñîîáùåíèé.  Â çàâèñèìîñòè îò ýêðàíà, íà êîòîðîì Âû íàõîäèòåñü, êíîïêè ìîãóò èìåòü ðàçëè÷íîå íàçíà÷åíèå.
<p>Êîãäà îòîáðàæàåòñÿ ñïèñîê âåòâåé:
<br />- Ëåâàÿ è ïðàâàÿ ñòðåëêè ïåðåâîäÿò ýêðàí íà ïðåäûäóùóþ è ñëåäóþùóþ ñòðàíèöû ñ ñîîáùåíèÿìè.
<br />- Ñòðåëêà ââåðõ âûâîäèò ñïèñîê âñåõ äîñòóïíûõ Äîñîê.
<br />- Êíîïêà &quot;Íîâîå ñîîáùåíèå&quot; ñîçäà¸ò íîâîå ñîîáùåíèå.
<br />- Êíîïêè + è - ðàñøèðÿþò è ñóæàþò âèä âåòâåé.  Ðàñøèðåííûå Âåòâè îòîáðàæàþòñÿ êàê çàãîëîâêè âñåõ îòâåòîâ â âåòâè.  Ñæàòûé ôîðìàò îòîáðàåò òîëüêî çàãîëîâîê ïåðâîãî ñîîáùåíèÿ è êîëè÷åñòâî îòâåòîâ öèôðîé.
<p>Ïðè ïðîñìîòðå êîíêðåòíîé Âåòâè:
<br />- Ëåâàÿ è ïðàâàÿ ñòðåëêè ïåðåâîäÿò ýêðàí íà ïðåäûäóùóþ è ñëåäóþùóþ Âåòâü
<br />- Ñòðåëêà ââåðõ âîçâðàùàåò íà ñïèñîê âñåõ äîñòóïíûõ íà Äîñêå âåòâåé.
<br />- Êíîïêà \"Îòîáðàæàòü Ñïèñîê\" âûâîäèò íà îäíó ñòðàíèöó âñå ñîîáùåíèÿ â âåòâè. Ïîëó÷àåòñÿ íå÷òî âðîäå òðàäèöèîííîé ãîñòåâîé êíèãè
<br />- Êíîïêà \"Îòîáðàæàòü Äåðåâî\" ïåðåâîäèò îòîáðàæåíèå â ðåæèì ñòðóêòóðèðîâàííîãî ïîêàçà âåòâåé.



<p>&nbsp;
</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">
<a name=\"sortorder\"><h3>Êóäà âåäóò ññûëêè &quot;Òåìà&quot;, &quot;Àâòîð&quot; è &quot;Îòïðàâëåíî&quot;?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Âû ìîæåòå êëèêíóòü ïî ëþáîé èç ýòèõ ññûëîê äëÿ èçìåíåíèÿ ïîðÿäêà ñîðòèðîâêè ñîîáùåíèé íà äîñêå.  Êëèêíóâ ïî ññûëêå &quot;Òåìà&quot; îäèí ðàç, âû ïîëó÷èòå ñîðòèðîâêó ñîîáùåíèé ïî òåìå îò &quot;ß&quot; äî &quot;À&quot;.  Êëèêíóâ ïî òîé æå ññûëêå âòîðîé ðàç, Âû ïîëó÷èòå ñîðòèðîâêó îò &quot;À&quot; äî &quot;ß&quot;.&quot;Àâòîð&quot; è &quot;Îòïðàâëåíî&quot; ðàáîòàþò àíàëîãè÷íî.



<p>&nbsp;
</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">
<a name=\"email\"><h3>Çà÷åì íóæíû ÄÂÀ email-àäðåñà?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Ðåàëüíûé e-mail àäðåñ íåîáõîäèì äëÿ óâåäîìëåíèé î íîâûõ ñîîáùåíèÿõ, ïîäïèñîê íà äîñêè, ïåðåäà÷è Âàì ïàðîëÿ â ñëó÷àå åãî óòåðè.  Âòîðîé, &quot;ïóáëè÷íûé&quot; àäðåñ ñëóæèò äëÿ îòîáðàæåíèÿ â âàøèõ ñîîáùåíèÿõ ñëóæèò äëÿ ïåðåïèñêè ìåæäó ïîëüçîâàòåëÿìè è Âàìè.  Ýòî ðåàëèçîâàíî äëÿ ëþäåé, êîòîðûå íå æåëàþò ïðåäîñòàâëÿòü ïóáëèêå ñâîé ïðèâû÷íûé àäðåñ, îïàñàÿñü ÑÏÀÌÀ, îäíàêî Âàø àäðåñ íåîáõîäèì äëÿ âûøåïåðå÷èñëåííûõ íàçíà÷åíèé. Íåêîòîðûå ëþäè óêàçûâàþò ÷òî-ëèáî âðîäå &quot;scream\@no.spam.domain.com&quot;.  Ýòîò ìåòîä ïîçâîëÿåò âñåì âûÿñíèòü Âàø ðåàëüíûé e-mail, îäíàêî àâòîìàòè÷åñêèå ÑÏÀÌ-ÀÃÅÍÒÛ âûçíàòü åãî íå â ñîñòîÿíèè. Åñëè Âû íå îïàñàåòåñü ÑÏÀÌà, ïîæàëóéñòà, âïèøèòå â îáà ïîëÿ îäèí è òîò æå, Âàø ðåàëüíûé àäðåñ.



<p>&nbsp;
</td></tr><tr><td class=\"alternatetable\">
</td></tr><tr><td class=\"darktable\">
<a name=\"register\"><h3>Ïî÷åìó ÿ äîëæåí ðåãèñòðèðîâàòüñÿ íà ôîðóìå?</h3></a>
<p>
</td></tr><tr><td class=\"lighttable\">
  Ðåãèñòðèðóÿ Èìÿ Ïîëüçîâàòåëÿ, Âû ïîëó÷àåòå âîçìîæíîñòü íàñòðîèòü Âàø ïåðñîíàëüíûé ïðîôèëü (Ëè÷íóþ Èíôîðìàöèþ) ïî Âàøåìó âêóñó òàê, ÷òî Âàøè ñîîáùåíèÿ áóäóò ëåãêî óçíàâàåìû äàæå ïî îáùåìó âèäó çà ñ÷¸ò ïîäïèñè, íàñòðîèòü âèä ôîðóìîâ òàê, êàê Âàì óäîáíî. Ïîñâÿòèâ íåìíîãî âðåìåíè íàñòðîéêàì âàøåãî ïðîôèëÿ Âû ñýêîíîìèòå ìíîãî âðåìåíè è óñèëèé â äàëüíåéøåì.  Ê òîìó æå òîëüêî çàðåãèñòðèðîâàííûå ïîëüçîâàòåëè ìîãóò ïîëó÷àòü ïðèâàòíûå (ëè÷íûå) ñîîáùåíèÿ, ïîäïèñûâàòüñÿ íà ôîðóìû öåëèêîì è ïîëó÷àòü óâåäîìëåíèÿ î ïîñòóïèâøèõ îòâåòàõ íà èõ ñîîáùåíèÿ.

</td></tr></table>  </td></tr></table>
";
// -------------
// Îòñûëàåì çàâåðøàþùóþ ÷àñòü ñòðàíèöû
  $html -> send_footer();
