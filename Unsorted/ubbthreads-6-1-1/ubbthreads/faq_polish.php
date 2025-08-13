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
  $html -> send_header("Najczê¶ciej zadawane pytania ",$Cat,0,$user);
  $html -> table_header("Najczê¶ciej zadawane pytania");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  Poni¿ej znajduj± siê odpowiedzi na najczê¶ciej zadawane pytania. Je¶li masz jakie¶ inne pytania to <a href=\"mailto:{$config['emailaddy']}\">napisz do mnie</a> - postaraj siê mo¿liwie szczegó³owo napisaæ o co chodzi.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Czy mo¿na do³±czaæ pliki do wiadomo¶ci?</a><br />
  <a href=\"#html\">Czy mo¿na u¿ywaæ HTML w wiadomo¶ciach?</a><br />
  <a href=\"#source\">Sk±d mogê ¶ci±gn±æ program, na którym zrealizowane jest to forum?</a><br />
  <a href=\"#cookies\">Czy muszê w³±czyæ w przegl±darce przyjmowanie cookies?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Chcia³bym widzieæ wiêcej (lub mniej) wiadomo¶ci na stronê.</a><br />
  <a href=\"#buttons\">Do czego s³u¿± poszczególne przyciski?</a><br />
  <a href=\"#sortorder\">Co daje klikniêcie na napisach Tytu³, Autor i Data wys³ania?</a><br />
  <a href=\"#email\">Po co w ustawieniach wpisywaæ dwa adresy email?</a><br />
  <a href=\"#register\">Po co zak³adaæ konto?</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  <a name=\"attach\"><h3>Czy mo¿na do³±czaæ pliki do wiadomo¶ci?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Je¶li masz przegl±darkê kompatybiln± z Mozill± w wersji conajmniej 4 to tak.  Mo¿esz do³±czyæ plik podczas podgl±dania wiadomo¶ci do wys³ania.
    ";
  }
  else {
    echo "Nie.  Do³±czanie plików zosta³o zablokowane w tym forum.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>Czy mo¿na u¿ywaæ HTML w wiadomo¶ciach?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
   Zale¿y od k±cika. Je¶li jest napisane <b>HTML Tak</b> to mo¿na u¿ywaæ HTML.
   Je¶li jest napisane <b>UBBCode Tak</b> to mo¿na u¿ywaæ nastêpuj±cych znaczników do formatowania tekstu:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  tekst
  <font class=standouttext>
  [/b]
  </font>
         = tekst pogrubiony<br />

  <font class=standouttext>
  [email]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/email] 
  </font>
  = tworzy odno¶nik do podanego adresu email<br />

  <font class=standouttext>
  [i]
  </font>
  tekst
  <font class=standouttext>
  [/i]
  </font>
         = tekst pochylony<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[obrazek]</font>";
    echo"http://www.jakas-strona.com/images/obrazek.gif";
    echo "<font class=standouttext>";
    echo "[/obrazek]</font>  = wy¶wietla obrazek o podanym adresie<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  tekst
  <font class=standouttext>
  [/pre]
  </font>
   = dodaje pust± linijkê przed i za tekstem<br />

  <font class=standouttext>
  [cytat]
  </font>
  tekst
  <font class=standouttext>
  [/cytat] 
  </font>
  = otacza podany tekst cudzys³owami i oddziela go liniami poziomymi. S³u¿y do cytowania w odpowiedziach<br />

  <font class=standouttext>
  [url]
  </font>
  http://www.jakas-strona.com
  <font class=standouttext>
  [/url]</font>    = tworzy odno¶nik z podanego adresu<br />


  <font class=standouttext>
  [wstyd]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [szalone]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [smutne]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [smiech]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [wscieklosc]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [szok]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [usmiech]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [jezyk]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [oczko]
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
  <a name=\"source\"><h3>Sk±d mogê ¶ci±gn±æ program, na którym zrealizowane
jest to forum?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Program UBB.threads jest dostêpny pod adresem <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3>Czy muszê w³±czyæ w przegl±darce przyjmowanie cookies?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Tak. Cookies s± u¿ywane do automatycznego logowania oraz zaznaczania,
  które wiadomo¶ci przeczyta³e¶ w ci±gu danej sesji. 
  Przy wy³±czonym przyjmowaniu cookies niektóre funkcje mog± nie dzia³aæ. 

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
  <a name=\"moreposts\"><h3>Chcia³bym widzieæ wiêcej (lub mniej) wiadomo¶ci na
stronê.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Mo¿na to zmieniæ w Ustawieniach. Mo¿liwe warto¶ci to 1 do 99 wiadomo¶ci
na stronê. Domy¶lnie ustawiane jest {$theme['postsperpage']} wiadomo¶ci na stronê.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>Do czego s³u¿± poszczególne przyciski?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Przyciski s³u¿± do poruszania siê miêdzy wiadmo¶ciami. Zale¿nie od miejsca
mog± oznaczaæ ró¿ne rzeczy. 

  <p>W spisie wszystkich w±tków: 
  <br />- strza³ki w lewo i w prawo poka¿± inne strony z w±tkami 
  <br />- strza³ka w górê powoduje przej¶cie do spisu forum
  <br />- przycisk \"Nowa wiadomo¶æ\" powoduje przej¶cie do edycji nowej wiadomo¶ci 
  <br />- przyciski + i - powoduj± rozwiniêcie i zwiniêcie w±tków 

  <p> Podczas przegl±dania pojedynczego w±tku: 
  <br />- strza³ki w lewo i w prawo powoduj± przej¶cie do nastêpnego lub poprzedniego w±tku. 
  <br />- strza³ka w górê powoduje przej¶cie do ostatnio przegl±danej strony z list± w±tków 
  <br />- Przycisk \"Ca³y w±tek na jednej stronie\" powoduje pokazanie tre¶ci wszystkich wiadomo¶ci w danym w±tku 
  <br />- Przycisk \"Drzewiasty\" powoduje przegl±danie wiadomo¶ci w trybie w±tkowym. 

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>Co daje klikniêcie na napisach Tytu³, Autor, Data wys³ania?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Powoduje posortowanie wiadomo¶ci wed³ug danego pola: 
<br />Tytu³ - wed³ug tematów w porz±dku alfabetycznym (lub w porz±dku odwrotnym) 
<br />Autor - wed³ug autora 
<br />Data wys³ania - wed³ug daty wys³ania. 


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>Po co w ustawieniach wpisywaæ dwa adresy email?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Adres wy¶wietlany w wiadomo¶ciach jest to adres, który bêd± widzieli inni, gdy bêd± przegl±dali Twoje dane.
Je¶li nie chcesz, ¿eby znali Twój adres to nic w to pole nie wpisuj.
Prenumeratê i tak mo¿esz dostawaæ na adres podany przy zak³adaniu konta.  

  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>Po co zak³adaæ konto?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Przede wszystkim po to by móc wysy³aæ wiadomo¶ci na forum, na którym
zablokowane jest wysy³anie wiadomo¶ci anonimowych - bez konta mo¿na jedynie
czytaæ. Poza tym posiadacz konta mo¿e dostosowaæ forum do swoich wymagañ oraz bêdzie widzia³ jakie nowe
wiadomo¶ci pojawi³y siê od jego ostatniej wizyty.   

  </td></tr></table>  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
