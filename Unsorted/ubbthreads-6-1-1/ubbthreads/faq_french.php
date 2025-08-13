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
  $html -> send_header("FAQ (Foire aux Questions)",$Cat,0,$user);
  $html -> table_header("FAQ (Foire aux Questions)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  Vous trouverez, ci-dessous, une liste de questions fréquemment posées. Vous pouvez cliquer sur n'importe laquelle et de recevoir de l'aide sur le sujet désiré ou dont vous avez un problème. Si vous savez toute autre chose qui devrait être adressé sur cette page s'il vous plaît envoyez vos suggestions à <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Est-ce que je peux attacher un fichier à mon message?</a><br />
  <a href=\"#html\">Est-ce que je peux utiliser du HTML dans mes messages?</a><br />
  <a href=\"#source\">Est-ce que je peux utiliser mon propre forum?</a><br />
  <a href=\"#cookies\">Est-ce que je dois accepter les biscuits (cookies)?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Je veux voir plus (ou moins) de messages par page.</a><br />
  <a href=\"#buttons\">Qu'est-ce qu'il y a avec tous les boutons?</a><br />
  <a href=\"#sortorder\">Pourquoi est-ce que Sujet, Expéditeur et Envoyer le sont cliquables?</a><br />
  <a href=\"#email\">Pourquoi demandez-vous deux adresses email?</a><br />
  <a href=\"#register\">Pourquoi est-ce que je devrais enregistrer un nom d'usager?</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=0 border=0 width=100% class=\"darktable\">
  <tr><td>
  <a name=\"attach\"><h3>Est-ce que je peux attacher un fichier à mon message?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Si vous avez un fureteur qui est compatible à Mozilla 4+ alors la réponse est oui, vous le pouvez. Lorsque vous visionnez votre message avant de l'envoyer, vous aurez l'occasion d'attacher un fichier à votre message.
    ";
  }
  else {
    echo "Non. L'attachement de fichier a été désactivé pour ces groupes de discussion.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"html\"><h3>Est-ce que je peux utiliser du HTML dans mes messages?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Il y a 2 façons que ceci peut être configuré sur une base individuelle de groupe de discussion. Si le HTML est activé puis vous verrez que <b>HTML est Activé</b> est affiché et vous pouvez utiliser le HTML normal dans vos messages. Si les marqueurs sont activés vous verrez que <b>Marqueur est Désactivé</b> est affiché. Les étiquettes suivantes sont disponibles pour votre usage si les marqueurs sont permis:
  <br /><br />
  <font class=standouttext>
  [b]
  </font>
  texte
  <font class=standouttext>
  [/b]
  </font>
         = Rend le texte donné en gras.<br />

  <font class=standouttext>
  [email]
  </font>
  joe\@blow.com
  <font class=standouttext>
  [/email] 
  </font>
  = Rend l'adresse email donnée clickable.<br />

  <font class=standouttext>
  [i]
  </font>
  texte
  <font class=standouttext>
  [/i]
  </font>
         = Rend le texte donné en italique.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=standouttext>";
    echo"[image]</font>";
    echo"url";
    echo "<font class=standouttext>";
    echo "[/image]</font>  = Met le URL donné dans une étiquette img src.<br />";
  }

  echo "

  <font class=standouttext>
  [pre]
  </font>
  texte
  <font class=standouttext>
  [/pre]
  </font>
   = Entoure le texte donné avec des étiquettes pre.<br />

  <font class=standouttext>
  [citation]
  </font>
  texte
  <font class=standouttext>
  [/citation] 
  </font>
  = Entoure le texte donné avec des blockquote et hr. Cette étiquette de marqueurs est utilisée pour citer une réponse.<br />

  <font class=standouttext>
  [url]
  </font>
  lien
  <font class=standouttext>
  [/url]</font>    = Transforme le URL donné en un lien.<br />


  <font class=standouttext>
  [rougeur]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=standouttext>
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=standouttext>
  [fou]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=standouttext>
  [froncement]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=standouttext>
  [rire]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=standouttext>
  [fache]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=standouttext>
  [choquer]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=standouttext>
  [sourire]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=standouttext>
  [langue]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=standouttext>
  [clignement]
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
  <a name=\"source\"><h3>Est-ce que je peux utiliser mon propre forum?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Oui, UBB.threads est disponible à <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"cookies\"><h3>Est-ce que je dois accepter les biscuits?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Oui. Les biscuits (cookies) sont employés pour dépister votre nom d'usager/mot de passe et quels sont les messages que vous avez lus au courant de la session actuelle. En n'acceptant pas les biscuits quelques fonctions ne fonctionneront pas correctement.

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
  <a name=\"moreposts\"><h3>Je veux voir plus (ou moins) de messages par page.</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Vous pouvez changer le nombre de messages à afficher par page en éditant votre profil. Vous pouvez configurer ceci pour afficher entre 1 et 99 messages par page. Lorsque vous connecter pour la première fois, ceci est configuré à {$theme['postsperpage']} messages par page.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <a name=\"buttons\"><h3>Qu'est-ce qu'il y a avec tous les boutons?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Les boutons sont utilisés pour la navigation et des l'affichage des messages. Selon l'écran que vous vous trouver, certains peuvent avoir des objectifs différents.
  <p>Lors de l'affichage de la liste des discussions:
  <br />- Les flèches de gauches et de droites vous porteront à la page précédente ou suivante des messages.
  <br />- La flèche du haut vous portera à l'index de tous les forums  disponibles.
  <br />- Le bouton \" Écrire un nouveau message\" vous laissera écrire un nouveau message dans le groupe de discussion.
  <br />- Les boutons + et - vous permettront de basculer entre les discussions étendues et effondrées. Les discussions de type étendues afficheront les sujets de tous les messages et réponses dans un format de discussion. Les discussions de type effondrées afficheront le sujet du message principal avec le nombre de réponses à ce message.
  <p>Lors de l'affichage des discussions individuelles:
  <br />- Les flèches de gauches et de droites vous porteront à la discussion précédente ou suivante.
  <br />- La flèche du haut vous portera à la liste de tous les messages sur cette page.
  <br />- Le bouton \"Mode plat\" vous permet de visualiser la discussion entière sur une seule page s'il y a des réponses au message initial.
  <br />- Le bouton \"Mode discussion\" vous permet de visualiser les messages courants veulent avec les autres messages dans cette discussion affichée au-dessous dans un format de discussion.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"sortorder\"><h3>Pourquoi est-ce que Sujet, Expéditeur et Envoyer le sont cliquables?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Vous pouvez cliquer sur n'importe lequel pour changer l'ordre dans laquelle les messages sont triés et affichés. Si vous cliquez sur le Sujet une fois, il affiche les messages par Sujet dans l'ordre alphabétique renversé. Si vous le cliquez encore sur le Sujet, il affiche les messages par Sujet dans l'ordre alphabétique. Expéditeur et Envoyer le fonctionnent similairement.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"email\"><h3>Pourquoi demandez-vous deux adresses email?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  La vraie adresse email est utilisée pour les notifications par email, les abonnements des forums et de vous envoyer par email votre mot de passe. L'autre est ce que les autres usagers voient lorsqu'ils visualisent votre profil. Nous réalisons que certains ne veulent pas que tout le monde sache leur adresse email normale, mais nous devons le savoir au cas où vous voudriez vous abonner à un forum ou si vous voulez recevoir les réponses par email. Pour cette raison vous pouvez nous donner votre vraie adresse email que seulement nous verrons et vous pouvez fournir une adresse différente pour le grand public. Certaines personnes aiment mettre quelque chose dans le style scream\@no.spam.domain.org. De cette façon les personnes peuvent encore figurer quel est votre vraie adresse email, mais les agents de spamming ne peuvent pas simplement analyser la page et vous spammer.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <a name=\"register\"><h3>Pourquoi est-ce que je devrais enregistrer un nom d'usager?</h3></a>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  En enregistrant un nom d'usager, vous pourrez éditer votre profil et vos préférences. Vous allez en profiter le plus ici si vous changez votre profil en fonction de vos goûts personnels. Il y a plusieurs options dans votre profil pour rendre votre expérience ici plus agréable, alors prenez quelques moments pour essayer les diverses configurations. Également, seulement les personnes avec des noms d'usager enregistrés peuvent tirer profit de l'avantage de l'option \"Nouveaux Messages\" lors de chaque visite.

  </td></tr></table  </td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
