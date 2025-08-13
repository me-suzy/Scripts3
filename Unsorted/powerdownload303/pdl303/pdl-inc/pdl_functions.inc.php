<?
// Downloadzeit berechnung
function dlspeed($size)
 {
  global $settings;
  $sekunden = round($size/1024/$settings[dlspeed],2);
  if($sekunden >= 60)
   {
    $minuten = round($sekunden/60,2);
    if($minuten >= 60)
     {
      $stunden = round($minuten/60,2);
      $stunden = explode(".", $stunden);
      $mins = ceil(($stunden[1]*60)/100);
      $seks = ceil(($mins*60)/100);
      return $stunden[0]."std, ".$mins."min, ".$seks."sek";
     }
    else
     {
      $mins = explode(".", $minuten);
      $seks = ceil(($mins[1]*60)/100);
      return $mins[0]."min, ".$seks."sek";
     }
   }
  else
   {
    $sekunden = ceil($sekunden);
    return $sekunden."sek";
   }
 }

// Normales Ordner Treeview
function treeview_ordner($ordner, $head)
 {
  global $db_handler,$sordner_id,$settings,$sql_table,$release,$screen_id;

  $treeview_res = $db_handler->sql_query("SELECT * FROM $sql_table[ordner] WHERE sordner_id='$ordner'");
  while($treeview_row = $db_handler->sql_fetch_array($treeview_res))
   {
    if(!$head)
     { $head = "<img src=\"pdl-gfx/spacer.gif\" border=\"0\" width=\"15\">"; }
    if($sordner_id == $treeview_row[ordner_id])
     {
      echo $head."<img src=\"pdl-gfx/folder_open.gif\" border=\"0\"> ";
      if($release && !$screen_id)
       { echo "<a href=\"".$settings[script_file]."ordner_id=".$treeview_row[ordner_id]."\">$treeview_row[name]</a> &raquo; $release[name]<br>\n"; }
      elseif($screen_id)
       { echo "<a href=\"".$settings[script_file]."ordner_id=".$treeview_row[ordner_id]."\">$treeview_row[name]</a> &raquo; <a href=\"$settings[script_file]release_id=$release[release_id]\">$release[name]</a> &raquo; Screenshot"; }
      else echo "$treeview_row[name]<br>\n";
     }
    else
     { echo $head."<img src=\"pdl-gfx/folder.gif\" border=\"0\"> <a href=\"".$settings[script_file]."ordner_id=".$treeview_row[ordner_id]."\">$treeview_row[name]</a><br>\n"; }
    $head2 = "<img src=\"pdl-gfx/spacer.gif\" border=\"0\" width=\"15\">".$head;
    treeview_ordner($treeview_row[ordner_id], $head2);
   }
 }

// Treeview mit Pfeil: ordner » ordner1 ...
function treeview_pfeil($ordner)
 {
  global $db_handler,$settings,$sql_table,$release_id,$screen_id,$ordner_id;

  $subdir_res = $db_handler->sql_query("SELECT * FROM $sql_table[ordner] WHERE ordner_id='$ordner'");
  while($subdir_row = $db_handler->sql_fetch_array($subdir_res))
   {
    if($subdir_row[sordner_id] != 0)
     { echo treeview_pfeil($subdir_row[sordner_id])." &raquo; "; }
    else
     { echo "<a href=\"".$settings[script_file]."ordner_id=0\">Index</a> &raquo; "; }
    if($screen_id || $release_id || $ordner != $ordner_id) echo "<a href=\"".$settings[script_file]."ordner_id=$subdir_row[ordner_id]\">$subdir_row[name]</a>";
    else echo "$subdir_row[name]";
   }
 }

// Durchschalten der Alternativfarben
function alt_switch()
 {
  global $alt_switch,$template;

  $alt_switch++;
  if($alt_switch == 2)
   {
    $alt_switch = 0;
    $alt = $template[alt_2];
   }
  elseif($alt_switch == 1)
   {
    $alt = $template[alt_1];
   }
  return $alt;
 }

// Byte Werte werden gerundet und automatisch in KB,MB,GB und TB umgewandelt
function size($size)
 {
  $size = round($size/1024, 1);
  if($size >= 1024)
   {
    $size2 = round($size/1024, 1);
    if($size2 >= 1024)
     {
      $size3 = round($size2/1024, 1);
      if($size3 >= 1024)
       {
        $size4 = round($size3/1024, 1);
        return $size4." TB";
       }
      else return $size3." GB";
     }
    else return $size2." MB";
   }
  else return $size." KB";
 }

// Ersetzt die Template Variablen durch die Dazugehörigen werte.
function replace($temp, $table_row)
 {
  global $settings,$list,$template,$total;

  $temp = str_replace("{name}", $table_row[name], $temp);
  $temp = str_replace("{titel}", $table_row[titel], $temp);
  $temp = str_replace("{votes}", $table_row[votes], $temp);
  $temp = str_replace("{vote}", $table_row[vote], $temp);
  $temp = str_replace("{vote_form}", $table_row[vote_form], $temp);
  if(preg_match("/{size}/",$temp)) $temp = str_replace("{size}", size($table_row[size]), $temp);
  $temp = str_replace("{downloads}", $table_row[downloads], $temp);
  $temp = str_replace("{views}", $table_row[views], $temp);
  $temp = str_replace("{text}", nl2br($table_row[text]), $temp);
  $temp = str_replace("{screens}", $table_row[screens], $temp);
  if(preg_match("/{dlspeed}/",$temp)) $temp = str_replace("{dlspeed}", dlspeed($table_row[size]), $temp);
  if(preg_match("/{time}/",$temp)) $temp = str_replace("{time}", date($settings[date_format],$table_row[time]), $temp);
  if(preg_match("/{uploader}/",$temp)) $temp = str_replace("{uploader}", user($table_row[uploader]), $temp);
  $temp = str_replace("{id}", $table_row[id], $temp);
  $temp = str_replace("{autor}", $table_row[autor], $temp);
  if(preg_match("/{alt}/",$temp)) $temp = str_replace("{alt}", alt_switch(), $temp);
  $temp = str_replace("{alt_1}", $template[alt_1], $temp);
  $temp = str_replace("{alt_2}", $template[alt_2], $temp);
  $temp = str_replace("{footer_bg}", $template[footer_bg], $temp);
  $temp = str_replace("{header_bg}", $template[header_bg], $temp);
  $temp = str_replace("{table_border}", $template[table_border], $temp);
  $temp = str_replace("{script_file}", $settings[script_file], $temp);
  if(preg_match("/{rows}/",$temp)) $temp = str_replace("{count}", $total, $temp);
  else $temp = str_replace("{count}", $table_row[count], $temp);
  $temp = str_replace("{files}", $table_row[files], $temp);
  $temp = str_replace("{subdirs}", $table_row[subdirs], $temp);
  $temp = str_replace("{filename}", $table_row[filename], $temp);
  if(preg_match("/{traffic}/",$temp)) $temp = str_replace("{traffic}", size($table_row[traffic]), $temp);
  $temp = str_replace("{list}", $list, $temp);
  if(preg_match("/{rows}/",$temp)) $temp = str_replace("{rows}", $table_row, $temp);
  return $temp;
 }

// Macht aus einer Userid den User mit Nick/ICQ/Homepage
function user($user_id)
 {
  global $users,$inadmin;

  $user = "<a href=\"mailto:".$users[$user_id][email]."\">".$users[$user_id][nick]."</a>";
  if($users[$user_id][icq] > 0) $user .= " <a href=\"http://wwp.icq.com/scripts/search.dll?to=".$users[$user_id][icq]."\"><img src=\"http://wwp.icq.com/scripts/online.dll?icq=".$users[$user_id][icq]."&img=5\" border=\"0\"></a>";
  if($users[$user_id][homepage])
   {
    $user.= " <a href=\"".$users[$user_id][homepage]."\"><img src=\"";
    if($inadmin == 1) $user.= "../";
    $user.= "pdl-gfx/www.gif\" border=\"0\"></a>";
   }
  if(!$users[$user_id][nick]) return "Gelöscht";
  return $user;
 }

// Zeigt die Seiten an.
function seiten($total,$perpage,$link,$file)
 {
  global $page,$settings;
  $i = ceil($total/$perpage);
  if($i < $settings[spages]) $settings[spages] = 0;

  $seiten .= "Seiten ($i): ";
  if($page > 1)
   { $seiten .= "<a href=\"".$file."page=1$link\">&laquo;</a> "; }
  if($settings[spages] == 0)
   {
    for($j=1;$j<=$i;$j++)
     {
      if($page == $j)
       {
        $seiten.="<b>[$j]</b> ";
       }
      else
       {
        $seiten.="<a href=\"".$file."page=".$j.$link."\">$j</a> ";
       }
     }
   }
  else
   {
    $spages = ceil(($settings[spages]-1)/2);

    if($page <= $spages)
     {
      $bpages = $page-1;
      $spages += $spages-$bpages;
     }
    elseif($page >= $i-$spages)
     {
      $bpages = $spages+$page-$i+$spages;
      $spages += $i-$spages-$page;
     }
    else $bpages = $spages;
    if($bpages > 0)
     {
      for($j = $page-$bpages; $j <= $page-1; $j++)
       {
        $seiten .= "<a href=\"".$file."page=$j$link\">$j</a> ";
       }
     }
    $seiten .= "<b>[$page]</b> ";

    $apages = $spages;
    if($apages > 0)
     {
      for($j = $page+1;$j <= $page+$apages;$j++)
       {
        $seiten .= "<a href=\"".$file."page=$j$link\">$j</a> ";
       }
     }
   }
  if($i > $page)
   { $seiten .= "<a href=\"".$file."page=$i$link\">&raquo;</a>"; }
  if($total > $perpage)
     { return $seiten; }
 }

// BB Code, Smilies, Glossary, Bad Words...
function bbcode($text,$rep_badwords,$rep_smilies,$rep_glossary,$rep_bbcode,$html)
 {
  global $smilies,$glossary,$badwords;

  if($html == "N") $text = str_replace("<","&lt;",str_replace(">","&gt;",$text));
  if($rep_smilies == "Y")
   {
    for($i = 0; $i < count($smilies); $i++)
     {
      $text = str_replace($smilies[$i][old], "<img src=\"".$smilies[$i][neu]."\" border=\"0\">",$text);
     }
   }
  if($rep_bbcode == "Y")
   {
    $regex = array(
    "/\[b\](.*)\[\/b\]/siU",
    "/\[i\](.*)\[\/i\]/siU",
    "/\[u\](.*)\[\/u\]/siU",
    "/([\n ])([a-z0-9]+?):\/\/([^\t <\n\r]+)/si",
    "/([\n ])(www\.)([^\t <\n\r]+)/si",
    "/([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)/si",
    "/\[url\](.*)\[\/url\]/siU",
    "/\[email\](.*)\[\/email\]/siU",
    "/\[url=(['\"]?)([a-z0-9]+?):\/\/(.*)(['\"]?)\](.*)\[\/url\]/siU",
    "/\[url=(['\"]?)(.*)(['\"]?)\](.*)\[\/url\]/siU",
    "/\[img\]([a-z0-9]+?):\/\/(.*)\[\/img\]/siU",
    "/\[img\](.*)\[\/img\]/siU",
    );
    $repwith = array(
    "<b>\\1</b>",
    "<i>\\1</i>",
    "<u>\\1</u>",
    "\\1[url]\\2://\\3[/url]",
    "\\1[url]http://www.\\3[/url]",
    "\\1[email]\\2@\\3[/email]",
    "<a href=\"\\1\" target=\"_blank\">\\1</a>",
    "<a href=\"mailto:\\1\">\\1</a>",
    "<a href=\"\\2://\\3\" target=\"_blank\">\\5</a>",
    "<a href=\"http://\\2\" target=\"_blank\">\\4</a>",
    "<img src=\"\\1://\\2\" border=\"0\">",
    "<img src=\"http://\\1\" border=\"0\">",
    );

    $text = preg_replace($regex,$repwith,$text);
   }
  if($rep_glossary == "Y")
   {
    for($i = 0; $i < count($glossary); $i++)
     {
      $text = str_replace($glossary[old], $glossary[neu],$text);
     }
   }
  if($rep_badwords == "Y")
   {
    for($i = 0; $i < count($badwords); $i++)
     {
      preg_match("/$badwords[$i]/i",$text,$temp);
      $text = preg_replace("/$badwords[$i]/i", substr($temp[0],0,1).str_repeat("*",strlen($badwords[$i])-1),$text);
     }
   }

  preg_match("/([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)/si",$text,$temp);
  $text = preg_replace("/([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)/si",ascii_encode("$temp[1]@$temp[2]"),$text);

  return $text;
 }

// Generiert einen String aus buchstaben und zahlen mit X zeichen länge
function generate_string($lenght)
 {
  srand(time());
  $pwarray = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

  $pwacount = count($pwarray);
  $password = "";
  $i = 0;
  while($letter = rand("0", $pwacount - 1) && $i != $lenght)
   {
    $password .= $pwarray[$letter];
    $i++;
   }

  return $password;
 }

// Splittet einen langen MySQL Befehlt mit kommentaren usw. in einzelne Befehle.
function split_query(&$return, $sql)
 {
  global $db_handler,$install;
  $sql = preg_replace("/(\n|^)#[^\n]*(\n|$)/", "\\1", trim($sql));
  $sql_len = strlen($sql);
  $in_string = false;

  for($i = 0; $i < $sql_len-1; $i++)
   {
    if($sql[$i] == ";" && !$in_string)
     {
      if($install == 1) $return[] = str_replace("\"","\\\"",str_replace("\\\"","\\\\\"",substr($sql, 0, $i)));
      else $return[] = substr($sql, 0, $i);
      $sql = substr($sql, $i + 1);
      $i = 0;
     }
    if($in_string && $sql[$i] == "'" && $sql[$i-1] != "\\") $in_string = false;
    elseif(!$in_string && $sql[$i] == "'" && $sql[$i-1] != "\\") $in_string = true;
   }
  return true;
 }

// wandelt nen String in ASCII Code um. Für die Email Addys gegen Spam Bots.
function ascii_encode($string)
 {
  for ($i=0; $i < strlen($string); $i++)
   {
    $encoded .= '&#'.ord(substr($string,$i)).';';
   }
  return $encoded;
 }
?>
