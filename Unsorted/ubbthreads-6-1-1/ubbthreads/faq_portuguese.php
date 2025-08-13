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
  $html -> send_header("FAQ (Perguntas frequentes)",$Cat,0,$user);
  $html -> table_header("FAQ (Perguntas frequentes)");

  $phpurl = $config['phpurl'];

  echo " 
  <table cellspacing=\"0\" border=\"0\" width=\"{$theme['tablewidth']}\" align=\"center\" class=\"darktable\">
  <tr><td>
  Aqui tem uma lista das perguntas e dúvidas mais comuns. Clicando em alguma delas você recebe a resposta a essa pergunta. Se a pergunta/resposta não constam nessa lista, envie uma sugestão para <a href=\"mailto:{$config['emailaddy']}\">{$config['emailaddy']}</a>.
  </p><p>
  </td></tr><tr><td class=\"lighttable\">
  <a href=\"#attach\">Posso anexar um arquivo a minha mensagem?</a><br />
  <a href=\"#html\">Posso usar HTML na minha mensagem?</a><br />
  <a href=\"#source\">Posso criar meu próprio fórum?</a><br />
  <a href=\"#cookies\">É obrigatório aceitar cookies?</a><br />
  <a href=\"#polls\">How do I put a poll in my post?</a><br />
  <a href=\"#moreposts\">Como altero o número de mensagens por página?</a><br />
  <a href=\"#buttons\">O que fazem esses botões?</a><br />
  <a href=\"#sortorder\">Porque Assunto, Autor e Data são clicáveis?</a><br />
  <a href=\"#email\">Porque preciso de dois endereços mail?</a><br />
  <a href=\"#register\">Porque preciso registrar um nome de usuário?</a>
  <p>
  </td></tr></table>


  <p>&nbsp;
  <table cellspacing=\"0\" border=\"0\" width=\"{$theme['tablewidth']}\" align=\"center\" class=\"darktable\">
  <tr><td>
  <h3><a name=\"attach\">Posso anexar um arquivo a minha mensagem?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  ";
  if($config['files']) {
    echo "
      Se seu navegador é compatível com Mozilla 4+ você pode.  O arquivo pode ser anexado no momento de revisar a mensagem.
    ";
  }
  else {
    echo "Não, esses fóruns tiveram anexação desativada.";
  }

  echo "
  <p>&nbsp;

  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"html\">Posso usar HTML na minha mensagem?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\"> 
  Existem duas maneiras, dependendo da configuração do fórum.  Se você vê <b>HTML está habilitado</b> pode usar HTML normal na sua mensagem.  Se você vê <b>UBBCode está habilitado</b> os seguintes tags estão disponíveis:
  <br /><br />
  <font class=\"standouttext\">
  [b]
  </font>
  text
  <font class=\"standouttext\">
  [/b]
  </font>
         = Faz letras em negrito.<br />

  <font class=\"standouttext\">
  [email]
  </font>
  joe\@blow.com
  <font class=\"standouttext\">
  [/email] 
  </font>
  = O endereço mail é clicável.<br />

  <font class=\"standouttext\">
  [i]
  </font>
  text
  <font class=\"standouttext\">
  [/i]
  </font>
         = Faz letras em itálico.<br />

  ";
  if ($config['allowimages']) {
    echo"<font class=\"standouttext\">";
    echo"[image]</font>";
    echo"url";
    echo "<font class=\"standouttext\">";
    echo "[/image]</font>  = Põe a url num tag img src.<br />";
  }

  echo "

  <font class=\"standouttext\">
  [pre]
  </font>
  text
  <font class=\"standouttext\">
  [/pre]
  </font>
   = Cerca o texto com tags pre.<br />

  <font class=\"standouttext\">
  [quote]
  </font>
  text
  <font class=\"standouttext\">
  [/quote] 
  </font>
  = Cerca o texto com blockquote e hr's.  Esse tag é usado para citações numa resposta.<br />

  <font class=\"standouttext\">
  [url]
  </font>
  link
  <font class=\"standouttext\">
  [/url]</font>    = Cria um link da url.<br />


  <font class=\"standouttext\">
  [avermelhar]
  </font> = <img src=\"{$config['images']}/graemlins/blush.gif\"><br />

  <font class=\"standouttext\">
  [legal]
  </font> = <img src=\"{$config['images']}/graemlins/cool.gif\"><br />

  <font class=\"standouttext\">
  [loucura]
  </font> = <img src=\"{$config['images']}/graemlins/crazy.gif\"><br />

  <font class=\"standouttext\">
  [aborrecido]
  </font> = <img src=\"{$config['images']}/graemlins/frown.gif\"><br />

  <font class=\"standouttext\">
  [risos]
  </font> = <img src=\"{$config['images']}/graemlins/laugh.gif\"><br />

  <font class=\"standouttext\">
  [brabo]
  </font> = <img src=\"{$config['images']}/graemlins/mad.gif\"><br />

  <font class=\"standouttext\">
  [chocado]
  </font> = <img src=\"{$config['images']}/graemlins/shocked.gif\"><br />

  <font class=\"standouttext\">
  [sorriso]
  </font> = <img src=\"{$config['images']}/graemlins/smile.gif\"><br />

  <font class=\"standouttext\">
  [língua]
  </font> = <img src=\"{$config['images']}/graemlins/tongue.gif\"><br />

  <font class=\"standouttext\">
  [pisca]
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
  <h3><a name=\"source\">Posso criar meu próprio fórum?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Sim, UBB.threads está disponível em <a href=\"http://www.infopop.com\">www.infopop.com</a>

  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"cookies\">É obrigatório aceitar cookies?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Sim.  Os cookies são usados para manter seu nome de usuário e senha, e quais mensagens você leu na sessão corrente.  Sem os cookies essas funções ficariam desabilitadas.

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
  <h3><a name=\"moreposts\">Como altero o número de mensagens por página?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  A quantidade de mensagens mostradas por página pode ser alterada editando seu perfil.  Os valores aceitáveis são de 1 a 99.  Ao fazer seu registro esse número é de {$config['postsperpage']} mensagens por página.


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">

  <h3><a name=\"buttons\">O que fazem esses botões?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">

  Os botões são para navegação e exibição de mensagens.  Dependendo da tela podem executar funções diferentes.
  <p>Na lista de todos os threads:
  <br />- As setas para esquerda e direita buscam a mensagem seguinte ou anterior. 
  <br />- A seta para cima busca o índice de todos os fóruns.
  <br />- O botão \"Nova mensagem\" busca a tela de colocação de mensagens.
  <br />- Os botões + e - alternam entre threads expandidos e comprimidos.  Um thread expandido mostra o cabeçalho da mensagem original e suas respostas na forma de uma lista escalonada.  Um thread comprimido mostra só o cabeçalho da mensagem original e a quantidade de respostas a ela.
  <p>Ao ler um thread individual:
  <br />- As setas para esquerda e direita buscam o thread seguinte ou anterior.
  <br />- A seta para cima retorna para a lista de todas as mensagens na página.
  <br />- O botão \"Flat Mode\" mostra o thread completo numa página se houve respostas 'a mensagem original.
  <br />- O botão \"Threaded Mode\" mostra as mensagens junto com  as respostas num formato escalonado.



  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"sortorder\">Porque Assunto, Autor e Data são clicáveis?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\">
  Clicando num desses altera a ordem em que as mensagens são exibidas. Um primeiro clique em Assunto lista as mensagens em ordem alfabética inversa. O segundo clique altera para ordem alfabética direta. Os links de Autor e Data funcionam de forma semelhante. 


  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"email\">Porque preciso de dois endereços mail?</a></h3>
  <p>
  </td></tr><tr><td class=\"lighttable\"> 
  Precisamos de seu mail real para o envio de notificações e de senhas. O outro endereço é o que outros usuários veêm no seu perfil. Estamos cientes de que muitas pessoas evitam o fornecimento de seu mail particular, mas precisamos dele para o caso de você assinar um fórum ou ter as respostas enviadas para você. Seu mail particular é conhecido apenas por nós, enquanto o segundo será visto pelo público em geral. Sugerimos que seja alterado como p/ex scream\@no.spam.domain.com, as pessoas têm inteligência suficiente para remover o no.spam., enquanto os programas coletores de mails automatizados não. Isso evita que você seja inundado por spam (mail comercial indesejável).

  
  <p>&nbsp;
  </td></tr><tr><td class=\"alternatetable\">
  </td></tr><tr><td class=\"darktable\">
  <h3><a name=\"register\">Porque preciso registrar um nome de usuário?</a></h3>
<p>
</td></tr><tr><td class=\"lighttable\">
Registrando-se você pode editar seu perfil e preferências. Ajustando seu perfil para suas preferências pessoais possibilita um aproveitamento maior dos fóruns, e apenas usuários registrados podem usar o recurso de \"Novas Mensagens\".  Existe uma variedade de opções no perfil, experimente com várias combinações até encontrar a que mais agrada.  
</td></tr></table>
  ";
// -------------
// Send a footer
  $html -> send_footer();
