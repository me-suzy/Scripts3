<?
function temizle($metin){
//remove unnecessary chars, which way is more suitable for you... (0)
 /*$metin=str_replace("\n","",$metin);
 $metin=str_replace("\'","`",$metin);
 $metin=str_replace("<","«",$metin);
 $metin=str_replace(">","»",$metin);
 $metin=str_replace("\\","|",$metin);
 $metin=str_replace("\"","",$metin);*/
 $metin=str_replace("\'","`",$metin);
 $metin=trim(htmlspecialchars ($metin));
return $metin;
}
function baglan($bos){
//you need to edit this line for your database user and password... (1)
return mysql_connect("localhost","root",""); 
}

//you need to edit this line for session save path... (2)
//$sessPath="/tmp";		//linux					
$sessPath="/temp";		//win

$yol=baglan('');

//you need to edit this line to change the database name... (3)
if(!mysql_select_db("veriler",$yol))		
{die( "Veritabaný veya tablolar yok, MySQL'de oluþturulmasý gereklidir!
       <br>You need to create a database and proper tables in MySQL");
}
?>
