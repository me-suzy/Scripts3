<?PHP
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]  !=  "") { 
       $rmt = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
   }
else { 
       $rmt = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
   }

$file = "ip.txt"; 

$line_to_delete = 99;

$file_data = file($file);
unset($file_data[$line_to_delete]);
$file_pointer = fopen($file,'w');
$new_file_data = implode("",$file_data);
fwrite($file_pointer,$new_file_data);
fclose($file_pointer);



         $open = fopen($file, "r"); 
         $content = fread($open, filesize($file));
$matches = file ($file); 

if(!preg_match_all("/$rmt/",$content,$matches)) {



if(file_exists("counter.txt"))
{
  $exist_file = fopen("counter.txt", "r");
  $new_count = fgets($exist_file, 255);
  $new_count++;
  fclose($exist_file);
  print("$new_count");
  $exist_count = fopen("counter.txt", "w");
  fputs($exist_count, $new_count);
  fclose($exist_count);
}
else
{
$new_file = fopen("counter.txt", "w");
fputs($new_file, "1");
print("1");
fclose($new_file);
}

  $exist_ip = join ('', file ('ip.txt') );
  $fileName = fopen ('ip.txt', 'w');
  fputs ($fileName, $rmt . chr(13) . chr(10) . $exist_ip);
  fclose ($fileName);

}
else {
  $exist_file = fopen("counter.txt", "r");
  $read_it = fread($exist_file,255);
  print($read_it);
  fclose($exist_file);
}
exit;
?>