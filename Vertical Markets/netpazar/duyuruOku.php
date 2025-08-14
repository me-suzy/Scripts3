<?
include "conf.php";
session_save_path("$sessPath");
session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9">
<link href="stil.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style3 {
	font-size: 18px;
	font-weight: bold;
}
.style4 {font-size: 18px}
-->
</style>
<body leftMargin=5 topMargin=5 marginheight=5 marginwidth=5>
<title class="style2">Duyuru Oku</title>
<h1 align="center"><span class="style2"><span class="style1 baslik style3"><strong><img src="img/trade.gif"  height="30" align="absmiddle">&nbsp;</strong></span></span><span class="style3"><strong>Net Pazar</strong> - Duyuru Oku</span></h1>
<p> 
  <?
 $sirano		=$HTTP_GET_VARS["sirano"];
 $sureUzat		=$HTTP_GET_VARS["uzat"];
 $yol			=baglan('');	   

 if ($sureUzat=='evet')
 { 
   $tarih= date("Y-n-j");
   $gelenveri1=mysql_query("update netpazar set 
     						kabulTarihi='$tarih'	
     						where kayno='$sirano' and aktif=1",$yol);
   if (!$gelenveri1) 
     echo ("<font color=red><b>&raquo;Duyuru süresi uzatýlamadý!</b></font><br>");
	 else 
	 echo "<font color=blue>&raquo;Duyuru süresi uzatýldý.</font><br>";
 } 
 
 if ($sirano!='') 
  {
   $gelenveri=mysql_query("select * from netpazar where kayno='$sirano' and aktif=1 limit 0,1",$yol);
   if (!$gelenveri) 
     die("<font color=red><b>&raquo;Kayýt bulunamýyor!</b></font>");
 
 if (mysql_numrows($gelenveri)==0)	  die("<font color=red><b>&raquo;Kayýt bulunamýyor!</b></font>");
 $konuBasligi	=mysql_result($gelenveri,0,"konuBaslik");
 $konuMetni		=mysql_result($gelenveri,0,"konuMetni");
 $gonderen		=mysql_result($gelenveri,0,"gonderen");
 $email			=mysql_result($gelenveri,0,"mail");
 $telefon1		=mysql_result($gelenveri,0,"telefon1");
 $telefon2		=mysql_result($gelenveri,0,"telefon2");
 $tarih1		=mysql_result($gelenveri,0,"eklemeTarihi");
 $tarih2		=mysql_result($gelenveri,0,"kabulTarihi");
 $fiyat			=mysql_result($gelenveri,0,"fiyat");
 $paraTuru		=mysql_result($gelenveri,0,"paraTuru");
 $resim			=mysql_result($gelenveri,0,"resim");
 $okuSayac		=mysql_result($gelenveri,0,"okunmaSayisi")+1;

   $gelenveriSayac=@mysql_query("update netpazar set 
     						okunmaSayisi=$okuSayac	
     						where kayno='$sirano' and aktif=1",$yol); //okunma sayýsý güncellendi
 if ($resim!='') {
	  if (file_exists('yollanan/'.$resim)) 
	   $resimvarmi="<a href=yollanan/$resim target=_blank title=Görüntüle>
	   <img src=yollanan/$resim border=0 width=200  align=absmiddle></a>"; 
	   else
	   $resimvarmi="Resim dosyasý bulunamadý";
   }
  else $resimvarmi="Resim Yok";
  
 echo "Kayýt no: $sirano<br>
  <table width=100% border=0> 
    <tr align=left><td><b>Duyurunun Okunma Sayýsý</td> <td><font color=green>$okuSayac</td></tr>
    <tr align=left><td width=30%><b>Baþlýk</td><td>$konuBasligi</td></tr>
    <tr align=left><td><b>Metin</td><td> $konuMetni</td></tr>
    <tr align=left><td><b>Gönderen</td> <td>$gonderen</td></tr>
    <tr align=left><td><b>Eposta</td> <td><a href=email.php?address=$email>&raquo;$email <img src=img/new.gif border=0 align=absmiddle></a></td></tr>
    <tr align=left><td><b>Cep Telefonu</td><td> $telefon1</td></tr>
    <tr align=left><td><b>Ev Telefonu</td><td> $telefon2</td></tr>
    <tr align=left><td><b>Resim</td> <td>$resimvarmi</td></tr>
    <tr align=left><td><b>Eklenme Tarihi</td> <td>$tarih1</td></tr>
    <tr align=left><td><b>Kabul Tarihi</td> <td>$tarih2</td></tr>
    <tr align=left><td><b>Fiyat</td> <td>$fiyat
	";
 if ($paraTuru=='0') echo " YTL</td></tr>";	else
 if ($paraTuru=='1') echo " $</td></tr>";	else
 if ($paraTuru=='2') echo " </td></tr>";	else
	 echo " Para Birimi Girilmemiþ</td></tr>";
   	
  echo "</table>";
  echo "<br><a href=duyuruOku.php?sirano=$sirano&uzat=evet>&raquo;Süreyi Uzat</a>";
  mysql_close;
  }
?>
</p>
<p align="center">
  <input name="Button" type="button" accesskey="k" title="Pencereyi Kapat (Alt+K)" onClick="window.close();" value="Kapat">
</p>
