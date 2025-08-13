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
   $html -> send_header("PREGUNTAS COMUNES",$Cat,0,$user);
   $html -> table_header("PREGUNTAS COMUNES");

   $phpurl = $config['phpurl'];

  echo " 
   <table cellspacing=\"0\" border=\"0\" width=\"{$theme['tablewidth']}\" align=\"center\" class=\"darktable\">
  <tr>
    <td> Lo siguiente son preguntas que se formulan con cierta frecuencia. Puedes 
      pulsar sobre cualquiera de estos, y recibir ayuda en cuanto al problema 
      que tienes. Si se te ocurre alguna otra cosa que se debiera comentar en 
      este tablero, se ruega contactar con <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>. 
      <p></p>
      <p> 
    </td>
  </tr>
  <tr>
    <td class=\"lighttable\"> <a href=\"#attach\">&iquest;Puedo adjuntar 
      un archivo a mi mensaje?</a><br />
      <a href=\"#html\">&iquest;Puedo usar HTML en mis mensajes?</a><br />
      <a href=\"#source\">&iquest;Puedo dirigir mi propio foro?</a><br />
      <a href=\"#cookies\">&iquest;Es necesario aceptar los cookies?</a><br />
      <a href=\"#polls\">How do I put a poll in my post?</a><br />
      <a href=\"#moreposts\">Me gustar&iacute;a ver m&aacute;s (o menos) mensajes 
      por pagina.</a><br />
      <a href=\"#buttons\">&iquest;De que va eso de tantos botones?</a><br />
      <a href=\"#sortorder\">&iquest;Porqu&eacute; se puede pulsar el &quot;Asunto&quot;, 
      &quot;Autor&quot; y el &quot;Creados el&quot;?</a><br />
      <a href=\"#email\">&iquest;Porqu&eacute; pides dos direcciones de correo electr&oacute;nico?</a><br />
      <a href=\"#register\">&iquest;Que raz&oacute;n tengo para registrar un Nombre 
      de Usuario?</a> 
      <p> 
    </td>
  </tr>
</table>


  <p>&nbsp;
  <table cellspacing=\"0\" border=\"0\" width=\"{$theme['tablewidth']}\" align=\"center\" class=\"darktable\">
  <tr><td>
  <h3><a name=\"attach\">&iquest;Puedo adjuntar un archivo a mi mensaje?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Si tienes un browser que es compatible con Mozilla 4 o superior, entonces si, puedes. Cuando mires a ver como te est&aacute; quedando el mensaje (la vista preliminar) entonces se te dar&aacute; la opci&oacute;n de adjuntar un archivo.
    ";
  }
  else {
    echo "No.  En estos tableros se ha apagado el sistema que permite adjuntar archivos.";
  }

  echo " 
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\"> 
  <h3><a name=\"html\">&iquest;Puedo usar HTML en mis mensajes?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Hay 2 modos en la que esto puede estar configurado dependiendo del tablero. 
  En el caso de que est&eacute; permitido usar html en los mensajes, ver&aacute;s 
  un mensaje que pone <b>HTML est&aacute; encendido</b>, por lo que podr&aacute;s 
  usar c&oacute;digo HTML en tus mensajes. Si Etiquetador est&aacute; encendido ver&aacute;s 
  un mensaje que pone <b>Etiquetador est&aacute; Encendido</b>. Los siguientes c&oacute;digos 
  son los que puedes usar en el caso de que este &uacute;ltimo est&eacute; encendido::
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  text
  <font class=\"standouttext\">
  [/b]
  </font>
         = Convierte la letra en negrita.<br />

  <font class=\"standouttext\">
  [email]
  </font>
  joe\@blow.com
  <font class=\"standouttext\">
  [/email] 
  </font>
  = Hace que la direcci&oacute;n de correo se pueda pulsar.<br />

  <font class=\"standouttext\">
  [i]
  </font>
  texto
  <font class=\"standouttext\">
  [/i]
  </font>
         = Hace que el texto sea cursiva.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=\"standouttext\">";
    echo"[image]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/image]</font>  = Pone el URL indicado en una etiqueta de img src.<br />";
  }

  echo " 

  <font class=\"standouttext\">
  [pre]
  </font>
  texto
  <font class=\"standouttext\">
  [/pre]
  </font>
   = Convierte el texto dentro de la etiqueta en preformateado.<br />

  <font class=\"standouttext\">
  [quote]
  </font>
  texto
  <font class=\"standouttext\">
  [/quote] 
  </font>
  = Convierte el texto en citaci&oacute;n.  Esta etiqueta etiquetador se utiliza para citar en contestaciones.<br />

  <font class=\"standouttext\">
  [url]
  </font>
  Enlace
  <font class=\"standouttext\">
  [/url]</font>    = Convierte la url introducida en un enlace.<br />


  <font class=\"standouttext\">
  [blush]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=\"standouttext\">
  [cool]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=\"standouttext\">
  [crazy]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=\"standouttext\">
  [frown]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=\"standouttext\">
  [laugh]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=\"standouttext\">
  [mad]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [shocked]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=\"standouttext\">
  [smile]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=\"standouttext\">
  [tongue]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=\"standouttext\">
  [wink]
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
  <h3><a name=\"source\">&iquest;Puedo dirigir mi propio foro?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Si, UBB.threads est&aacute; disponible en <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\">&iquest;Es necesario aceptar los cookies?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Si, los cookies (o galletas) son necesarios para mantener tu Nombre de Usuario 
  y contrase&ntilde;a y para saber cuales han sido los mensajes que has le&iacute;do 
  en tu &uacute;ltima sesi&oacute;n. Si tienes el funcionamiento de los cookies 
  desactivado, habr&aacute;n cosas que no funcionar&aacute;n adecuadamente.

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
  echo  " 
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
  <h3><a name=\"moreposts\">Me gustar&iacute;a ver m&aacute;s (o menos) mensajes por pagina.</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Puedes cambiar la cantidad de mensajes a ver por pagina con editar tu perfil 
  de usuario. Puedes ponerlo de modo que salgan cualquier cantidad de mensajes 
  entre 1 y 99 por pagina. Cuando primero te apuntas recibes {$config['postsperpage']} mensajes por pagina.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <h3><a name=\"buttons\">&iquest;De que va eso de tantos botones?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  <p>Los botones son utilizados para la navegaci&oacute;n y la presentaci&oacute;n 
  de los mensajes. Dependiendo de la pantalla en la que te encuentres, puede que 
  tengan diferentes funciones.</p>
<p>Cuando visualizando una lista de todas las conversaciones:<br />
  - Las flechas de izquierda y derecha te llevar&aacute;n a la p&aacute;gina anterior 
  o la siguiente p&aacute;gina de mensajes.<br />
  - La flecha hacia arriba te llevar&aacute; al &iacute;ndice de todos los foros 
  disponibles.<br />
  - El bot&oacute;n de &quot;New Post&quot; te permitir&aacute; crear un nuevo 
  mensaje en ese tablero.<br />
  - Los botones + y - te permitir&aacute;n cambiar entre conversaciones (o cadenas) 
  expandidas y contraidas. Expandir las conversaciones te mostrar&aacute; el Asunto 
  de todos los mensajes y respuestas de modo expandido (se ve cada una). El Contraer 
  las conversaciones te mostrar&aacute; el Asunto de cada mensaje con el n&uacute;mero 
  de respuestas que ese mensaje ha recibido. 
<p>Cuando visualizando las conversaciones de modo individual: <br />
  - El bot&oacute;n de izquierda y derecha te llevar&aacute;n a la anterior o 
  siguiente conversaci&oacute;n.. <br />
  - La flecha hacia arriba te llevar&aacute; de vuelta a la lista de todos los 
  mensajes en esa pagina.<br />
  - La &quot;Vista Plana&quot; te permite ver la conversaci&oacute;n entera, con 
  todas las convestaciones tambi&eacute;n visibles en la p&aacute;gina (si los 
  hay). <br />
  - El bot&oacute;n \"Vista Contra\&iacute;da\" te permite ver los mensajes actuales como 
  conversaciones o cadenas.




  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\">&iquest;Porqu&eacute; se puede pulsar el &quot;Asunto&quot;, &quot;Autor&quot; 
      y el &quot;Creados el&quot;?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Puedes pulsar sobre cualquiera de estos para cambiar el orden el que los mensajes 
  est&aacute;n presentados. Si pulsas una vez sobre Asunto, mostrar&aacute; todos 
  los mensajes en orden alfab&eacute;tico descendente. Si pulsas otra vez sobre 
  Asunto, te ordenar&aacute; los mensajes en orden alfab&eacute;tico. El Autor 
  y el Nombre de usuario funcionan de modo similar.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\">&iquest;Porqu&eacute; pides dos direcciones de correo electr&oacute;nico?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  La direcci&oacute;n real de correo electr&oacute;nico es usada para avisos 
  y subscripciones a foros adem&aacute;s de para mandarte la clave secreta. La 
  otra es lo que los dem&aacute;s usuarios ven cuando ven tu perfil. Como a todos 
  no les gusta que se les vea su direcci&oacute;n de correo electr&oacute;nico 
  normal, y necesitamos saberlo en caso de que quieras subscribirte a un foro, 
  o si deseas que se te env&iacute;en las contestaciones. Por esta raz&oacute;n 
  puedes darnos tu direcci&oacute;n de correo que &uacute;nicamente nosotros veremos 
  y puedes dar otro para el p&uacute;blico general. Hay gente a la que le gusta 
  poner algo as&iacute; como alguien\@quitaesto.domain.com. De este modo la 
  gente todav&iacute;a puede descubrir cual es tu direcci&oacute;n de correo electr&oacute;nico, 
  pero los agentes de spamming no podr&aacute;n llegar a la p&aacute;gina y mandarte 
  correo no solicitado.


  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"register\">&iquest;Que raz&oacute;n tengo para registrar un Nombre de Usuario? 
      <p></a></h3>
<p>
</td></tr><tr><td class=\"lighttable\"> 
Al registrar un Nombre de Usuario podr&aacute;s editar tu perfil y preferencias. 
  Sacar&aacute;s mayor provecho de tu tiempo en estos foros si cambias los datos 
  de tu perfil para que funcione como m&aacute;s te interese. Hay muchas opciones 
  en el perfil para que tu experiencia en estos foros sea m&aacute;s agradable, 
  por lo que se ruega y recomienda tomarse unos breves instantes en probar estas 
  opciones disponibles. Adem&aacute;s, &uacute;nicamente aquellos que son usuarios 
  registrados podr&aacute;n tomar ventaja de los &quot;Anuncios Nuevos&quot; cada 
  vez que visiten el foro.

</td></tr></table>
  ";
# -------------
# Send a footer
   $html ->send_footer();
