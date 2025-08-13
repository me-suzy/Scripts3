<?
$files_res = $db_handler->sql_query("SELECT * FROM $sql_table[files]");
$files = $db_handler->sql_num_rows($files_res);

$size = 0;
$traffic = 0;
$downloads = 0;

while($files_row = $db_handler->sql_fetch_array($files_res))
 {
  if($files_row[mirror] > 0)
   {
    $mirror_of = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[files] WHERE file_id='$files_row[mirror]'"));
    $traffic += $mirror_of[size]*$files_row[downloads];
   }
  else
   {
    $size += $files_row[size];
    $traffic += $files_row[size]*$files_row[downloads];
   }
  $downloads += $files_row[downloads];
 }
$tage = ceil((time()-$settings[installed])/(3600*24));

$durch_traffic = $traffic/$tage;
$durch_downloads = $downloads/$tage;

$size = size($size);
$traffic = size($traffic);
$durch_traffic = size($durch_traffic);
$durch_downloads = round($durch_downloads,1);

$stats = str_replace("{files}", $files, $template[stats]);
$stats = str_replace("{size}", $size, $stats);
$stats = str_replace("{downloads}", $downloads, $stats);
$stats = str_replace("{traffic}", $traffic, $stats);
$stats = str_replace("{durch_downloads}", $durch_downloads, $stats);
$stats = str_replace("{durch_traffic}", $durch_traffic, $stats);

echo replace($stats, "");
?>
