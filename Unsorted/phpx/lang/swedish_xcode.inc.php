<?php
#$Id: swedish_xcode.inc.php,v 1.3 2003/10/20 14:59:48 ryan Exp $
$xTitle[] = "Vad är XCode?";
$bodyText[] = "XCode är en variation av HTML-taggar som du kanske redan är bekant med. Kort sagt ger det dig möjlighet att lägga till funktioner eller stil på din text som normalt skulle kräva HTML. Du kan använda XCode även om HTML är avstängt i forumen. Du kan också använda XCode även om HTML är tillåtet, eftersom det krävs mindre kodning för det, och du kommer aldrig förstöra layouten på sidorna du tittar på.";

$xTitle[] = "Webadresser och e-postlänkar";
$bodyText[] = "Xcode, unlike some other bulletin board codes, does not require you to specify links (URL) and email links.  However, you can specify URL's if you wish. <br><br>To create a link, just enter the URL, with either ftp://, telnet://, http:// or https:// before the link and PHPX will automatically turn it into a link.  Email does the same, just put in the email address and when you save your post, PHPX will create an email link.<br><br>If you wish to specify a URL link, or create a link on text or image you may use the <font color=red>[url=][/url]</font> tags.<br><br>Linking an Image:<br><font color=red>[url=http://www.phpx.org/][img=http://www.phpx.org/images/someimage.jpg][/url]</font> will create a link to www.phpx.org using the image specified.";


$xTitle[] = "Fet, understruken och kursiv stil";
$bodyText[] = "För att skapa kursiv, fet eller understruken text omringa den del av texten du vill formatera med [b] [/b] för fetstil, [u] [/u] för understruken eller [i] [/i] för kursiv stil.<br><br><font color=red>[b]</FONT>fet text</Font color=red>[/b]</font blir <b>fet text</B> och så vidare. Du kan också kombinera de olika alternativen med varandra: <font color=red>[b][u][i]</font>Fet understruken kursiv text<font color=red>[/B][/U][/I]</font> blir <b><u><i>Fet understruken kursiv text</B></I></U>";

$xTitle[] = "Textposition";
$bodyText[] = "Text kan positioneras hur du vill med hjälp av ett flertal taggar: [left], [right], [center] och [block].<br><br>Vänsterställd text:<br><font color=red>[left]</font>Vänsterställd text<font color=red>[/left]</font> blir <div align=left>Vänsterställd text</div><br><br>Högerställd text:<br><font color=red>[right]</font>Högerställd text<font color=red>[/right]</font> blir <div align=right>Högerställd text</div><br><br>Centrerad text:<br><font color=red>[center]</font>Centerad text<font color=red>[/center]</font> blir <div align=center>Centrerad text</div><br><br>Likställd text:<br><font color=red>[block]</font>Likställd text<font color=red>[/block]</font> blir <blockquote>Likställd text</blockquote><br><br>";

$xTitle[] = "Annan textformatering";
$bodyText[] = "Det finns tre andra attribut som ändras med XCode: Textstorlek, teckensnitt och färg<br><br>Textstorlek:<br>Textstorlek kan ställas allt mellan 1 (oläslig och 24 (jättestor).<br><font color=red>[size=18]</font>stor text<font color=red>[/size]</font> blir <span style=font-size:18px>Stor text</span><br><br>Typsnitt (font):<br>Du kan använda flera olika typsnitt i din text med hjälp av Xcode. Dessa kan dock bara vara standardtypsnitt!<br><font color=red>[font=times]</font>Times New Roman<font color=red>[/font]</font> blir <span style=font-family:times>Times New Roman</span><br><br>Textfärg:<br>Text kan också sättat till valfri färg. Du kan använda både färgens engelska namn och hexkod.<br><font color=red>[color=blue]</font>Blå text<font color=red>[/color]</font> blir <font color=blue>blå text</font>.<br><font color=red>[color=#0000FF]</font>blå text<font color=red>[/color]</font> blir <span style=color:#0000FF>blå text</span>";

$xTitle[] = "Linjer";
$bodyText[] = "För att sätta in en linje i din text använder du [line]-taggen.<br><br><font color=red>[line]</font> blir <hr width=100% border=2>";

$xTitle[] = "Listor";
$bodyText[] = "Du kan skapa punktlistor eller listor i bokstavs eller sifferordning<br><br>Oordnad punktlista:<b><font color=red>[list]</Font><br><font color=red>[*]</font>Punkt 1<br><font color=red>[*]</font>Punkt 2<br><font color=red>[/list]</font><br>Ger: <ul><li>Punkt 1</LI><LI>Punkt 2</LI></UL><br>Observera att du måste avsluta med en [/list] för att din lista ska fungera!<br><br>Det är precis lika enkelt att använda ornade listor. Lägg bara till [list A], [list a], [list 1], [list I] eller [list i]. A skapar en alfabetisk lista med kapilärer från A-Z. a skapar en lista från a-z med gementer. 1 skapar en numerisk lista, I skapar en lista med romerska siffror i kapilärer och i skapar en lista med romerska siffror i gemener. Här är ett exempel:<br><font color=red>[list A]</font><br><font color=red>[*]</font>Punkt 1<br><font color=red>[*]</font>Punkt 2<br><font color=red>[/list]</font><br>Resulatet blir:<ol type=A><li>Punkt 1</li><li>Punkt 2</LI>";

$xTitle[] = "Bilder";
$bodyText[] = "För att lägga in grafik i bilder använder du en weblänk och omringar den som visas i exemplet:<br><br><font color=red>[img]</font>http://www.yoururl.com/images/hot.jpg<font color=red>[/img]</font><br>I exemplet ovan gör XCode automatiskt om koden till den bild som finns på bildlänken. OBS! \"http://\"-delen av länken MÅSTE vara med för att [img]-taggen ska fungera. Notera också att vissa forumadmininistratörer stänger av denna funktion för att hindra olämpliga bilder från att visas!";

$xTitle[] = "Citera (quotea) andra meddelanden;
$bodyText[] = "För att referera något speciellt som någon har postat, kopiera bara texten och klistra in den enligt exemplet nedan:<br><br><font color=red>[quote]</font>Detta är ett citat.<font color=red>[/quote]</font> blir: <blockquote><div class=small><i>quote:</i><hr width=100% align=center>Detta är ett citat.<hr width=100% align==center></blockquote></div>";

$xTitle[] = "Posta kod";
$bodyText[] = "Fungerar ungefär som [quote]-taggen, och är bra för att visa programkod eller HTML till exempel.<br><br><font color=red>[code]</font>#!/usr/bin/perl<br><br>print \"Content-type: text/html\n\n\";<br>print \"Hello World!\"; <font color=red>[/code]</font> blir <font color=green><blockquote>#!/usr/bin/perl<br><br>print \"Content-type: text/html\n\n\";<br>print \"Hello World!\"; </blockquote></font>";

$xTitle[] = "Övrigt";
$bodyText[] = "Xcode kan bli ändrad för att passa det speciella forumets behov därför kanske en del funktioner inte fungerar som de är beskrivna här, efterom forumadministratören kan ha ändrat dem.";





