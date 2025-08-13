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
  $html -> send_header("FAQ (Domande più frequenti)",$Cat,0,$user);
  $html -> table_header("FAQ (Domande più frequenti)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  Qui sotto troverai una lista delle domande più frequenti.  Cliccando su ognuna otterrai un aiuto sul problema specifico.  Se pensi ci siano domande e problemi che possano essere inseriti in questa pagina invia, per favore, i tuoi commenti a <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Posso inviare file in attach ai miei messaggi?</a><br />
  <a href=\"#html\">Posso utilizzare html nei miei messaggi?</a><br />
  <a href=\"#source\">Posso avere un mio forum?</a><br />
  <a href=\"#cookies\">Devo accettare i cookies?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Voglio vedere più (o meno) messaggi per pagina.</a><br />
  <a href=\"#buttons\">A cosa servono tutti questi bottoni?</a><br />
  <a href=\"#sortorder\">Perchè il Soggetto,Chi invia messaggi e la data in cui sono inviati, sono cliccabili?</a><br />
  <a href=\"#email\">Perchè chiedi due indirizzi email?</a><br />
  <a href=\"#register\">Perchè dovrei registrare un Nome Utente?</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  <a name=\"attach\"><h3>Posso inviare file in attach ai miei messaggi?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      se hai un browser che è compatibile con Mozilla 4+ la risposta è: sì, puoi.  Al momento in cui viene visualizzata l'anteprima del tuo messaggio avrai l'opportunità di attaccare un file (max 10 kBytes).
    ";
  }
  else {
    echo "No.  I file in attachment sono stati disattivati per questi boards.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>Posso utilizzare HTML nei miei messaggi?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  E' possibile attivare l'uso di HTML o UBBCode in un board.  Se HTML è On vedrai <b>HTML è On</b> e puoi utilizzare normale HTML nei tuoi messaggi.  Se UBBCode è on vedrai <b>UBBCode è On</b>.  I seguenti tags ti sono disponibili quando il UBBCode è abilitato:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  text
  <font class=standouttext>
  [/b]
  </font>
         = Rende il testo marcato.<br />

  <font class=standouttext>
  [email]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/email] 
  </font>
  = Rende l'indirizzo email cliccabile.<br />

  <font class=standouttext>
  [i]
  </font>
  text
  <font class=standouttext>
  [/i]
  </font>
         = Rende il testo italico.<br />

  ";
  if ($config['allowimages']) {
    echo "<font class=standouttext>";
    echo "[image]</font>";
    echo "url";
    echo "<font class=standouttext>";
    echo "[/image]</font>  = Mette una data URL in un img src tag.<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  text
  <font class=standouttext>
  [/pre]
  </font>
   = Circonda un dato testo con pre tags.<br />

  <font class=standouttext>
  [citazione]
  </font>
  text
  <font class=standouttext>
  [/quote] 
  </font>
  = Circonda un dato testo con un blockquote e hr's.  Questo UBBCode tag è utilizzare per indicare una risposta.<br />

  <font class=standouttext>
  [url]
  </font>
  link
  <font class=standouttext>
  [/url]</font>    = Trasforma un dato url in un link.<br />


  <font class=standouttext>
  [arrossire]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [interessante]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [pazzo]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [disapprovare]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [ridere]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [mad]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [scioccato]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [sorriso]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [lingua]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [ammiccare]
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
  <a name=\"source\"><h3>Posso creare un mio forum?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Sì, UBB.threads è disponibile a <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3>Devo accettare i cookies?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Sì.  I cookies sono utilizzati per controllare il tuo Nome Utente/Password e per tener traccia dei messaggi che hai letto nell'attuale sessione.  Se non accetti i cookies alcune funzioni potranno presentare problemi.

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
  <a name=\"moreposts\"><h3>Voglio vedere più (o meno) messaggi per pagina.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Editando il tuo profilo puoi variare il numero dei messaggi mostrati per pagina. Puoi scegliere tra 1 e 99 messaggi per pagina.  Quando ti iscrivi per la prima volta vengono mostrati   {$theme['postsperpage']} messaggi per pagina.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\"> 

  <a name=\"buttons\"><h3>A cosa servono tutti questi bottoni?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  I bottoni sono utilizzati per la navigazione e per mostrare i messaggi.  Dipende dalla videata nella quale ti trovi e possono servire a diversi scopi.
  <p>Quando è mostrata una lista di tutti gli argomenti:
  <br />- Le frecce di sinistra e di destra ti porteranno nella pagina precedente o successiva dei messaggi. 
  <br />- La freccia in alto ti porterà all'indice di tutti i forum disponibili.
  <br />- Il bottone \"Nuovo Messaggio\" ti permetterà di inserire un nuovo messaggio in quel board.
  <br />- I bottoni + e - ti permetteranno di passare tra la modalità argomenti estesa e
contratta.  Gli argomenti per esteso ti mostreranno il soggetto di tutti i messaggi e le risposte in formato \"threaded\".  Gli argomenti \"contratti\" ti mostreranno il soggetto del messaggio principale insieme al numero delle risposte a quel determinato messaggio.
  <p>Quando vedi argomenti singoli:
  <br />- Le frecce di destra e di sinistra ti permetteranno di vedere in anteprima l'argomento precedente o successivo.
  <br />- La freccia in alto ti porterà indietro alla lista di tutti i messaggi su quella pagina.
  <br />- Nella \"Modalità Flat\" il bottone ti permette di vedere l'intero argomento su una pagina se ci sono alcune risposte al messaggio originale.
  <br />- Nella  \"Modalità Threaded\" il bottone ti permette di vedere gli attuali messaggi che saranno mostrati insieme e nella parte sottostante in formato \"threaded\".



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>Perchè il soggetto, l'autore del messaggio e la data di invio sono cliccabili</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Puoi cliccare su ognuna per cambiare l'ordine nel quale i messaggi sono classificati e mostrati.  Se clicchi una volta sul Soggetto, ti saranno mostrati i messaggi relativi a quel Soggetto in ordine alfabetico inverso.  Se clicchi ancora una volta i messaggi ti saranno mostrati per Soggetto in ordine alfabetico. L'autore del messaggio e la data/ora in cui sono inviati  funzionano in modo similare.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>Perchè chiedete due indirizzi email?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  L'indirizzo email reale è utilizzato per le notificazioni, le sottoscrizioni e per inviarti la tua password in email.  L'altro è quello che gli altri utenti vedono quando visionano il tuo profilo.  Alcune persone possono infatti desiderare che altri non vedano  il loro normale indirizzo email per evitare di ricevere email non sollecitate (spamming). Tuttavia i gestori del forum hanno bisogno del tuo indirizzo mail reale per iscriverti al forum o per inviarti le risposte direttamente nella tua casella mail.  Per questa ragione puoi tranquillamente segnalarci il tuo reale indirizzo email che soltanto noi vedremo e puoi, eventualmente, segnalarne uno differente per il pubblico in generale. In alternativa puoi dichiarare la tua email pubblica anteponendo no.spam al dominio (per esempio, scream\@no.spam.domain.org).  In questa maniera un umano  può immaginare quale sia il tuo reale indirizzo email, ma gli agenti di spamming (software automatico per l'invio di mail non sollecitate, tipicamente a contenuto promozionale) non possono analizzare la pagina ed inviare messaggi non desiderati.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>Perchè dovrei registrare un Nome Utente?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Registrando un Nome Utente sarai in grado di editare il tuo profilo e le tue preferenze. Ci sono molte opzioni nel profilo che possono rendere la tua esperienza multimediale molto più piacevole, per favore dedica qualche istante per provare le varie opzioni.  Inoltre, solo le persone registrate con un Nome Utente possono trarre vantaggio della speciale opzione \"Nuovi Messaggi\" in ogni visita.  

  </td></tr></table>  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
