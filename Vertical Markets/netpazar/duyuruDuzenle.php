<?
include "conf.php";
session_save_path("$sessPath");
session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9">
<link href="stil.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style7 {font-size: 18px; font-weight: bold; }
.style8 {
	font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
<title>Duyuru Düzenle </title>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style2 {font-family: Verdana, Arial, Helvetica, sans-serif}
-->
</style>
<p align="center" class="style2 style7"><span class="style1 baslik style3"><strong><strong><img src="img/trade.gif"  height="30" align="absmiddle">&nbsp;<span class="style8">Net Pazar</span></strong><span class="style8"> - Yönetici Giriþi - Duyuru Düzenle </span></strong></span></p>
<?
 $sirano		=temizle($HTTP_GET_VARS["sirano"]);
 $konuBasligi	=temizle($HTTP_POST_VARS["konuBasligi"]);
 $konuMetni		=temizle($HTTP_POST_VARS["konuMetni"]);
 $gonderen		=temizle($HTTP_POST_VARS["gonderen"]);
 $eposta		=temizle($HTTP_POST_VARS["eposta"]);
 $telefon1		=temizle($HTTP_POST_VARS["telefon1"]);
 $telefon2		=temizle($HTTP_POST_VARS["telefon2"]);
 $kategori		=temizle($HTTP_POST_VARS["kategori"]);
 $eklemeVar 	=temizle($HTTP_POST_VARS["eklemeVar"]);
 $fiyat			=temizle($HTTP_POST_VARS["fiyat"]);
 $paraTuru 		=temizle($HTTP_POST_VARS["paraTuru"]);
 $okuSayac 		=temizle($HTTP_POST_VARS["okuSayac"]);
 $tarih 		=date("Y-n-j");
 $resim 		=temizle($HTTP_POST_VARS["resim"]);
 $yol			=baglan('');	   
 $resimsiz 		=temizle($HTTP_GET_VARS["resimsiz"]); 
 if ($resimsiz=='1') $HTTP_SESSION_VARS["resimAdi"]="";
 $reism			=$HTTP_SESSION_VARS["resimAdi"];
 
 if ($HTTP_SESSION_VARS["girisVar"]!='1') die ("<font color=red><b>&raquo;Yetkisiz giriþ algýlandý!</b></font>");
 
 if ($eklemeVar!='')
 if ($konuBasligi!='' && $konuMetni!='' && $gonderen!='' && $eposta!='' && $fiyat!='') 
  {
    $gelenveri=mysql_query("update netpazar set kategori='$kategori',konuBaslik='$konuBasligi',
		    				konuMetni='$konuMetni',gonderen='$gonderen',mail='$eposta',telefon1='$telefon1',
							telefon2='$telefon2',resim='$resim',fiyat='$fiyat',paraTuru='$paraTuru',okunmaSayisi ='$okuSayac'  
							where kayno=$eklemeVar",$yol);
   if (!$gelenveri) 
     die("<font color=red><b>&raquo;Ýþlem tamamlanamadý.</b></font>");

   echo "<center><font color=blue>&raquo;Duyuru güncelleme iþlemi tamamlandý.</font>
   <input type=button class=style2 accesskey='k' title='Pencereyi Kapat (Alt+K)' onClick='window.close();' value='Kapat'>";
   exit;
  }else echo "<font color=red><b>&raquo;Eksik býrakýlan alanlar var! Ýþlem tamamlanamadý.</b></font>";
  //OKUMA
    $gelenveri2=mysql_query("select * from netpazar	where kayno=$sirano limit 1",$yol);
   if (!$gelenveri2) 
     die("<font color=red><b>&raquo;Kayýt bilgileri alýnamadý.</b></font>");
   if (mysql_numrows($gelenveri2)==1){
		 $konuBasligi	=mysql_result($gelenveri2,0,"konuBaslik");
		 $konuMetni		=mysql_result($gelenveri2,0,"konuMetni");
		 $gonderen		=mysql_result($gelenveri2,0,"gonderen");
		 $eposta		=mysql_result($gelenveri2,0,"mail");
		 $telefon1		=mysql_result($gelenveri2,0,"telefon1");
		 $telefon2		=mysql_result($gelenveri2,0,"telefon2");
		 $kategori		=mysql_result($gelenveri2,0,"kategori");
		 $fiyat			=mysql_result($gelenveri2,0,"fiyat");
		 $paraTuru 		=mysql_result($gelenveri2,0,"paraTuru");
		 $resim			=mysql_result($gelenveri2,0,"resim");
		 $okuSayac		=mysql_result($gelenveri2,0,"okunmaSayisi");
	}else
	 die("<font color=red><b>&raquo;Kayýt Bulunamadý!</b></font>");	   
	 
  mysql_close;
?>
<body  onload="document.form1.konuBasligi.focus();" leftMargin=5 topMargin=5 marginheight=5 marginwidth=5>
<form name="form1" method="post" action="duyuruDuzenle.php">
  <table width="610" border="0">
    <tr>
      <td width="228"><b>Duyuru Okunma Sayýsý </td>
      <td width="310"><input name="okuSayac" type="text" class="butoon" size="5" maxlength="5" value="<?echo $okuSayac?>"></td>
      <td width="58"></td>
    </tr>
    <tr>
      <td width="228"><b>Duyuru Baþlýðý </td>
      <td width="310"><input name="konuBasligi" type="text" class="butoon" size="30" maxlength="30" value="<?echo $konuBasligi?>"></td>
      <td width="58"><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><b>Duyuru Metni </td>
      <td><textarea name="konuMetni" cols="50" rows="5" class="butoon" ><?echo $konuMetni?></textarea></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><b>Gönderenin Ad Soyadý </td>
      <td><input name="gonderen" type="text" class="butoon" size="30" maxlength="30" value="<?echo $gonderen?>"></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><b>E-posta Adresi</td>
      <td><input name="eposta" type="text" class="butoon" value="<?echo $eposta?>" size="30" maxlength="30" ></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><b>Cep Telefonu</td>
      <td><input name="telefon1" type="text" class="butoon" size="15" maxlength="15" value="<?echo $telefon1?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><b>Ev Telefonu</td>
      <td><input name="telefon2" type="text" class="butoon" size="15" maxlength="15" value="<?echo $telefon2?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><b>Resim Gönder </td>
      <td>
	  <input name="resim" type="text" class="butoon" size="30" maxlength="30" value="<?echo $resim?>">
	  <br>
	   <? 
	  if ($reism!="") {
	   echo "<img src=yollanan/$reism width=70 height=50 align=absmiddle> $reism"; 
	   echo " <a href=?resimsiz=1&sirano=$sirano>&raquo;Resim Seçimini Ýptal Et</a> <br><font color=green><b>Yeni dosyanýzý buradan ismini kopyalayýp güncelleyebilirsiniz.</b></font><br>"; 
	   }

		 if ($resim!='') {
			  if (file_exists('yollanan/'.$resim)) 
			   $resimvarmi="<img src=yollanan/$resim border=0 width=200  align=absmiddle>"; 
			   else
			   $resimvarmi="Resim dosyasý bulunamadý";
		   }
		  else $resimvarmi="Resim Yok";
	     echo $resimvarmi;
	   ?>
	    <a href="resimYollama.php?sirano=<? echo $sirano?>">&raquo;Gönder</a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><b>Fiyat</td>
      <td><input name="fiyat" type="text" class="butoon"  value="<?echo $fiyat?>">
        <select name="paraTuru" class="butoon">
          <option value="0"  <?echo ($paraTuru=='0')?"selected":"";?>>YTL</option>
          <option value="1"  <?echo ($paraTuru=='1')?"selected":"";?>>$</option>
          <option value="2"  <?echo ($paraTuru=='2')?"selected":"";?>></option>
        </select></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><b>Kategori</td>
      <td><select name="kategori" class="butoon">
        <option value="0"  <?echo ($kategori=='0')?"selected":"";?>>Elektronik</option>
        <option value="1"  <?echo ($kategori=='1')?"selected":"";?>>Bilgisayar</option>
        <option value="2"  <?echo ($kategori=='2')?"selected":"";?>>Yazýlým</option>
        <option value="3"  <?echo ($kategori=='3')?"selected":"";?>>Telefon</option>
        <option value="4"  <?echo ($kategori=='4')?"selected":"";?>>Giyim</option>
        <option value="5"  <?echo ($kategori=='5')?"selected":"";?>>Taký</option>
        <option value="6"  <?echo ($kategori=='6')?"selected":"";?>>Saat</option>
        <option value="7"  <?echo ($kategori=='7')?"selected":"";?>>Kýrtasiye</option>
        <option value="8"  <?echo ($kategori=='8')?"selected":"";?>>Ev</option>
        <option value="9"  <?echo ($kategori=='9')?"selected":"";?>>Otomobil</option>
        <option value="10"  <?echo ($kategori=='10')?"selected":"";?>>Beyaz Eþya</option>
        <option value="11"  <?echo ($kategori=='11')?"selected":"";?>>Bahçe</option>
        <option value="12"  <?echo ($kategori=='12')?"selected":"";?>>Çiçek</option>
        <option value="13"  <?echo ($kategori=='13')?"selected":"";?>>Oyuncak</option>
        <option value="14"  <?echo ($kategori=='14')?"selected":"";?>>Kozmetik</option>
        <option value="15"  <?echo ($kategori=='15')?"selected":"";?>>Saðlýk</option>
        <option value="16"  <?echo ($kategori=='16')?"selected":"";?>>Spor</option>
        <option value="17"  <?echo ($kategori=='17')?"selected":"";?>>Müzik</option>
        <option value="18"  <?echo ($kategori=='18')?"selected":"";?>>CD/DVD</option>
        <option value="19"  <?echo ($kategori=='19')?"selected":"";?>>Oyun</option>
        <option value="20"  <?echo ($kategori=='20')?"selected":"";?>>Kitap</option>
        <option value="21"  <?echo ($kategori=='21')?"selected":"";?>>Dergi</option>
        <option value="22"  <?echo ($kategori=='22')?"selected":"";?>>Mobilya</option>
        <option value="23"  <?echo ($kategori=='23')?"selected":"";?>>Diðer</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="Submit" type="submit" class="butoon" accesskey="e" title="Duyuruyu güncelle" value="Güncelle">
      <input name="Submit2" type="reset" class="butoon" accesskey="t" title="Eski verilere geri döner" value="Eski Deðerlere Dön">
      <input name="eklemeVar" type="hidden" id="eklemeVar" value="<?echo $sirano?>"></td>
      <td><span class="baslik style1"><span class="style2">
        <input name="Submit3" type="button" class="style2" accesskey="k" title="Pencereyi Kapat (Alt+K)" onClick="window.close();" value="Kapat">
      </span></span></td>
    </tr>
    <tr>
      <td colspan="3"><em>(*) Doldurulmasý mecburidir. </em></td>
    </tr>
  </table>
</form>
