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
   $html -> send_header("FAQ (Raspunsuri la intrebarile cele mai frecvente)",$Cat,0,$user);
   $html -> table_header("FAQ (Raspunsuri la intrebarile cele mai frecvente)");

  $phpurl = $config['phpurl'];

  $html -> open_table();
  echo " 
  <tr class=\"darktable\">
  <td>
  Aceasta este o lista cu cele mai frecvente intrebari care apar.<br />Puteti da click pe oricare dintre acestea pentru a obtine raspunsul la problema respectiva.<br />Daca aveti cunostinta de ceva care ar fi necesar sa apara aici, va rugam sa trimiteti sugestiile <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Pot sa atasez un fisier mesajului meu?</a><br />
  <a href=\"#html\">Pot sa folosesc HTML in mesaje?</a><br />
  <a href=\"#source\">Pot sa-mi fac un forum propriu?</a><br />
  <a href=\"#cookies\">Trebuie sa accept cookies-uri?</a><br />
  <a href=\"#polls\">Cum introduc un chestionar intr-un mesaj?</a><br />
  <a href=\"#moreposts\">Vreau sa vad mai multe (putine) mesaje pe pagina.</a><br />
  <a href=\"#buttons\">Ce-i cu toate butoanele alea?</a><br />
  <a href=\"#sortorder\">De ce pot sa click-ez pe Subiect, Autor si Data?</a><br />
  <a href=\"#email\">De ce trebuie 2 adrese de email?</a><br />
  <a href=\"#register\">De ce trebuie sa-mi inregistrez un nume?</a>
  <p>
  ";
  $html -> close_table();

  echo "
  <p>&nbsp;
  ";

  $html -> open_table();  

  echo "
  <tr class=\"darktable\"><td>
  <h3><a name=\"attach\">Pot sa atasez un fisier mesajului meu?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Da, daca aveti un browser care este compatibil Mozilla 4+. In ecranul in care vizualizati mesajul inainte de a-l trimite pe server vi se ofera posibilitatea atasarii unui fisier. Tineti seama insa ca fisierul nu trebuie sa depaseasca dimensiunea specificata.
    ";
  }
  else {
    echo "Nu. Atasarea fisierelor nu este permisa pe acest forum.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\">Pot sa folosesc HTML in mesaje?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Exista 2 posibilitati configurabile individual pe fiecare board.<br />Daca HTML-ul este activat, veti vedea <b>HTML este Activ</b> si veti putea folosi HTML in mesaje.<br />Daca UBBCode-ul este activat, veti vedea <b>UBBCode este Activ</b> si veti avea la dispozitie urmatoarele tag-uri: 
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  text
  <font class=\"standouttext\">
  [/b]
  </font>
         = Face ca text-ul respectiv sa apara bold.<br />

  <font class=\"standouttext\">
  [email]
  </font>
  foo@bar.com
  <font class=\"standouttext\">
  [/email] 
  </font>
  = Permite trimiterea unui email la adresa respectiva.<br />

  <font class=\"standouttext\">
  [i]
  </font>
  text
  <font class=\"standouttext\">
  [/i]
  </font>
         = Face ca text-ul respectiv sa apara italic.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=\"standouttext\">";
    echo"[image]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/image]</font>  = Afiseaza imaginea aflata la url-ul respectiv.<br />";
  }

  echo "

  <font class=\"standouttext\">
  [pre]
  </font>
  text
  <font class=\"standouttext\">
  [/pre]
  </font>
   = Pune textul respectiv intre tag-uri PRE.<br />

  <font class=\"standouttext\">
  [citat]
  </font>
  text
  <font class=\"standouttext\">
  [/citat] 
  </font>
  = Pune textul respectiv intre tag-uri HR si BLOCKQUOTE. Acest tag se foloseste pentru a introduce un citat din alt mesaj.<br />

  <font class=\"standouttext\">
  [url]
  </font>
  link
  <font class=\"standouttext\">
  [/url]</font>    = Transforma url-ul respectiv intr-un link.<br />

  <font class=\"standouttext\">
  [url=link]
  </font>
  text
  <font class=\"standouttext\">
  [/url]</font>    = Transforma textul respectiv intr-un link care 'link'.<br />


  <font class=\"standouttext\">
  [imbujorat]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=\"standouttext\">
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=\"standouttext\">
  [crazy]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=\"standouttext\">
  [trist]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=\"standouttext\">
  [ris]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=\"standouttext\">
  [suparat]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [socat]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=\"standouttext\">
  [zimbet]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=\"standouttext\">
  [obraznic]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=\"standouttext\">
  [smecher]
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
  <a name=\"source\"><h3>Pot sa-mi fac un forum propriu?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Da, UBB.threads este disponibil la <a href=\"http://www.infopop.com/\">www.infopop.com</a>.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3>Trebuie sa accept cookies-uri?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Da. Cookies-urile pastreaza informatii despre nume/parola si despre mesajele citite in sesiunea curenta.<br />Daca nu le acceptati, unele facilitati nu vor functiona corespunzator.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"polls\">Cum introduc un chestionar intr-un mesaj?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Folosirea chestionarelor este simpla dar trebuie avut in vedere faptul ca mesajele respective nu pot fi editate (dar se pot sterge).<br />
  ";
  if ($config['allowpolls'] == "off") {
    echo "<i>(Aceasta facilitate este disponibila doar moderatorilor si administratorilor.)</i><br />";
  }
  echo " 
  Pentru a adauga un chestionar folositi urmatorul format:<p>
  [pollstart]<br />
  [polltitle=Numele chestionarului]<br />
  [polloption=Prima optiune]<br />
  [polloption=A doua optiune]<br />
  .<br />.<br />
  [polloption=Optiunea N]<br />
  [pollstop]

                
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"moreposts\"><h3>Vreau sa vad mai multe (putine) mesaje pe pagina.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Numarul de mesaje afisate pe pagina poate fi schimbat prin editarea profilului.<br />Acest numar poate sa fie oriunde in intervalul 1-99. Valoarea implicita este de {$theme['postsperpage']} mesaje pe pagina.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>Ce-i cu toate butoanele alea?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Butoanele folosite pentru navigatie si afisarea mesajelor. In functie de ecranul in care va aflati functionalitatea lor poate sa difere.
  <p>Cind vizualizati lista threadurilor dintr-un forum:
  <br />- Butoanele <img src=\"{$config['images']}/previous.gif\"> si <img src=\"{$config['images']}/next.gif\"> va trimit la pagina anterioara/urmatoare de mesaje.
  <br />- Butonul <img src=\"{$config['images']}/all.gif\"> va trimite la un index cu toate forumurile.
  <br />- Butonul <img src=\"{$config['images']}/newpost.gif\"> va permite sa adaugati un mesaj pe acel board.
  <br />- Butoanele <img src=\"{$config['images']}/expand.gif\" > si <img src=\"{$config['images']}/collapse.gif\"> schimba intre threaduri expandate/colapsate. Threadurile expandate arata subiectul tuturor mesajelor si raspunsurile la acestea. Threadurile colapsate arata doar subiectul mesajului initial, impreuna cu numarul de raspunsuri la acesta.

  <p>Cind vizualizati un anumit thread:
  <br />- Butoanele <img src=\"{$config['images']}/previous.gif\"> si <img src=\"{$config['images']}/next.gif\"> va trimit la threadul anterior/urmator.
  <br />- Butonul <img src=\"{$config['images']}/all.gif\"> va trimite la lista tuturor mesajelor de pe acea pagina.
  <br />- Butonul <img src=\"{$config['images']}/flat.gif\"> va permite sa vizualizati tot threadul pe o pagina -- daca exista raspunsuri la mesajul original.
  <br />- Butonul <img src=\"{$config['images']}/threaded.gif\"> va permite sa vedeti mesajele in cascada, continutul mesajului selectat aparind in partea de sus a ecranului.
  <br />- Butonul <img src=\"{$config['images']}/reply.gif\"> va permite sa raspundeti unui mesaj.
  <br />- Butonul <img src=\"{$config['images']}/edit.gif\"> va permite editarea mesajului.

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>De ce pot sa apas pe Subiect, Autor si Data?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Puteti da click pe oricare din acestea 3 pentru a schimba ordinea in care sint sortate si afisate mesajele.<br />Primul click pe Subiect va determina afisarea mesajelor dupa Subiect in ordine invers alfabetica.<br />Al doilea click pe Subiect va face ca mesajele sa fie sortate dupa Subiect in ordine alfabetica.<br />Dupa acelasi principiu functioneaza si butoanele pentru Autor si Data. 


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\"> 
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>De ce trebuie 2 adrese de email?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Adresa de email reala este folosita pentru instiintarile prin email, pentru subscrieri si pentru trimiterea parolei. Adresa fictiva este cea pe care utilizatorii o vad cind se uita la profilul dvs. Este de inteles ca anumite persoane nu doresc ca toata lumea sa le cunoasca adresa reala de email, dar programul de forum are nevoie de aceasta pentru a trimite parola initiala, arhiva forumurilor la care sint abonati, precum si diferitele instiintari prin email. Din acest motiv adresa reala este disponibila doar programului, in timp ce a doua este vizibila pentru toata lumea. Pentru a doua adresa unii prefera sa introduca ceva gen scream@no.spam.domain.com pentru a deruta programele specializate de cautat email-uri, ceilalti vizitatori avind posibilitatea identificarii adresei corecte.

  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>De ce trebuie sa-mi inregistrez un nume??</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Dupa inregistrarea contului aveti posibilitatea editarii profilului si setarii diferitelor optiuni. In felul acesta veti avea de cistigat prin alegerea parametrilor optimi care va convin. Exista multe optiuni ce pot fi modificate in cadrul profilului pentru a va face experienta cit mai placuta asa ca incercati sa va faceti timp sa experimentati cu ele.<br />In plus, doar utilizatorii inregistrati beneficiaza de afisarea mesajelor noi la fiecare vizita.

  ";
  $html -> close_table();

// -------------
// Send a footer
   $html -> send_footer();
