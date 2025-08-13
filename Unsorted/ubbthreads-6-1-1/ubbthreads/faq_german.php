<?
/*
# UBBThreads, Version 6
# Official Release Date for UBBThreads Version5: 06/05/2002 

# First version of UBBThreads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2001.
# For more info on the UBBThreads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBBThreads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

/*
======================================================================

bersetzung Carl Erling info@chrom.de

Erlaeuterung:
--------------------
Dieses FAQ wurde dem engl. FAQ angepasst und wir haben nun endlich auch
ein aktuelle deutsche Version, da die offiziell beigelegte (Stand V6 beta5) 
im Vergleich zur englischen Version URALT ist.

Und eine Erlaeuterung des UBB Code eingebaut, die sich Eueren Aenderungen 
im Language-File automatisch anpasst!!!


Achtung: Kleine Anpassungen Euererseits sind noetig:
-------------------------------------------------------------
* Ich Duze und Sieze hier nicht.. ggf. bitte anpassen.

* Ich habe einige Sachen (mit Kommentar) auskommentiert, die keinen Sinn machen,
  das kann man aber leicht rueckgaengig machen: Kommentarzeichen "//", bzw. "<!--" und "-->" entfernen.

* Ich habe die Erklrung zum allseits bekannten "MARKREAD" Hack eingebaut.
  Wenn Ihr den Hack nicht verwendet, ausbauen!
    
* REGELN wurde auf eine Sonderseite verschoben, von der auch vom Board aus gelinkt wird.
  Die Regeln werden jetzt vom ADMIN aus verwaltet. Dann muss man nicht mehrere Regeln warten.
  Wenn Ihr die Regeln im FAQ wollt, dann ndert einfach den Text dort unten nach Eueren Wuenschen.


======================================================================

*/

// Require the library
   require ("main.inc.php");
                                
// ---------------------
// Send the page to them
  $html = new html; 
  $html -> send_header("FAQ (Frequently Asked Questions)",$Cat,0,0,0,0,$user);
  $html -> table_header("FAQ (Frequently Asked Questions)");

  $phpurl = $config['phpurl'];

  $html -> open_table();
  echo " 
  <tr><td class=darktable>
Hier findest Du eine Liste der h&auml;ufigsten Fragen. Du kannst auf jede dieser Fragen klicken, um eine m&ouml;gliche L&ouml;sung Deines Problems zu finden. Wenn Du weitere Vorschl&auml;ge oder Fragen hast, die hier stehen sollten, sende bitte eine Mail an 
<a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>. (Neu hinzugekommene Fragen haben ein '*'.)
  </p><p  </p><p>  </td></tr><tr><td class=\"lighttable\">

<p><b>Registrieren:</b><br>
  <a href=\"#register\">Warum sollte ich einen Benutzernamen registrieren?</a><br>
  <a href=\"#email\">Warum werde ich nach zwei Email-Adressen gefragt?</a><br>
  <a href=\"#cookies\">Mu&szlig; mein Browser Cookies akzeptieren?</a><br>

<p><b>Login:</b><br>
  *<a href=\"#login\">'Login', wie mache ich das?</a><br>
  *<a href=\"#password\">Ich habe mein Passwort vergessen.</a><br>
  *<a href=\"#help\">HILFE! - Einloggen klappt einfach nicht!</a><br>

<p><b>Pers&ouml;nliche Einstellungen:</b><br>
  *<a href=\"#changepassword\">Wie &auml;ndere ich mein Passwort?</a><br>
  *<a href=\"#editprof\">Wie bearbeite ich mein 'Profil'?</a><br>
  *<a href=\"#limit\">Gibt es ein Limit der Gr&ouml;&szlig;e eines Bildes in der Signatur?</a><br>
  *<a href=\"#titles\">Was bedeuten die 'Titel' und wie kriegt man einen?</a><br>

<p><b>Anzeige:</b><br>
  <a href=\"#buttons\">Was steckt hinter den ganzen Kn&ouml;pfen</a><br>
  <a href=\"#sortorder\">Wieso sind 'Betreff', 'Absender' usw. anklickbar?</a><br>
<!-- HACK nchste Zeile -->  
  *<a href=\"#setread\">Ich will alle Nachrichten als'gelesen' markieren - die <b>NEU</b>-Flags sollen weg.</a><br>
  *<a href=\"#flash\">Was bedeutet das blinkende 'Brief'-Symbol oben in der Menu-Zeile?</a><br>
  *<a href=\"#letters\">Was bedeuten die Buchstaben bei der Liste der empfangenden privaten Nachricht?</a><br>
  <a href=\"#moreposts\">Ich will mehr (oder weniger) Nachrichten je Seite sehen.</a><br>
  *<a href=\"#editdisp\">Sollte ich die optische Anzeige im Forum &auml;ndern?</a><br>
  *<a href=\"#titlestring\">Was ist das f&uuml;r ein Text, der in Nachrichten immer neben meinem Benutzernamen angezeigt wird?</a><br>

<p><b>Nachrichten eingeben:</b><br>
  *<a href=\"#answer\">Wie finde ich Nachrichten, in denen meine Frage vielleicht schon beantwortet wurde?</a><br>
  *<a href=\"#subject\">Warum ist der Titel ('Betreff') der ersten Nachricht eines Threads so wichtig?</a><br>
  *<a href=\"#whitespace\">Wie mu&szlig; ich meine Nachrichten schreiben, da&szlig; ich Leerr&auml;ume zwischen meine Abs&auml;tze bekomme?</a><br>
  *<a href=\"#spellchecker\">Gibt es eine Rechtschreibkorrektur?</a><br>
  *<a href=\"#mistake\">Was geschieht, wenn ich mit meiner Nachricht einen Fehler mache?</a><br>
  *<a href=\"#wrongforum\">Was mu&szlig; ich machen, wenn ich eine Nachricht versehentlich ins falsche Forum setze?</a><br>
  <a href=\"#html\">Wie kann ich HTML (oder 'Markup'-Tags) in meinen Nachrichten benutzen? <img src=\"{$config['images']}/graemlins/cool.gif\"></a><br>
  *<a href=\"#image\">Wie kriege ich ein Bild in eine Nachricht oder Signatur</a><br>
  *<a href=\"#url\">Die URL-Tags (Markup bzw. Html) funktionieren bei mir nicht.</a><br>
  <a href=\"#attach\">Kann ich eine Datei an meine Nachricht anh&auml;ngen?</a><br>
  <a href=\"#polls\">Wie kann ich eine Umfrage in meine Nachricht einbauen?</a><br>

<p><b>Sonstiges:</b><br>
  *<a href=\"#rules\">Was sind die Forum-Regeln?</a><br>
  
  <!-- HACK out:
  <a href=\"#source\">Kann ich mein eigenes Forum einrichten?</a><br> 
  End HACK -->
  <p>
  ";
  $html -> close_table();

  echo "
  <p>&nbsp;
  ";

  $html -> open_table();

  echo "

  <p>&nbsp; 
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"register\"></a>Warum sollte ich einen Benutzernamen registrieren?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Durch die Registrierung Deines Benutzernamens kannst Du Dein Profil und pers&ouml;nliche Darstellungseinstellungen (im Menu {$ubbt_lang['CONTROL_PANEL']}) dauerhaft konfigurieren. Du wirst die Foren am einfachsten nutzen k&ouml;nnen, wenn Du Dein Benutzer-Profil Deinen W&uuml;nschen angepa&szlig;t hast.
<p>Es existieren viele M&ouml;glichkeiten, Deinen Aufenthalt auf diesem Forum zu angenehm wie m&ouml;glich zu machen, deshalb bitten wir Dich, diese Einstellungsm&ouml;glichkeiten doch mal auszuprobieren.
<p>Au&szlig;erdem ist nur f&uuml;r registrierte Benutzer die Anzeige aller \&quot;Neuen Nachrichten\&quot; seit dem letzten Besuch m&ouml;glich. - Und Du erh&auml;ltst damit stark erweiterte Schreibrechte und die M&ouml;glichkeit, Deine eigenen Nachrichten nachtr&auml;glich noch bearbeiten oder wieder l&ouml;schen zu k&ouml;nnen.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\"></a>Warum werde ich nach zwei Email-Adressen gefragt?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Forum-Subskriptionen sowie zur Versendung des Passworts verwendet. Die zweite Email-Adresse ist die, die andere Benutzer sehen, wenn sie sich Dein Profil anschauen. - Dies kommt dem Wunsch einiger Benutzer entgegen, da&szlig; nicht jeder die 'richtige' Email-Adresse lesen soll, aber wir ben&ouml;tigen diese, wenn Du Dich sich in die Subskriptions-Liste eines Forums eintr&auml;gst, oder z.B. das Zusenden von Antworten auf Deine Nachrichten w&uuml;nschst.
<p>Aus diesem Grund kannst Du uns Deine echte Email-Adresse geben, und gleichzeitig eine andere der Allgemeinheit zur Verf&uuml;gung stellen. Viele Leute geben gerne eine Adresse wie zum Beispiel meinname\@no.spam.meinesite.com an. Auf diesem Weg k&ouml;nnen andere Leute zwar Deine korrekte Email-Adresse erkennen und bei eMails an Dich einfach 'no.spam.' rausl&ouml;schen, jedoch ist es Massen-Email-Sendern nicht m&ouml;glich, diese aus den Forum-Seiten rauszulesen und zu verwenden.



  <p>&nbsp; 
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\"></a>Mu&szlig; mein Browser Cookies akzeptieren?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Ja. Cookies werden verwendet, um Benutzernamen, Passwort, sowie Deine bereits bei diesem Besuch gelesenen Nachrichten zu verarbeiten. - Ohne Cookies funktionieren einige Funktionen nicht korrekt.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"login\"></a>'Login', wie mache ich das?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Wenn Du einen Account (Benutzernamen) innerhalb dieses Forum registriert hast, mu&szlig;t Du 'einloggen' um die M&ouml;glichkeit, pers&ouml;nliche Einstellungen nach Deinem Geschmack zu machen, nutzen zu k&ouml;nnen. - au&szlig;erdem erh&auml;ltst Du damit stark erweiterte Schreibrechte und die M&ouml;glichkeit, Deine eigenen Nachrichten nachtr&auml;glich noch bearbeiten oder wieder l&ouml;schen zu k&ouml;nnen.		<p>Um Einzuloggen, schaue oben im Menu nach dem Link '{$ubbt_lang['BUTT_LOGIN']} '. Dieser link f&uuml;hrt Dich auf eine Seite, wo Du Deinen Namen und Dein Passwort eingeben kannst. (Das Passwort unterscheidet zwischen Gro&szlig;- und Kleinschreibung: 'Geheim123', 'geheim123' und 'GEHEIM123' sind also verschiedene Dinge.) </p>
<p>Wenn Du Benutzernamen und Passwort eingegeben hast, kommst entweder gleich zur {$ubbt_lang['FORUM_IND']}, wo alle Foren gelistet sind, oder zuerst auf Deine pers&ouml;nliche {$ubbt_lang['CONTROL_PANEL']}. In letzterem Fall kannst Dann dort private Messages lesen und Deine Einstellungen machen. Von dort - wie &uuml;berall im Forum - kommst Du auf die {$ubbt_lang['FORUM_IND']}, in dem Du auf den stets oben im Menu angezeigten Link klickst.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"password\"></a>Ich habe mein Passwort vergessen.</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Wenn Du Dein Passwort vergessen hast, kein Problem. Du kannst Dir sehr einfach ein neues tempor&auml;res Passwort erstellen lassen, welches Dir dann gemailt wird. Alles was Du tun mu&szlig;t ist zur 'Login' Seite zu gehen, Deinen Benutzernamen einzutippen und den Link zu klicken 'Ich habe mein Passwort vergessen'. Dir wird dann ein Passwort an die eMail-Adresse geschickt, welche Du beim Registrieren hinterlegt hast. 
<p>Das ist sicher, weil das Passwort ja nur an den urspr&uuml;nglichen Besitzer des Accounts gelangen kann. Solange nur Du selbst Zugang zu Deinem eMail-Konto hat, gibt es keine M&ouml;glichkeit f&uuml;r andere, Dein Passwort mit diesem Feature zu stehlen. Damit ist das Forum so sicher wie Dein Mail-Konto.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"help\"></a>HILFE! - Einloggen klappt einfach nicht!</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Diese Checkliste hilft Dir, Dich bei Problemen doch noch erfolgreich einzuloggen:
<ol>
<li>Stelle sicher, da&szlig; Du den korrekten von Dir angemeldeten Benutzernamen eingibst. Beachte  au&szlig;erdem, da&szlig; das Passwort zwischen Gro&szlig;- und Kleinschreibung unterscheidet: 'Geheim123', 'geheim123' und 'GEHEIM123' sind also verschiedene Dinge.<br><br></li>
<li>Stelle sicher, da&szlig; Dein Browser 'Cookies' benutzt. (Was und wo Cookies sind, kannst Du <a href=\"cookies_d.html\">hier</a> nachlesen). Falls 'Cookies' aktiviert sind, &uuml;berpr&uuml;fe dann m&ouml;gliche Beschr&auml;nkungen bei den 'Sicherheitseinstellungen des Browsers: 'High Level' Security lehnt automatisch Cookies ab. Um aber wichtige Einstellungen des Forums nutzen zu k&ouml;nnen, m&uuml;ssen Cookies aktiviert sein.<br><br></li>
<li>Wenn man eingeloggt ist, noch mal komplett ausloggen und wieder einloggen. Das l&ouml;scht evtl. fr&uuml;her gesetzte und vielleicht besch&auml;digte Cookies. Wenn das nicht geht kann man die Cookies auch direkt &uuml;ber Windows l&ouml;schen. (Wo die sind und wie das geht, kannst Du 
<a href='\"cookies_d.html\"'>hier</a> nachlesen). <br><br></li>
<li>Nach dem Einloggen im Browser den &quot;Refresh&quot; Button (auch &quot;Reload&quot; oder &quot;Neu Laden&quot; genannt) dr&uuml;cken.<br><br></li>
<li>Wenn die Probleme weiterbestehen, gehe noch mal zur 'Login'-Seite. Gebe den Benutzernamen ein und klicke den Link 'Ich habe mein Passwort vergessen' Knopf. Ein neues tempor&auml;res Passwort wird anstelle des alten generiert und an die email-Adresse geschickt, die Du bei Einrichtung Deines Accounts eingetragen hast. Das Feature geht nat&uuml;rlich nur, wenn der richtige Benutzernamen eingetragen ist und die damals eingetragene eMail-Adresse stimmt. Wenn Du Deinen Benutzernamen nicht mehr genau erinnerst, dann kannst Du zur Not auch eine eMail (aber unbedingt von dem eMail-Konto aus, die Du im Forum hinterlegt hast) an den <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a> schicken. Und wir werden versuchen Dir zu helfen.<br><br></li>
<li>Geht es immer noch nicht? Dann wissen wir spontan auch nicht weiter. Du hast es geschafft. Du bist der/die Erste. :) Bitte maile dem <a href='\"mailto:{$config['emailaddy']}\"'>{$config['emailaddy']}</a> und beschreibe uns das Problem und was Du gemacht hast.</li>



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"changepassword\"></a>Wie &auml;ndere ich mein Passwort?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Du kannst Dein Passwort jederzeit &auml;ndern. Alles was Du tun mu&szlig;t ist im Menu  {$ubbt_lang['CONTROL_PANEL']} zu klicken. Dann unten auf der Seite auf 'Pers&ouml;nliche Informationen, eMail, Passwort, usw... ' klicken. Um das Passwort zu &auml;ndern, dann das Passwort neu eintippen und in das darunterliegende Feld zur &Uuml;berpr&uuml;fung noch einmal. Dann unten auf der Seite den Button 'Abschicken' klicken. Fertig.
<p>(Tip: Das Passwort unterscheidet zwischen Gro&amp;szlig;- und Kleinschreibung: 'Geheim123', 'geheim123' und 'GEHEIM123' sind also verschiedene Dinge. W&auml;hle am besten ein Passwort, was nicht so leicht zu erraten ist.)



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editprof\"></a>Wie bearbeite ich mein 'Profil'?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Im 'Profil' legst Du fest, was anderen Benutzern, die auf Deinen Namen klicken, &uuml;ber Dich angezeigt wird. Hier kannst Du Dich kurz vorstellen. Klicke oben im Menu auf den Link {$ubbt_lang['CONTROL_PANEL']}. Unten auf der dann erscheinenden Seite findest Du die Links zu Deinen Einstellungen, wo auch die Angaben zu Deinem Profil bearbeitet werden k&ouml;nnen.


 
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"limit\"></a>Gibt es ein Limit der Gr&ouml;&szlig;e eines Bildes in der Signatur?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
(Falls das Feature 'Bilder in der Signatur' erlaubt ist:) <br>
Wir bitten Dich die Bilder in der Signatur klein zu halten, also weniger als 100*300 Pixel und kleiner als 20 K. Dadurch wird das Laden des Forums f&uuml;r alle User schnell gehen.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titles\"></a>Was bedeuten die 'Titel' und wie kriegt man einen?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Jeder hat im Forum einen 'Titel'. Der wird Dir bereits aufgefallen sein, weil er beim User in jeder Nachricht steht. Titel werden einerseits automatisch nach Anzahl der geposteten Nachrichten vergeben. (Das System wird allerdings nicht verraten - und versuche bitte der Versuchung zu widerstehen, durch sinnloses Posten den Titel 'erh&ouml;hen' zu wollen. Das nervt nur und macht niemandem Spa&szlig;.)
<p>Und manche Titel k&ouml;nnen theoretisch auch vom Administrator offiziell bei Bedarf vergeben werden, z.B. an offizielle Mitarbeiter oder sonstige VIPs.
";
// Hack out - soll ja eine Ueberraschung sein, und gegen die Versuchung durch sinnloses Posten "upzugraden":
//  <p>Here is the list of the standard titles and # of posts to achieve them:
//  <p>
//  <pre>";
//  readfile("{$config['path']}/filters/usertitles");
//
//  echo "
//  </pre>
//  <br>
echo "



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"buttons\"></a>Was steckt hinter den ganzen Kn&ouml;pfen</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\"> 
Die Kn&ouml;pfe werden zur Navigation und Darstellung der Nachrichten verwendet. Je nach angew&auml;hlter Seite kann es hierbei zu Abweichungen in der Bedeutung kommen.
<p>In der Darstellung aller Threads ('Threads' sind die Gruppe von Nachrichten):
<ul>
<li>Der<b> Pfeil nach links</b> und <b>rechts</b> bringt einen zur vorigen oder n&auml;chsten Nachricht.
<li>Der <b>Pfeil nach oben</b> bringt einen zur {$ubbt_lang['FORUM_IND']}, der &Uuml;bersicht aller vorhandenen Foren.
<li>Der \&quot;<b>Neue schreiben</b>\&quot;-Knopf l&auml;&szlig;t einen eine neue Nachricht auf diesem Board schreiben.
<li>Die \&quot;<b>+</b>\&quot; und \&quot;<b>-</b>\&quot;-Kn&ouml;pfe erlauben die &Auml;nderung der Darstellung zwischen eingeklapptem und ausgeklapptem Zustand. Der ausgeklappte Zustand zeigt die Betreffs aller Nachrichten und ihrer Antworten in einer gestaffelten Darstellung. Der eingeklappte Zustand zeigt nur den Betreff der Haupt-Nachricht und die Zahl der Antworten darauf.
</ul>
<p>Bei der Ansicht einzelnen Nachrichten:</p>
<ul>
<li>Der <b>Pfeil nach links</b> und <b>rechts</b> bringt einen zum vorigen oder n&auml;chsten Thread (Gruppe von Nachrichten).
<li>Der <b>Pfeil nach oben</b> bringt einen zur &Uuml;bersicht aller Nachrichten in diesem Forum.
<li>Der \&quot;<b>Flach</b>\&quot;-Knopf erlaubt die Nachricht sowie alle dazu geschriebenen Antworten (falls diese existieren) auf einer Seite anzuzeigen.
<li>Der \&quot;<b>Gestaffelt</b>\&quot;-Knopf erlaubt die Nachricht zusammen mit einer gestaffelten &Uuml;bersicht der Betreffs aller Antworten anzuzeigen.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\"></a>Wieso sind 'Betreff', 'Absender' usw. anklickbar?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Du kannst so durch Anklicken die Sortierreihenfolge der dargestellten Nachrichten &auml;ndern. Durch Klicken auf 'Betreff' werden alle Nachrichten in umgekehrt-alphabetischer Reihenfolge gelistet.<p>Wenn Du auf 'Betreff' ein weiteres Mal klickst, wird die Liste in alphabetischer Reihenfolge dargestellt. F&uuml;r 'Absender' und die &uuml;brigen Buttons gilt dasselbe.



<!-- HACK - ganzer Block: --> 
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"setread\"></a>Ich will alle Nachrichten als'gelesen' markieren - die <b>NEU</b>-Flags sollen weg.</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Nach dem Klick oben im Menu auf {$ubbt_lang['CONTROL_PANEL']} auf der dann folgenden Seite dann ganz unten auf den Link 'Markiere alle Nchrichten als gelesen' klicken und den Anweisungen folgen.  



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"flash\"></a>Was bedeutet das blinkende 'Brief'-Symbol oben in der Menu-Zeile?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Das bedeutet: Du hast neue, noch ungelesene private Nachrichten. Klicke auf das Bild, um sie direkt lesen zu k&ouml;nnen.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"letters\"></a>Was bedeuten die Buchstaben bei der Liste der empfangenden privaten Nachricht?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
'{$ubbt_lang['TEXT_NEW']}' bedeutet eine noch ungelesene Nachricht.<br>
'{$ubbt_lang['TEXT_REPLIED']}' bedeutet, da&szlig; die Nachricht bereits beantwortet wurde.<br>
Kein Zeichen bedeutet, da&szlig; die Nachricht zwar bereits gelesen, aber noch nicht beantwortet wurde.
 <p>&nbsp;



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"moreposts\"></a>Ich will mehr (oder weniger) Nachrichten je Seite sehen.</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Du kannst die Anzahl der Nachrichten je Seite in Deinem Profil einstellen. Sie k&ouml;nnen diese auf eine beliebige Zahl zwischen 1 und 99 Nachrichten je Seite konfigurieren. Standardm&auml;&szlig;ig wird eine Darstellung von {$theme['postsperpage']} Nachrichten je Seite verwendet.

  

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"editdisp\"></a>Sollte ich die optische Anzeige im Forum &auml;ndern?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Ja, Du wirst sonst einige sch&ouml;ne M&ouml;glichkeiten verpassen, die dieses Forum bietet. Es gibt vielf&auml;ltige Aspekte, wie die Forum-Anzeige individuell eingestellt werden kann. Die Vorgehensweise ist &auml;hnlich wie bei den 'Pers&ouml;nlichen Einstellungen', aber nach dem Klick oben im Menu auf {$ubbt_lang['CONTROL_PANEL']} auf der dann folgenden Seite dann ganz unten auf den Link 'Darstellungs-Optionenen...' klicken.
<p>Du kannst eine Sprache f&uuml;r die Forum-Software w&auml;hlen, ein Stylesheet aussuchen was Dir am besten gef&auml;llt und den gesamten 'Look and 'Feel'. Au&szlig;erdem, wieviel Nachrichten pro Seite angezeigt werden sollen, ob Bilder der Usern bei deren Nachrichten gezeigt werden sollen, und vieles mehr. Wenn diese Einstellungen einmal gespeichert sind, 'merkt' das Forum sich diese bei Deinem n&auml;chsten 'Login'. Die Einstellungen k&ouml;nnen jederzeit wieder ge&auml;ndert werden.
 


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"titlestring\"></a>Was ist das f&uuml;r ein Text, der in Nachrichten immer neben meinem Benutzernamen angezeigt wird?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Das ist ein vom System generierter 'Titel'. Der &auml;ndert sich je nach Anzahl der geposteten Nachrichten. Und gibt keine Auskunft &uuml;ber die Qualit&auml;t Deiner Beitr&auml;ge, sondern nur die Quantit&auml;t.
<p>(Tip: Also bitte nicht der Versuchung erliegen, durch Posten von sinnlosen 'cool'-, 'finde ich auch'- und sonstiger 'blabla'-News die Posting-Zahl hochzutreiben. Das w&uuml;rde nur alle nerven und Dich nicht gerade beliebt machen.)



  <p>&nbsp; 
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"answer\"></a>Wie finde ich Nachrichten, in denen meine Frage vielleicht schon beantwortet wurde?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Eines der Hauptvorteile eines Forums ist die M&ouml;glichkeit, oft gestellte Fragen - zum Nutzen aller - nur einmal zu beantworten. Bevor Du eine neue Nachricht schreibst, ist es sinnvoll, nachzusehen, ob die Frage vielleicht schon fr&uuml;her einmal gestellt - und beantwortet wurde. Je allgemeiner Deine Frage ist, desto besser stehen die Chancen daf&uuml;r.</p>
<p>Empfehlenswert ist eine umfangreiche Suche &uuml;ber den Link 'Suche' oben in der Menuzeile. Dort gibt es vielf&auml;ltige M&ouml;glichkeiten die Suche auszuweiten oder einzuschr&auml;nken, je nach Anzahl der angezeigten Ergebnisse.</p>
<p>F&uuml;r eine Schnellsuche kannst Du auch direkt das Suchwort oben bei 'Forum-Suche' eintippen. Dann erfolgt die Suche aber nur in dem Forum-Bereich, wo Du gerade bist, und das w&auml;re vor allem praktisch, etwas dort auf die Schnelle wiederzufinden.



<p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"subject\"></a>Warum ist der Titel einer Nachricht ('Betreff') so wichtig?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Die erste Nachricht eines Threads (Gruppe von Nachrichten) legt fest, um welches Thema es darin - und auch in allen Antworten dieses Threads - inhaltlich gehen wird. </p>
<p>Auf den Punkt gebracht werden sollte dieses Thema durch deren 'Betreff'. Daher ist es wichtig, den Betreff so klar und genau wie m&ouml;glich zu formulieren. </p>
<p>Zum Beispiel ist es besser zu schreiben 'Wie kriege ich Smilies in meine Nachricht', als so etwas Allgemeines wie 'Bin neu, brauche Hilfe'. Es ist nicht nur wahrscheinlicher, da&szlig; jemand Dir antworten wird, es ist auch leichter, sp&auml;ter die Nachricht - und die Antworten darauf - wiederzufinden.</p>
<p>Tip: Sobald es eine erste Antwort gibt, kann der Betreff der Ursprungsnachricht nachtr&auml;glich nicht mehr ge&auml;ndert werden. Daher den Betreff von vornherein sorgf&auml;ltig w&auml;hlen.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"whitespace\"></a>Wie mu&szlig; ich meine Nachrichten schreiben, da&szlig; ich Leerr&auml;ume zwischen meine Abs&auml;tze bekomme?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
An der Stelle, wo vor dem n&auml;chsten Absatz eine Leerzeile erscheinen soll, die 'Return'-Taste ZWEIMAL dr&uuml;cken. Wenn Du Dir eine Voransicht Deiner Nachricht anzeigen l&auml;sst, hast Du die M&ouml;glichkeit, Deine Nachricht weiter ver&auml;ndern und gestalten, bevor sie endg&uuml;ltig im Forum erscheint.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"spellchecker\"></a>Gibt es eine Rechtschreibkorrektur?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Nein. Wenn Du sicher sein willst, alles richtig geschrieben zu haben, mu&szlig;t Du Deine Nachricht in einem Texteditor schreiben, dort korrigieren lassen, und dann den Text dort rauskopieren und in die Text-Box im Forum einf&uuml;gen.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"mistake\"></a>Was passiert, wenn ich mit meiner Nachricht einen Fehler mache?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Benutzer k&ouml;nnen ihre eigenen Nachrichten bis {$config['edittime']} Stunden noch nachtr&auml;glich bearbeiten.<br>Tip: Wenn der Inhalt der Nachricht eine gr&ouml;&szlig;ere &Auml;nderung erf&auml;hrt, sollte die Nachricht als 'bearbeitet' markiert werden (eine entsprechende Checkbox findest Du auf der '&Auml;ndern'-Seite), damit die anderen Leser dar&uuml;ber informiert werden. Handelt es sich jedoch nur um eine &Auml;nderung nur kosmetischer Natur, dann besser nicht.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"wrongforum\"></a>Was mu&szlig; ich machen, wenn ich eine Nachricht versehentlich ins falsche Forum setze?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Benachrichtige den Forum-Administrator, er/sie wird die Nachricht f&uuml;r Dich verschieben.





  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\"></a>Wie kann ich HTML (oder 'Markup'-Tags) in meinen Nachrichten benutzen? <img src=\"{$config['images']}/graemlins/cool.gif\"></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Es gibt 2 Wege, wie ein Board konfigueriert sein kann. (Ob dies &&uuml;uml;berhaupt gestattet ist und welcher dieser Wege dann gew&auml;hlt wird, legt der Administrator pro Board fest.)
  <br>Wenn <b>HTML</b> angeschaltet ist, erscheint 'HTML erlaubt' und Sie k&ouml;nnen regul&auml;ren HTML-Code in Ihrer Nachricht benutzen.
  <br>Falls <b>Markierungsmarken</b> erlaubt sind, erscheint 'Markierung erlaubt'. Die folgenden Markierungen sind dann erlaubt:
  <br><br>
    ";
//    
// (HACK) MARKUPS-TABLE Begin....  
// Not all markups make sense, and you want to keep that code hidden. 
// Not needed markups are commented out. To use them, remove the HTML comment tags: <!--   -->
// by Carl Erling, comments to: info@chrom.de 
 
echo "
<table border=2 cellpadding=2 cellspacing=0 width=390>
	<tr><td>
	<table border=0 cellpadding=2 cellspacing=0 width=390>
		<tr>
			<td width=140 valign=\"top\"><b>Code:</b></td>
			<td width=200 valign=\"top\"><b>Ergebnis:</b></td>
			<td width=100 valign=\"top\"><b>K&uuml;rzel:</b></td>
		</tr>
		<tr>
			<td colspan=3><hr></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_BLUSH']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/blush.gif\"></td>
			<td width=100 valign=\"top\"><b>:o</b></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_COOL']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/cool.gif\"></td>
			<td width=100 valign=\"top\"></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b><b>[{$ubbt_lang['ICON_CRAZY']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/crazy.gif\"></td>
			<td width=100 valign=\"top\"></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b><b>[{$ubbt_lang['ICON_FROWN']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/frown.gif\"></td>
			<td width=100 valign=\"top\"><b>:(</b></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_LAUGH']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/laugh.gif\"></td>
			<td width=100 valign=\"top\"><b>:D</b></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_MAD']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/mad.gif\"></td>
			<td width=100 valign=\"top\"></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_SHOCKED']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/shocked.gif\"></td>
			<td width=100 valign=\"top\"></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_SMILE']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/smile.gif\"></td>
			<td width=100 valign=\"top\"><b>:)</b></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_TONGUE']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/tongue.gif\"></td>
			<td width=100 valign=\"top\"><b>:p</b></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['ICON_WINK']}]</b></font></td>
			<td width=200 valign=\"top\"><img src=\"{$config['images']}/graemlins/wink.gif\"></td>
			<td width=100 valign=\"top\"><b>;)</b></td>
		</tr>
		<tr>
			<td colspan=3><hr></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><b>[b]</b>Text<b>[/b]</b></td>
			<td width=200 valign=\"top\">Text in <b>fett</b></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><b>[i]</b>Text<b>[/i]</b></td>
			<td width=200 valign=\"top\">Text in <i>kursiv</i></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_RED']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_RED']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=red>{$ubbt_lang['COLOR_RED']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_GREEN']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_GREEN']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=green>{$ubbt_lang['COLOR_GREEN']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_BLUE']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_BLUE']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=blue>{$ubbt_lang['COLOR_BLUE']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_ORANGE']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_ORANGE']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=orange>{$ubbt_lang['COLOR_ORANGE']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_YELLOW']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_YELLOW']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=yellow>{$ubbt_lang['COLOR_YELLOW']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_PURPLE']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_PURPLE']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=purple>{$ubbt_lang['COLOR_PURPLE']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>

<!-- dont show black & white:	
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_WHITE']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_WHITE']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=silver>{$ubbt_lang['COLOR_WHITE']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[{$ubbt_lang['COLOR_BLACK']}]</b></font>Text<font class=\"standouttext\"><b>[/{$ubbt_lang['COLOR_BLACK']}]</b></font></td>
			<td width=200 valign=\"top\">Text in <font color=gray>{$ubbt_lang['COLOR_BLACK']}</font></td>
			<td width=100 valign=\"top\"><br></td>
		</tr>		
-->		
		<tr>
			<td colspan=3><hr></td>
		</tr>
	<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\">
			<b>[list]</b><br>
			<b>[*]</b>Zeile 1<br>
			<b>[*]</b>Zeile 2<br>
			<b>[/list]</b>
			</font></td>
			<td width=200 valign=\"top\">= Erstellt eine einger&uuml;ckte Liste.<br><b>[list=A]</b> bzw. <b>[list=1]</b><br>erstellt eine nach ABC oder Zahlen geordnete Liste.</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td colspan=3><hr></td>
		</tr>

		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[quote]</b></font>Text<font class=\"standouttext\"><b>[/quote]</b></font></td>
			<td width=200 valign=\"top\">Diesen Code kann man benutzen, um bei einer Antwort etwas zu zitieren. (F&uuml;gt HTML Blockquote und HR-Tags ein.)</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[pre]</b></font>Text<font class=\"standouttext\"><b>[/pre]</b></font></td>
			<td width=200 valign=\"top\">F&uuml;r vorformatierten Text. (Umschliesst Text mit HTML Pre-tags.)</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
	";
		
	if ($config['allowimages']) { echo"
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[image]</b></font>http://www.test.com/image.jpg<font class=\"standouttext\"><b>[/image]</b></font></td>
			<td width=200 valign=\"top\">F&uuml;gt die Grafik an dieser Image Url als Bild in die Nachricht ein.</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
    "; }

	echo "		
		<tr>
			<td colspan=3><hr></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[email]</b></font>info@email.com<font class=\"standouttext\"><b>[/email]</b></font></td>
			<td width=200 valign=\"top\">Macht diese Email-Adresse anklickbar</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[url]</b></font>http://www.test.com<font class=\"standouttext\"><b>[/url]</b></font></td>
			<td width=200 valign=\"top\">Verwandelt die URL zwischen den Tags in einen Link.</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
		<tr>
			<td width=140 valign=\"top\"><font class=\"standouttext\"><b>[url=</b>http://www.test.com<b>]</b></font>Test Page<font class=\"standouttext\"><b>[/url]</b></font></td>
			<td width=200 valign=\"top\">Macht den Text zwischen den Tags anklickbar, und verweist auf den Link.</td>
			<td width=100 valign=\"top\"><br></td>
		</tr>
	</table>
	</tr>
</table>
";
// (HACK) MARKUPS-TABELLE END....
//  
  echo " 



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"image\"></a>Wie kriege ich ein Bild in eine Nachricht oder Signatur?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

";
if ($config['allowimages']) { echo"
Um Deiner Nachricht oder Signatur ein Bild hinzuzuf&uuml;gen, mu&szlig; dieses Bild bereits auf irgendeinem Webserver verf&uuml;gbar sein. Dies kann zum Beispiel Deine eigene Homepage sein. Um dieses Bild nun in der Nachricht zu platzieren, benutze folgenden <a href=\"#html\">Markup</a>-Tag:
<p><b>[image]</b>http://www.url_mit_bild.com/bildname.gif<b>[/image]</b>
<p>Das gleiche kannst Du mit Deiner Signatur machen. Klicke auf den Link {$ubbt_lang['CONTROL_PANEL']} oben im Menu und unten auf der dann folgenden Seite auf den Link 'Pers&ouml;nliche Informationen..'. Suche die 'Signatur'-Eingeabebox und gib die Information mit dem Bild Markup-Code ein, wie oben beschrieben.
<p>Tip: Damit das Forum f&uuml;r jeden schnell l&auml;dt, achte bitte darauf, die Dateugr&ouml;&szlig;e des Bildes unter 35 K zu halten. Der Moderator wird den Link sonst entfernen.
"; }
else { echo"
Dieses Feature ist vom Admin deaktiviert.<br>
Du hast aber die M&ouml;glichkeit ein Bild einer Nachricht als 
'<a href=\"#attach\">Anhang</a>' beizuf&uuml;gen. 
";}
echo" 


 
  <p>&nbsp; 
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"url\"></a>Die URL-Tags (Markup bzw. Html) funktionieren bei mir nicht.</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
Wenn die Tags selbst im Text angezeigt werden, oder zwar ein Link angezeigt wird, dieser aber zu 'http:///' linkt, dann hast Du vermutlich unerw&uuml;nschte Leerzeichen eingegeben. Vermeide jegliche Leerzeichen und alles sollte problemlos funktionieren.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"attach\"></a>Kann ich eine Datei an meine Nachricht anh&auml;ngen?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Falls Du einen Browser benutzt, der zu Mozilla 4+ Kompatibel ist, dann ist die Antwort: 'Ja, Du kannst'. - In der 'Voransicht' der Nachricht kann man dann eine Datei anh&auml;ngen.
    ";
  }
  else {
    echo "Nein.  Dateien anzuh&auml;ngen ist auf diesem Board nicht erlaubt.";
  }

  echo "


 
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\"></a>Wie kann ich eine Umfrage in meine Nachricht einbauen?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  <br><br>
  Eine Umfrage in eine Nachricht einzubauen ist im Prinzip einfach, aber nicht vergessen: Nachrichten, die Umfragen enthalten,
 k&ouml;nnen nachtr&auml;glich nicht ge&auml;ndert, wohl aber wieder gel&ouml;scht werden.<br>
  ";
  if (!$config['allowpolls']) {
    echo "<i>Dieses Feature steht momentan nur Moderatoren und Admins zur Verf&uuml;gung.</i><br>";
  }
  echo " 
  Um eine Umfrage in die Nachricht einzubauen, bitte folgendes Format verwenden:<p>
  <b>[pollstart]</b><br>
  <b>[polltitle=</b>Name der Umfrage<b>]</b><br>
  <b>[polloption=</b>Erste Auswahlm&ouml;glichkeit<b>]</b><br>
  <b>[polloption=</b>Zweite Auswahlm&ouml;glichkeit<b>]</b><br>
  <b>[polloption=</b>und so weiter, beliebig viele sind m&ouml;glich<b>]</b><br>
  <b>[pollstop]</b>



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"rules\"></a>Was sind die Forum-Regeln?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
 Die Regeln wurden auf eine Sonderseite verschoben, bitte dort nachlesen:<br>
<a href=\"rules_d.html\">Die 'Spielregeln' f&uuml;r dieses Forum in Deutsch</a>


 
<!-- HACK out  ...credits + link are shown underneath each page anyway.
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"source\"></a>Kann ich mein eigenes Forum einrichten?</h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Ja, UBBThreads steht unter <a href=\"http://www.infopop.com\">www.infopop.com</a> zur Verf&uuml;gung
HACK ende --> 


  ";
  $html -> close_table();
// -------------
// Send a footer
  $html -> send_footer();
