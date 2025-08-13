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
  $html -> send_header("FAQ (Gyakran ismételt kérdések)",$Cat,0,$user);
  $html -> table_header("FAQ (Gyakran ismételt kérdések)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  Itt láthatja a leggyakrabban feltett kérdéseket.  Rákattintva ezek egyikére kap egy kis segítséget, mellyel reméljük megoldódik az ön probléméja. Ha valami mást is szeretne megtudni, írja meg kérdését a következõ címre: <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Csatolhatok fájlt az üzeneteimhez?</a><br />
  <a href=\"#html\">Használhatok HTML kódot üzeneteimben?</a><br />
  <a href=\"#source\">Lehet e saját fórumom?</a><br />
  <a href=\"#cookies\">El kell fogadom a cookie-kat?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Több vagy kevesebb üzenetet szeretnék egy lapon.</a><br />
  <a href=\"#buttons\">Mit jelentenek a gombok?</a><br />
  <a href=\"#sortorder\">A cím, a feladó és a dátum miért klickelhetõ?</a><br />
  <a href=\"#email\">Miért kérdeznek két e-mail címet?</a><br />
  <a href=\"#register\">Miért kell regisztrálnom fehasználói nevet?</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  <a name=\"attach\"><h3>Csatolhatok fájlt az üzeneteimhez ?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Ha Ön Mozilla 4+ kompatibilis böngészõt használ akkor a válasz igen.  Az üzenet ellenõrzésekor lehetõsége van fájlt csatolnia az üzenetéhez.
    ";
  }
  else {
    echo "Nem.  A fájl csatolás ki van kapcsolva ezen a fórumon.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>Használhatok HTML kódot üzeneteimben ?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
 Önnek két lehetõsége van a beállításoktól függõen. Ha a HTML kód engedélyezve van akkor ki van írva <b>HTML kód engedélyezve</b> akkor normál HTML kódokat használhat üzeneteiben.  Ha jelölések is engedélyezve vannak akkor ki van írva <b>Jelölések engedélyezve</b>.  Ha a jelölések engedélyezve vannak akkor a következõ jelölések használhatók:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  szüveg
  <font class=standouttext>
  [/b]
  </font>
         = A szöveg vastag betüvel lesz írva.<br />

  <font class=standouttext>
  [email]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/email] 
  </font>
  = Az adott e-mail cím klickelhetõ lesz.<br />

  <font class=standouttext>
  [i]
  </font>
  szöveg
  <font class=standouttext>
  [/i]
  </font>
         = Az adott szöveg dõlt betüs lesz.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[kép]</font>";
    echo"url";
    echo "<font class=standouttext>";
    echo "[/kép]</font>  = Ha megadott url egy kép címét jelöli így a kép fog megjelenni.<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  szöveg
  <font class=standouttext>
  [/pre]
  </font>
   = Körülveszi az adott szöveget elõtaggal.<br />

  <font class=standouttext>
  [idézet]
  </font>
  szöveg
  <font class=standouttext>
  [/idézet] 
  </font>
  = Az adott szöveget idézetként jeleníti meg.  Ez használható idézéskor a válaszokban.<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = Az adott url kattintható link lesz.<br />


  <font class=standouttext>
  [pironkodik]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [szemérmes]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [bolond]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [szomorú]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [vidám]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [mogorva]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [ámuló]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [mosolyog]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [csintalan]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [kacsint]
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
  <a name=\"source\"><h3>Lehet e saját fórumom?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Igen, UBB.threads elérhetõ a <a href=\"http://www.infopop.com\">www.infopop.com</a> címen.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3>El kell fogadni a cookie-kat?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Igen.  Cookie-k használatával követhetõ a név/jelszó, és hogy mely üzeneteket olvasta már el.  A cookie-k elkogadása nélkül néhány funkció nem fog mûködni.

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
  <a name=\"moreposts\"><h3>Több vagy kevesebb üzenetet szeretnék látni egy lapon.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  A adatmódosításnál beállíthatja, hogy egy képernyõn hány üzenet legyen. Ezt 1 és 99 között lehet. Alapesetben {$theme['postsperpage']} üzenet laponként.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>Mit jelentenek a gombok?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  A gombok segítsével lehet a programban mozogni az üzenetek között.  A képernyõktõl függõen a gomboknak más-más  funkciója van.
  <p>Mikor az összes üzenet listája látható:
  <br />- A bal és a jobb nyil segítségével a lapok között lehet mozogni, az elõzõ és a következõ lapra. 
  <br />- A felfelé nyillal a fórum tartalomjegyzékéhez lehet menni.
  <br />- Az  \"Új üzenet\" gombbal új üzenetet lehet írni.
  <br />- A + és - gombok váltanak a részletes és rövidített kijelzési mód között.  A részletes listán látszik az összes üzenet címe és az üzenet maga is.  Csökkentett módban látszik az eredeti üzenet és a válaszok száma.
  <p>Az üzenet megtekintése közben:
  <br />- A bal és jobb nyillal az elõzõ vagy a következõ üzenetre jutunk.
  <br />- A fellfelé nyil az üzenetek listájához vezet.
  <br />- A \"Részletes\" gomb megnyomásakor a az üzenet és az összes válasz teljes egészében látszik.
  <br />- A \"Csökkentett\" gomb megnyomásakor az aktuális üzenet látszik és a válaszok listája.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>A cím, a feladó és a dátum miért klickelhetõ?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  A dátumra kattintással a  csökkenõ és a növekvõ elrendezés között lehet váltani.  Ha a címre egyszer kattintva a cím szerint csökkenõ ABC sorrendbe rendezi az üzeneteket.  A következõ kattintás ABC sorrenbe rendez.  Feladó és a dátum hasonlóan mûködik.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>Miért kérdeznek két e-mail címet?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Az igazi e-mail címét kell használnia az e-mail értesítésekhez és a fórumokra történõ feliratkozáshoz, a jelszó elküldéséhez. A másik amit más tagok látnak az adatainál. Ez azért van így, mert néhány ember nem szeretné nyilvánosságra hozni az igazi e-mail címét, de nekünk tudni kell a fórumokra történõ fel és leiratkozáshoz, vagy ha választ szeretne üzenetére. Ebbõl kifolyólag önnek meg kell addnia az igazi e-mail címét amit csak mi tudunk és nem adjuk át senkinek sem. A másik e-mail címe lesz nyilvánosságra hozva. Ha Önnek olyan e-mail címe van, amin végeznek spam szûrést, mint például a scream\@no.spam.domain.org, akkor azt is nyugodtan megadhatja.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>Miért kell regisztrálnom fehasználói nevet?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  A felhasználói név regiszrálásánál be tudja állítani az adatait és tulajdonságait. Rengeteg beállítási lehetõsége van a program  személyre szóló beállításra, a fórum élvezhetõbbé és barátságosabbá tételére. Szánjon rá pár percet és próbálgassa a beállításokat. Napi e-mail értesítést kaphat az üzenetére érkezet válaszokról, és azokról a témakörökrõl amikre feliratkozik. Ezeket az elõnyöket csak a regisztrált felhasználók élvezhetik.

  </td></tr></table  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
