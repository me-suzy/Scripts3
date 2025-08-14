<?
include "conf.php";
session_save_path("$sessPath");
session_start();
$cikis			=temizle($HTTP_GET_VARS["cikis"]);
$girisIslemi	=temizle($HTTP_GET_VARS["girisIslemi"]);
$silme			=temizle($HTTP_GET_VARS["silme"]);
$silEmin		=temizle($HTTP_GET_VARS["silEmin"]);
$sirano			=temizle($HTTP_GET_VARS["sirano"]);
$reism			=temizle($HTTP_GET_VARS["reism"]);
if( $HTTP_SESSION_VARS["girisVar"]=='' && $sirano!='') die("<font color=red><b>&raquo;Yetkisiz giriþ algýlandý!</b></font>");
if ($silme=='evet' && $sirano!='')
{
	echo "<body bgcolor=#fffff0><link href=stil.css rel=stylesheet type=text/css>";
	echo "<font size=2 color=red face=arial>Kayit Silme Onayi - Kayýt no: $sirano <script LANGUAGE=JavaScript>";
    echo "cevap=confirm('Emin misiniz?');";
    echo "if (cevap)  window.location.href='yonetici.php?silEmin=tamam&sirano=$sirano&reism=$reism';";
    echo "if (!cevap) window.location.href='yonetici.php';";
    echo "</script>";
    exit; 	
}
if ($cikis=='evet') {
  $HTTP_SESSION_VARS["ad"]="";
  $HTTP_SESSION_VARS["sifre"]="";
  $HTTP_SESSION_VARS["girisVar"]='';
  }
?>
<link href="stil.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style3 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9">
<link href="stil.css" rel="stylesheet" type="text/css">
<title>Giriþ</title>
</span></p>
<?
$aktifOl		=temizle($HTTP_GET_VARS["aktifOl"]);
$sirano			=temizle($HTTP_GET_VARS["sirano"]);
$kullaniciAdi	=temizle($HTTP_POST_VARS["ad"]);
$sifre			=temizle($HTTP_POST_VARS["sifre"]);
$girisVar		=temizle($HTTP_POST_VARS["girisVar"]);
$yol			=baglan('');	   

if (!empty($HTTP_SESSION_VARS["ad"]) && !empty($HTTP_SESSION_VARS["sifre"]) && $HTTP_SESSION_VARS["girisVar"]=='1') 
 { $kullaniciAdi	=temizle($HTTP_SESSION_VARS["ad"]);
   $sifre			=temizle($HTTP_SESSION_VARS["sifre"]);
 }


if ($aktifOl=='evet' || $aktifOl=='hayir')
{
  $kabTarih=date("Y-n-j");
  if ($aktifOl=='evet')   
     $gelenveri3=mysql_query("update netpazar set aktif='1', kabulTarihi='$kabTarih' where kayno=$sirano",$yol);
  if ($aktifOl=='hayir')  
     $gelenveri3=mysql_query("update netpazar set aktif='0' where kayno=$sirano",$yol);
     if (!$gelenveri3) 
     die("<font color=red><b>&raquo;Tabloya eriþilemiyor!</b></font>");
}

     $gelenveri0=mysql_query("select * from admin where user='$kullaniciAdi' and password='$sifre'",$yol);
     if (!$gelenveri0) 
     die("<font color=red><b>&raquo;Tabloya eriþilemiyor!</b></font>");
	 else {	 
	  if ($kullaniciAdi!='' && $sifre!=''){
		 $girisVar='1';}
	 }


if ($girisVar=='1')
if ($kullaniciAdi!='' && $sifre!='')
 {

 if ($silEmin=='tamam')
	{
		$gelenveriSil=mysql_query("delete from netpazar where kayno='$sirano'",$yol);
        if (!$gelenveriSil) 
         die("<font color=red><b>&raquo;Kayýt silinemiyor!</b></font>");
		unlink('yollanan/'.$reism); //resimler de silinecek
        echo "Silme iþlemi tamamlandý. <a href=# onclick=window.location.href='yonetici.php';>&raquo;Geri</a>";
	 	exit;
	}

     $gelenveri1=mysql_query("select * from admin where user='$kullaniciAdi' and password='$sifre'",$yol);
     if (!$gelenveri1) 
     die("<font color=red><b>&raquo;Tabloya eriþilemiyor!</b></font>");
	 	if (mysql_numrows($gelenveri1)==1)
		 $girisIslemi='tamam';
		 else
		 echo "<font color=red><b>&raquo;Tanýmlanamayan kullanýcý girildi!</b></font>";
	 
 }
// else echo "<font color=red><b>&raquo;Gerekli veri girilmedi!</b></font>";
 if ($girisIslemi=='tamam')
  {  
		 $HTTP_SESSION_VARS["ad"]=$kullaniciAdi;
		 $HTTP_SESSION_VARS["sifre"]=$sifre;
		 $HTTP_SESSION_VARS["girisVar"]='1';
//    header("Location:yonetici.php?girisIslemi=tamam");
     $gelenveri=mysql_query("select *,(unix_timestamp(now()) - unix_timestamp(kabulTarihi) )/3600/24 as GF from netpazar order by kabulTarihi DESC",$yol);
     if (!$gelenveri) 
     die("<font color=red><b>&raquo;Tabloya eriþilemiyor!</b></font>");
           $sirano=1;
           $i=0;
	echo "<table width=100% border=0 align=center cellpadding=0 cellspacing=1 bordercolor=#FFFF00 >
  <caption align=top>
 <div align=center>
 <span class='style1 baslik style3'>
 <strong><img src=img/trade.gif height=30 align=absmiddle>&nbsp;</strong></span>
 <span class=style3><strong>Net Pazar - Yönetici Giriþi - Duyurular</strong></span></div>
  </caption>
   <tr bordercolor=#FFFF00 bgcolor=#CCCCCC>
    <th width=51>Sýra</th>
    <th width=20>Aktiflik</th>
    <th width=50>Baþlýk<br>ve Okunma Sayýsý</th>
    <th width=90>Eklenme Tarihi</th>
    <th width=90>Kabul Tarihi <img src=img/down.gif width=15 height=15></th>
    <th width=223>Metin </th>
    <th width=23>Fiyat </th>
	  </tr>";
		   
           while($i<mysql_numrows($gelenveri))        
         {
 $kayno			=mysql_result($gelenveri,$i,"kayno");
 $konuBasligi	=mysql_result($gelenveri,$i,"konuBaslik");
 $konuMetni		=mysql_result($gelenveri,$i,"konuMetni");
 $tarih1		=mysql_result($gelenveri,$i,"eklemeTarihi");
 $tarih2		=mysql_result($gelenveri,$i,"kabulTarihi");
 $fiyat			=mysql_result($gelenveri,$i,"fiyat");
 $paraTuru		=mysql_result($gelenveri,$i,"paraTuru");
 $aktif			=mysql_result($gelenveri,$i,"aktif");
 $resim			=mysql_result($gelenveri,$i,"resim");
 $okuSayac		=mysql_result($gelenveri,$i,"okunmaSayisi");
 $gunFarki		=round(mysql_result($gelenveri,$i,"GF"));
 echo "  <tr>
    <td align=right>".($i+1)."</td>";
	
	 if ($aktif=='0') //RED GREEN
	  echo "<td align=center width=70><a href=yonetici.php?sirano=$kayno&aktifOl=evet><img src=img/deaktif.gif border=0></a>
			<a href=duyuruDuzenle.php?sirano=$kayno target=_blank><img src=img/ekle.png border=0></a>
	        <a href=yonetici.php?sirano=$kayno&silme=evet&reism=$resim><img src=img/sil.png border=0></a></td>";
	  else
	  echo "<td align=center width=70><a href=yonetici.php?sirano=$kayno&aktifOl=hayir><img src=img/aktif.gif border=0></a>
			<a href=duyuruDuzenle.php?sirano=$kayno target=_blank><img src=img/ekle.png border=0></a>
	        <a href=yonetici.php?sirano=$kayno&silme=evet&reism=$resim><img src=img/sil.png border=0></a></td>";

    echo "<td align=left>$konuBasligi <font color=green>($okuSayac)</td>
    <td align=center width=90>$tarih1</td>
    <td align=center width=90>$tarih2<br><font color=green>($gunFarki günlük)</td>
    <td align=left>";
	echo (strlen($konuMetni)>=75)?substr($konuMetni,0,75)."...":$konuMetni;
	echo "</td><td align=right>$fiyat ";
	if ($paraTuru=='0') echo " YTL</td>";
	if ($paraTuru=='1') echo " $</td>";
	if ($paraTuru=='2') echo " </td>";
    echo "</tr>";
  	$i++;
   		} //while
  echo "</table><br>";
  mysql_close;
  die("<center><a href=yonetici.php?cikis=evet>Oturumu Kapat</a> /  
   	  <a href=yonetici.php>Tazele</a><br>	  
	  <input name=Submit3 type=button class=style2 accesskey=k title='Pencereyi Kapat (Alt+K)' onClick=window.close(); value=Kapat>");
  }
  
mysql_close;
?>
<body  onload="document.form1.ad.focus();" leftMargin=5 topMargin=5 marginheight=5 marginwidth=5>
<div align="center"><span class="style1 baslik style3"><strong><img src="img/trade.gif" width="30" align="absmiddle">&nbsp;</strong></span><span class="style3"><strong>Net Pazar - Yönetici Giriþi</strong></span></div>
<form name="form1" method="post" action="yonetici.php">
  <table width="312" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
    <tr bgcolor="#CCCCCC">
      <td width="106" bgcolor="#CCCCCC"><div align="right"><strong>Kullanýcý Adý </strong></div></td>
      <td width="135"><input name="ad" type="text" class="butoon" size="20" maxlength="20" value="<? echo ($kullaniciAdi=='')?$kullaniciAdi2:$kullaniciAdi?>"></td>
      <td width="57" bgcolor="#CCCCCC"><span class="style4"></span></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <td bgcolor="#CCCCCC"><div align="right"><strong>Parola</strong></div></td>
      <td><input name="sifre" type="password" class="butoon" size="20" maxlength="20" value=""></td>
      <td bgcolor="#CCCCCC"><span class="style4"></span></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <td bgcolor="#CCCCCC"><input name="girisVar" type="hidden" id="girisVar" value="1"></td>
      <td><input name="Submit" type="submit" class="butoon" value="Tamam">
      <input name="Submit2" type="reset" class="butoon" value="Temizle"></td>
      <td bgcolor="#CCCCCC"><span class="baslik style1"><span class="style2">
        <input name="Submit3" type="button" class="style2" accesskey="k" title="Pencereyi Kapat (Alt+K)" onClick="window.close();"
		value="Kapat">
      </span></span></td>
    </tr>
  </table>
</form>
