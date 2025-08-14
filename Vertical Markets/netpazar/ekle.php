<?
include "conf.php";
session_save_path("$sessPath");
session_register("resimAdi");
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
.style9 {font-size: 10px}
-->
</style>
<body  onload="document.form1.konuBasligi.focus();" leftMargin=5 topMargin=5 marginheight=5 marginwidth=5>
<title>Duyuru Ekle</title>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style2 {font-family: Verdana, Arial, Helvetica, sans-serif}
-->
</style>
<p align="center" class="style2 style7"><span class="style1 baslik style3"><strong><img src="img/trade.gif" height="30" align="absmiddle">&nbsp;<span class="style8">Net Pazar</span><span class="style8"> - Duyuru Ekle</span></strong></span></p>
<?
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
 $tarih 		=date("Y-n-j");
 $resim 		=temizle($HTTP_SESSION_VARS["resimAdi"]); 
 $resimsiz 		=temizle($HTTP_GET_VARS["resimsiz"]); 
 if ($resimsiz=='1') $HTTP_SESSION_VARS["resimAdi"]="";
 if ($eklemeVar=='1')
 if ($konuBasligi!='' && $konuMetni!='' && $gonderen!='' && $eposta!='' && $fiyat!='') 
  {
    $yol=baglan('');	   
    $gelenveri=mysql_query("insert into netpazar values('','','$kategori','$konuBasligi',
		    											'$konuMetni','$gonderen','$eposta','$telefon1',
														'$telefon2','$resim','0','$tarih','$fiyat','$paraTuru','0')",$yol);
   if (!$gelenveri) 
     die("<font color=red><b>&raquo;Ýþlem tamamlanamadý.</b></font>");

   $mailbody	="Gondered:$gonderen, Konu Basligi:$konuBasligi \nKonu Metni:$konuMetni\nTarih:$tarih ";
   @mail("apache@tuzlaatl.k12.tr", "Net Pazar'a Duyuru Eklendi!", "$mailbody");

   echo "<font color=blue>&raquo;Duyurunuz kýsa süre sonra eklenecektir. Ýþlem tamamlandý.</font>";
  }else echo "<font color=red><b>&raquo;Eksik býrakýlan alanlar var! Ýþlem tamamlanamadý.</b></font>";
  mysql_close;
?>
<p class="style2">Yönetici duyurunuzu <em><strong>uygun</strong></em> bulursa aktif hale getirecektir. </p>
<form name="form1" method="post" action="ekle.php">
  <table width="775" border="0">
    <tr>
      <td><div align="left"><strong>Resim Gönder </strong></div></td>
      <td>
        <?
	  $reism=$HTTP_SESSION_VARS["resimAdi"];
	  if ($reism!="") {
	   echo "<a href=yollanan/$reism target=_blank title=Görüntüle><img src=yollanan/$reism width=70 height=50 align=absmiddle border=0></a>"; 
	   echo " <a href=ekle.php?resimsiz=1>Resim Seçimini Ýptal Et</a> "; 
	   }
	   else echo "Resim yok &lt;<a href=resimYolla.php>Gönder</a>&gt;";
	  ?>
      </td>
      <td><span class="style9"><div align="left">Eðer resimli duyuru ekleyecekseniz, diðer bilgileri doldurmadan önce resminizi yollayýnýz. Yoksa bilgilerinizi tekrar yazmanýz gerekir.</div> </span></td>
    </tr>
    <tr>
      <td width="192"><div align="left"><strong><b>Duyuru Baþlýðý </strong></div></td>
      <td width="337"><input name="konuBasligi" type="text" class="butoon" size="30" maxlength="30"></td>
      <td width="232"><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Duyuru Metni </strong></div></td>
      <td><textarea name="konuMetni" cols="50" rows="5" class="butoon"></textarea></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Gönderenin Ad Soyadý </strong></div></td>
      <td><input name="gonderen" type="text" class="butoon" size="30" maxlength="30"></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>E-posta Adresiniz </strong></div></td>
      <td><input name="eposta" type="text" class="butoon" value="@" size="30" maxlength="30"></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Cep Telefonunuz </strong></div></td>
      <td><input name="telefon1" type="text" class="butoon" size="15" maxlength="15"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Ev Telefonunuz</strong></div></td>
      <td><input name="telefon2" type="text" class="butoon" size="15" maxlength="15"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Fiyat</strong></div></td>
      <td><input name="fiyat" type="text" class="butoon" value="0">
        <select name="paraTuru" class="butoon">
          <option value="0" selected>YTL</option>
          <option value="1">$</option>
          <option value="2"></option>
        </select></td>
      <td><span class="style7">*</span></td>
    </tr>
    <tr>
      <td><div align="left"><strong><b>Kategori</strong></div></td>
      <td><select name="kategori" class="butoon">
        <option value="0">Elektronik</option>
        <option value="1">Bilgisayar</option>
        <option value="2">Yazýlým</option>
        <option value="3">Telefon</option>
        <option value="4">Giyim</option>
        <option value="5">Taký</option>
        <option value="6">Saat</option>
        <option value="7">Kýrtasiye</option>
        <option value="8">Ev</option>
        <option value="9">Otomobil</option>
        <option value="10">Beyaz Eþya</option>
        <option value="11">Bahçe</option>
        <option value="12">Çiçek</option>
        <option value="13">Oyuncak</option>
        <option value="14">Kozmetik</option>
        <option value="15">Saðlýk</option>
        <option value="16">Spor</option>
        <option value="17">Müzik</option>
        <option value="18">CD/DVD</option>
        <option value="19">Oyun</option>
        <option value="20">Kitap</option>
        <option value="21">Dergi</option>
        <option value="22">Mobilya</option>
        <option value="23">Diðer</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="Submit" type="submit" class="butoon" accesskey="e" title="Duyuruyu ekle" value="Ekle">
      <input name="Submit2" type="reset" class="butoon" accesskey="t" title="Formu temizler" value="Temizle">
      <input name="eklemeVar" type="hidden" id="eklemeVar" value="1"></td>
      <td><span class="baslik style1"><span class="style2">
        <input name="Submit3" type="button" class="style2" accesskey="k" title="Pencereyi Kapat (Alt+K)" onClick="window.close();" value="Kapat">
      </span></span></td>
    </tr>
    <tr>
      <td colspan="3"><em>(*) Doldurulmasý mecburidir. </em></td>
    </tr>
  </table>
</form>
