<?
if($settings[enable_search] == "Y")
 {
  if($submit == 1)
   {
    $query = "SELECT * FROM $sql_table[release] WHERE ";

    $stucke = explode(" ",$text);
    for($i = 0; $i < count($stucke); $i++)
     {
      if($in == "text") $query.= "text LIKE '%$stucke[$i]%'";
      elseif($in == "titel") $query.= "name LIKE '%$stucke[$i]%'";
      else $query.= "name LIKE '%$stucke[$i]%' OR text LIKE '%$stucke[$i]%'";
      if($i != count($stucke)-1) $query.= " OR ";
      $stucke[$i] = "/".$stucke[$i]."/i";
     }

    $query.= " AND released='Y' ORDER BY $settings[orderby] $settings[orderseq]";

    $temp1=$page * $settings[perpage] - $settings[perpage];
    $limit=$temp1.",".$settings[perpage];
    $total = $db_handler->sql_num_rows($db_handler->sql_query($query));
    echo "<br><center>Ihre Suchanfrage ergab <b>$total</b> Treffer</center><br>";

    if($total > 0)
     {
      echo "<center>".seiten($total,$settings[perpage],"&show_search=1&submit=1&text=$text&in=$in",$settings[script_file])."<br></center>";
      echo "<form action=\"$settings[script_file]change_list=1\" method=\"post\">";

      $release_rows = "";
      $files_res = $db_handler->sql_query("$query LIMIT $limit");
      while($files_row = $db_handler->sql_fetch_array($files_res))
       {
        $size = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT SUM(size) AS tsize FROM $sql_table[files] WHERE release_id='$files_row[release_id]' AND mirror='0'"));
        $files_row[size] = $size[tsize];
        $files_row[id] = $files_row[release_id];

        $files_row[name] = stripslashes($files_row[name]);
        $files_row[text] = stripslashes($files_row[text]);
        $files_row[text] = preg_replace($stucke,"<b>$0</b>",$files_row[text]);
        $files_row[name] = preg_replace($stucke,"<b>$0</b>",$files_row[name]);

        if(!$files_row[text])
         { $files_row[text] = "N/A"; }
        elseif($settings[trenn_durch] == "zeichen")
         {
          $files_row[text] = str_replace($settings[trenn_string],"",$files_row[text]);
          if(strlen($files_row[text]) <= $settings[trenn_zeichen])
           { $files_row[text] = $files_row[text]; }
          else
           { $files_row[text] = substr($files_row[text],0,$settings[trenn_zeichen])."..."; }
         }
        elseif($settings[trenn_durch] == "string")
         {
          $text = explode($settings[trenn_string], $files_row[text]);
          $files_row[text] = $text[0];
         }
        if($files_row[text] != "N/A") $files_row[text] = bbcode($files_row[text],$settings[badwords_releases],$settings[smilies],$settings[glossary],$settings[bb_code],$settings[html_releases]);

        $release_rows .= replace($template[release_row], $files_row);
       }

      echo replace($template[release_box], $release_rows)."</form>";
     }
   }
  else
   { ?>
<center>
<form action="<? echo $settings[script_file]; ?>show_search=1&submit=1" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="45%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="2" align="center">
            <b>Suche</b>
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>Suchbegriff</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <input type="text" name="text" size="30"><br>
            Einzelne Suchwürter trennen durch Leerzeichen.
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>Sichen in</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <select name="in">
            <option value="text">Beschreibung</option>
            <option value="texttitel">Beschreibung und Titel</option>
            <option value="titel">Titel</option>
            </select>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="2" align="center">
            <input type="submit" value="Suche starten">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
  <? }
 }
else
 { echo "<br><center>Die Suche wurde global deaktiviert.</center>"; }
?>
