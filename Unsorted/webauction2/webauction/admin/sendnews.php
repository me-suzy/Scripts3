<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

//--Authentication check
	if(! $HTTP_COOKIE_VARS["authenticated"]){
		Header("Location: login.php?loginfail=1");
	}
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	
	include "./header.php";
?>

<html>
<HEAD>

<TITLE></TITLE>

<?    require('../includes/styles.inc.php'); ?>

</HEAD>

  
<BODY bgcolor="#FFFFFF">
<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
<center>


<?
class CMIMEMail {
 var $to;
 var $boundary =  "----=_NextPart_000_0009_01BF95E9.CDFD2060";
 var $smtp_headers;
 var $filename_real;
 var $body_plain;
 var $body_html;
 var  $atcmnt;
 var $atcmnt_type;
 function CMIMEMail($to,$from,$subject,$priority=3) {
   $this->to=$to; $this->from=$from; $this->subject=$subject; $this->priority=$priority;
 }
 function  mailbody( $plain, $html= "" ) {
   $this->body_plain=$plain;
   $this->body_html=$html;
 }
 function  attach( $name, $content_type, $data ) {
   $this->atcmnt[$name]=$data;
   $this->atcmnt_type[$name]=$content_type;
 }
 function  attachfile( $fname, $content_type , $name) {
//
   $this->attach($name,$content_type,fread(fopen("$fname", "r"), filesize("$fname")));

 }
 function  clear() {
   unset( $atcmnt );
   unset( $atcmnt_type );
 }

 function  makeheader() {

   $out= "From: ".$this->from. "\n";
   $out.= "To: \"".$this->toname."\"<".$this->to. ">\n";
   $out.= "MIME-Version: 1.0\nContent-Type: multipart/mixed;\n boundary=\"".$this->boundary. "\"\nX-Priority: ".$this->priority. "\n";
   return $out;
 }

 function  makebody() {
   $boundary2=  "----=_NextPart_001_0009_01BF95E9.CDFD2060";
   $out= "";
   $out= "\n\n".$this->body_plain. "\n\n";
   $body_plan=$this->body_plain;
   if( $this->body_html!= "" ) {
     $out.= "--".$this->boundary. "\nContent-Type: multipart/alternative;\n boundary=$boundary2\n\n";
     $out.= "$body_plan\n--$boundary2\nContent-Type: text/plain\nContent-Transfer-Encoding: quoted-printable\n\n".$this->body_plain. "\n\n--$boundary2\nContent-Type: text/html\n".
            "Conent-Transfer-Encoding: quoted-printable\n\n$this->body_html\n\n--$boundary2--\n";
   } else {
     $out.= "--".$this->boundary. "\nContent-Type: text/plain\nContent-Transfer-Encoding: quoted-printable\n\n".$this->body_plain. "\n\n--".$this->boundary. "\n";
   }
if(isset($this->atcmnt_type)) {
if(is_array($this->atcmnt_type)) {
   reset( $this->atcmnt_type);
   while( list($name, $content_type) = each($this->atcmnt_type) ) {
     $out.= "\n--".$this->boundary. "\nContent-Type: $content_type\nContent-Transfer-Encoding: base64\nContent-Disposition: attachment; filename=\"$name\"\n\n".
       chunk_split(base64_encode($this->atcmnt[$name])). "\n";

   }
}
}
   $out.= "--".$this->boundary. "--\n";
   return $out;
 }

 function  send(){

   mail( $this->to, $this->subject, $this->makebody(),$this->makeheader() );
 }
 function  sendto($email){
   mail( $email, $this->subject, $this->makebody(),$this->makeheader() );
 }
 function  SMTPsend($host){
   $errno=0;$errstr= "";
//   $f=fsockopen("127.0.0.1",25,&$erno, &$errstr);
   if(!$f) {
     $this->send();
   } else {
      //SNMP commands Not finished yet
      echo fgets($f,512);
     fputs($f, "HELO host.com\n");
      echo fgets($f,512);
      fputs($f, "MAIL FROM: ".$this->from. "\n");
      echo fgets($f,512);
      fputs($f, "RCPT TO: ".$this->to). "\n";
      echo fgets($f,512);
     fputs($f, "data\n");
      echo fgets($f,512);
     fputs($f, "From: ".$this->from. "\nTo: ".$this->to. "\n".$this->makeheader().$this->makebody(). "\n\n.\n");
     fputs($f, "quit\nexit");

     fclose($f);
  }
 }
}

include "../includes/passwd.inc.php";
mysql_connect($DbHost, $DbUser, $DbPassword);
mysql_selectdb($DbDatabase);
$query="SELECT *FROM ".$dbfix."_users";
$query=mysql_query($query);
$y=0;
while ($row=mysql_fetch_array($query)) {
$t=$txt;
$h=$htmltxt;
$s=$subject;
while (list($i, $j)=each($row)) {
$t=str_replace("{$i}", $j, $t);
$h=str_replace("{$i}", $j, $h);
$s=str_replace("{$i}", $j, $s);
}
$tt=new CMIMEMail($row["email"], $von, $s, 3);
$tt->mailbody($t, $h);
$tt->Send();

$y++;
}
echo "$y";
?>
 Mails versendet!<br>
<pre>
Text:
<? 
echo $txt;
?>


=======================================================
<?
echo $htmltxt;
?>
</pre>

</center>
	  <CENTER>
		<A HREF="admin.php" CLASS="links">Admin Home</A>
		</CENTER>
	  <BR>
</TD>
</TR>
</TABLE>
  
  <!-- Closing external table (header.php) -->
  </TD>
  </TR>
</TABLE>
  
  
  <? include "./footer.php"; ?>
