<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9">
<link href="stil.css" rel="stylesheet" type="text/css">
<title>Net Pazar</title>
<style type="text/css">
<!--
.style3 {font-size: 18px}
body {
	margin-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
}
-->
</style>
<?
include "conf.php";
	$arama		=($HTTP_POST_VARS["arama"]);
	$aramaTemiz	=temizle($HTTP_POST_VARS["arama"]);
	if ($HTTP_GET_VARS["arama"]!='') 
	 $aramaTemiz=temizle($HTTP_GET_VARS["arama"]);
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function moveLayerToMouseLoc(theLayer, offsetH, offsetV)
{
  var obj;
  if ((MM_findObj(theLayer))!=null)
  {
    if (document.layers)  //NS
    {
      document.onMouseMove = getMouseLoc;
      obj = document.layers[theLayer];
      obj.left = mLoc.x +offsetH;
      obj.top  = mLoc.y +offsetV;
    }
    else if (document.all)//IE
    {
      getMouseLoc();
      obj = document.all[theLayer].style;
      obj.pixelLeft = mLoc.x +offsetH;
      obj.pixelTop  = mLoc.y +offsetV;
    }
//    showHideLayers(theLayer,'','show');
  }
}
// get mouse location
function Point(x,y) {  this.x = x; this.y = y; }
mLoc = new Point(-500,-500);
function getMouseLoc(e)
{
  if(!document.all)  //NS
  {
    mLoc.x = e.pageX;
    mLoc.y = e.pageY;
  }
  else               //IE
  {
    mLoc.x = event.x + document.body.scrollLeft;
    mLoc.y = event.y + document.body.scrollTop;
  }
  return true;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    document.all("resimLayer").innerHTML="<img src=yollanan/"+args[1]+" width=190 border=1>";
    obj.display='block';
	moveLayerToMouseLoc("resimLayer",15,5); //Fare imlecine konumlandýrma
    obj.visibility=v; //layer GÖZÜKÜR
	 }
}
//-->
</script>
<table width="100%"  border="0">
  <tr>
    <td width="76%" align="center" valign="middle" nowrap><div align="center"><span class="style1 baslik style3"><strong><img src="img/trade.gif" width="50" align="absmiddle">&nbsp;Net Pazar'a Hoþgeldiniz!</strong></span></div></td>
    <td width="24%" nowrap><form name="form1" method="post" action="index.php">
      <div align="right">
          <input name="arama" type="text" class="butoon" accesskey="b" title="Duyuru baþlýðý, metni ve gönderen içinde arama yapabilirsiniz. (Alt+B)" value="<?echo $aramaTemiz?>" size="20" maxlength="30">
            <input name="Submit" type="submit" class="butoon" accesskey="a" title="Duyuru arama (Alt+A)" value="Ara">
      </div>
    </form></td>
  </tr>
</table>
<p align="justify" class="style2"><br>
Satýlýk ev, otomobil, bilgisayar, cep telefonu ve benzeri eþyalarýnýz varsa, takas yapmak istiyorsanýz,  duyurunuzu buraya býrakabilirsiniz</p>
<p align="center" class="style2"><a href="ekle.php" target="_blank"><img src="img/ekle.png" alt="ekle" width="16" height="16" border="0" align="absmiddle">Duyuru Ekle!</a></p>
<div align="center"><span class="style2"><em><strong>Kategoriler </strong></em><br>    
<a href="index.php?kategori=0">Elektronik<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=0");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?>
</a>
<a href="index.php?kategori=1">Bilgisayar<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=1");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
 <a href="index.php?kategori=2">Yazýlým<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=2");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
 <a href="index.php?kategori=3">Telefon<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=3");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
  <a href="index.php?kategori=4">Giyim<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=4");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
   <a href="index.php?kategori=5">Taký<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=5");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
   <a href="index.php?kategori=6">Saat<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=6");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
   <a href="index.php?kategori=7">Kýrtasiye<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=7");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
   <a href="index.php?kategori=8">Ev<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=8");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
   <a href="index.php?kategori=9">Otomobil<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=9");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
   <a href="index.php?kategori=10">Beyaz Eþya<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=10");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
    <a href="index.php?kategori=11">Bahçe<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=11");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	<a href="index.php?kategori=12">Çiçek<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=12");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	<a href="index.php?kategori=13">Oyuncak<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=13");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
	 <a href="index.php?kategori=14">Kozmetik<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=14");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	 <a href="index.php?kategori=15">Saðlýk<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=15");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	 <a href="index.php?kategori=16">Spor<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=16");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	 <a href="index.php?kategori=17">Müzik<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=17");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>  
	 <a href="index.php?kategori=18">CD<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=18");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
	 <a href="index.php?kategori=19">Oyun<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=19");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
	  <a href="index.php?kategori=20">Kitap<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=20");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
	   <a href="index.php?kategori=21">Dergi<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=21");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a>
	    <a href="index.php?kategori=22">Mobilya<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=22");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a> 
		<a href="index.php?kategori=23">Diðer<?
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1 and kategori=23");
  if (@mysql_result($raporIst,0,"toplam")!='0')	 
  printf("[%d]",mysql_result($raporIst,0,"toplam"));
?></a></span><br>
   <br>
</div>
<?
    $yol=baglan('');	   
	//EXPIRATION 7 gün				 
   $gelenveriSil=mysql_query("update netpazar set aktif=2 where  (unix_timestamp(now()) - unix_timestamp(kabulTarihi))/3600/24 >7	AND aktif =1",$yol);
   if (!$gelenveriSil)      echo ("<font color=red><b>&raquo;Tablodaki kayýtlar güncellenemedi!</b></font>");
   
   $geelVeri=mysql_query("select resim from netpazar where aktif=2 and resim<>''",$yol);
   if (!$geelVeri)      echo ("<font color=red><b>&raquo;Tablodaki kayýtlar alýnamadý!</b></font>");
		    $i=0;
		   if (mysql_numrows($geelVeri)!=0){
		     while($i<mysql_numrows($geelVeri))        
	         	{
			 $resBilgisi	=mysql_result($geelVeri,$i,"resim");
			 $result		=unlink('yollanan/'.$resBilgisi); //resimler de silinecek
			 $i++;
  		   		} //while
		    }//if
  $gelenveriSil=mysql_query("delete from netpazar where aktif=2",$yol);//DELETEEE
   if (!$gelenveriSil)      echo ("<font color=red><b>&raquo;Tablodaki kayýtlar silinemedi!</b></font>");
   //expire Cleaning finished...
   
	$kategori		=temizle($HTTP_GET_VARS["kategori"]);
	$siralama		=temizle($HTTP_GET_VARS["siralama"]);
	$yon			=temizle($HTTP_GET_VARS["yon"]);
	$ilerleme		=temizle($HTTP_GET_VARS["ilerleme"]); 
	$toplamKayit	=temizle($HTTP_GET_VARS["toplamKayit"]); 
	$yerBlok		=temizle($HTTP_GET_VARS["yerBlok"]); 
	$yonBozulmasin	=temizle($HTTP_GET_VARS["yonBozulmasin"]); 
	if ($yonBozulmasin!='evet') {
 		if($yon=="desc") $yon="asc"; else $yon="desc";
		}
	if ($siralama=='') $siralama='kabulTarihi';
	if ($ilerleme=='') $ilerleme='ilk';
	
	switch($ilerleme){
	case "ilk"	: $yerBlok=0;break;
	case "once"	: $yerBlok-=10;if($yerBlok<0)$yerBlok=0;break;
	case "sonra": $yerBlok+=10;if($yerBlok>=$toplamKayit)$yerBlok=$toplamKayit-10;break;
	case "son"	: $yerBlok=$toplamKayit-10;
	}
	
	if (($kategori)!=''){
     $gelenveri=mysql_query("select *,(unix_timestamp(now()) - unix_timestamp(kabulTarihi) )/3600/24 as GF from netpazar where aktif=1 and kategori='$kategori' order by $siralama $yon limit $yerBlok,10",$yol);
     $gelveri=mysql_query("select * from netpazar where aktif=1 and kategori='$kategori'",$yol);
	 }
	else 
	if ($aramaTemiz!=''){
     $gelenveri=mysql_query("select *,(unix_timestamp(now()) - unix_timestamp(kabulTarihi) )/3600/24 as GF from netpazar where aktif=1 and (konuBaslik like '%$aramaTemiz%' or konuMetni like '%$aramaTemiz%' or gonderen like '%$aramaTemiz%') order by $siralama $yon limit $yerBlok,10",$yol);
     $gelveri=mysql_query("select * from netpazar where aktif=1 and (konuBaslik like '%$aramaTemiz%' or konuMetni like '%$aramaTemiz%' or gonderen like '%$aramaTemiz%')",$yol);
	 }
	else {	
     $gelenveri=mysql_query("select *,(unix_timestamp(now()) - unix_timestamp(kabulTarihi) )/3600/24 as GF from netpazar where aktif=1 order by $siralama $yon limit 0,10",$yol);
     $gelveri=$gelenveri;
	 }
	 
   if (!$gelenveri) 
     die("<font color=red><b>&raquo;Bir hata oluþtu! (2)</b></font>");

?>
<?
           $sirano=1;
           $i=0;
		   if($aramaTemiz!="") $duyuruBaslik="Arama Sonuç Listesi"; else
		   if($kategori!="") $duyuruBaslik="Kategori Listesi"; else
		   $duyuruBaslik="En Son Eklenenler"; 
		   
		   $toplamKayit =mysql_numrows($gelveri); 	//tüm kayýt sayýsý
		   $toplamKayit2=mysql_numrows($gelenveri); //filtreli kayýt sayýsý
		   
		   if ($toplamKayit2 > 0){
  		   	  if ($yon=="asc" && $siralama=='konuBaslik' && $toplamKayit2 > 1)
 			   $siraResmi1=" <img src=img/down.gif height=8 border=0>";
			   else if ($yon=="desc" && $siralama=='konuBaslik' && $toplamKayit2 > 1)
 			   $siraResmi1=" <img src=img/up.gif height=8 border=0>";
			   else
 			   $siraResmi1="";
  		   	  if ($yon=="asc" && $siralama=='kabulTarihi' && $toplamKayit2 > 1)
 			   $siraResmi2=" <img src=img/down.gif height=8 border=0>";
			   else if ($yon=="desc" && $siralama=='kabulTarihi' && $toplamKayit2 > 1)
 			   $siraResmi2=" <img src=img/up.gif height=8 border=0>";
			   else
 			   $siraResmi2="";
  		   	  if ($yon=="asc" && $siralama=='fiyat' && $toplamKayit2 > 1)
 			   $siraResmi3=" <img src=img/down.gif  height=8 border=0>";
			   else if ($yon=="desc" && $siralama=='fiyat' && $toplamKayit2 > 1)
 			   $siraResmi3=" <img src=img/up.gif  height=8 border=0>";
			   else
 			   $siraResmi3="";
	if ($toplamKayit>10) 
	  $ilkeBar=" - 
	  <a href=?ilerleme=ilk&toplamKayit=$toplamKayit&yerBlok=$yerBlok&yon=$yon&siralama=$siralama&arama=$aramaTemiz&kategori=$kategori&yonBozulmasin=evet title='Ýlk 10 duyuru'><img src=img/ilkkayit.gif border=0 width=25 align=absmiddle></a>&nbsp;<a href=?ilerleme=once&toplamKayit=$toplamKayit&yerBlok=$yerBlok&yon=$yon&siralama=$siralama&arama=$aramaTemiz&kategori=$kategori&yonBozulmasin=evet title='Önceki 10 duyuru'><img src=img/oncekikayit.gif border=0 width=25 align=absmiddle></a>&nbsp;<a href=?ilerleme=sonra&toplamKayit=$toplamKayit&yerBlok=$yerBlok&yon=$yon&siralama=$siralama&arama=$aramaTemiz&kategori=$kategori&yonBozulmasin=evet title='Sonraki 10 duyuru'><img src=img/sonrakikayit.gif border=0 width=25 align=absmiddle></a>&nbsp;<a href=?ilerleme=son&toplamKayit=$toplamKayit&yerBlok=$yerBlok&yon=$yon&siralama=$siralama&arama=$aramaTemiz&kategori=$kategori&yonBozulmasin=evet title='Son 10 duyuru'><img src=img/sonkayit.gif border=0 width=25 align=absmiddle></a>	   ";
		   echo "
	<table width=100% border=0 align=center cellpadding=0 cellspacing=1 bordercolor=#FFFF00> 
	  <caption align=top>
	  <font style=style2><b><i>Duyurular - $duyuruBaslik $ilkeBar </b></i> </font>
	  </caption>
	<tr bordercolor=#FFFF00 bgcolor=#CCCCCC>
    <th width=10 align=center>Sýra</th>
    <th width=328 align=center><a href=?siralama=konuBaslik&ilerleme=ilk&toplamKayit=$toplamKayit&yon=$yon&arama=$aramaTemiz&kategori=$kategori>Duyuru Baþlýðý ve Okunma Sayýsý $siraResmi1</a></th>
    <th width=122 align=center><a href=?siralama=kabulTarihi&ilerleme=ilk&toplamKayit=$toplamKayit&yon=$yon&arama=$aramaTemiz&kategori=$kategori>Kabul Tarihi $siraResmi2</a></th>
    <th width=123 align=center><a href=?siralama=fiyat&ilerleme=ilk&toplamKayit=$toplamKayit&yon=$yon&arama=$aramaTemiz&kategori=$kategori>Fiyat $siraResmi3</a></th>
	</tr>";
           while($i<mysql_numrows($gelenveri))        
         {
 $kayno			=mysql_result($gelenveri,$i,"kayno");
 $konuBasligi	=mysql_result($gelenveri,$i,"konuBaslik");
 $tarih1		=mysql_result($gelenveri,$i,"eklemeTarihi");
 $tarih2		=mysql_result($gelenveri,$i,"kabulTarihi");
 $fiyat			=mysql_result($gelenveri,$i,"fiyat");
 $resim			=mysql_result($gelenveri,$i,"resim");
 $paraTuru		=mysql_result($gelenveri,$i,"paraTuru");
 $okuSayac		=mysql_result($gelenveri,$i,"okunmaSayisi");
 if ($paraTuru=='0') $paraTuru='YTL';
 if ($paraTuru=='1') $paraTuru='$';
 if ($paraTuru=='2') $paraTuru='';
 $tarihFarki	=round(mysql_result($gelenveri,$i,"GF"));
 if ($resim!='')
  if (file_exists('yollanan/'.$resim))
	  $resimOnizleme= " onMouseOver=MM_showHideLayers('resimLayer','$resim','show') onMouseOut=MM_showHideLayers('resimLayer','','hide') title='Duyuru resim içeriyor.'";
	  else 
	  $resimOnizleme= " title='Duyurudaki resim dosyasýna ulaþýlamýyor.'";
	  else 
	  $resimOnizleme= "";
 echo "  <tr>
    <td align=right>".($i+1+$yerBlok)."</td>
    <td align=left><a href=duyuruOku.php?sirano=".($kayno)." target=_blank $resimOnizleme>$konuBasligi <font color=green>($okuSayac)</a></td>
    <td align=center>$tarih2 ($tarihFarki günlük)</td>
    <td align=center>$fiyat $paraTuru</td>
  </tr>";
  	$i++;
   		} //while
		}else 
		echo "<br><font color=red><b>&raquo;Bu kategoride duyuru yapýlmamýþ veya arama sonuçsuz kaldý!</b></font>";
	echo "</table>";		
	
	   
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=0");
   if (!$raporIst) 
     die("<font color=red><b>&raquo;Ýstatistik için tabloya eriþilemiyor!</b></font>");
  if (mysql_result($raporIst,0,"toplam")!='0')	 
  printf("<font color=darkgreen><br><b>&raquo;Onay bekleyen duyuru sayýsý: %d adet</b></font>",mysql_result($raporIst,0,"toplam"));
  if ($aramaTemiz!='' && $toplamKayit!=0)	 
   printf("<br><font color=darkgreen><br><b>&raquo;Arama sonucunda bulunan duyuru sayýsý: %d adet</b></font>",$toplamKayit);
  $raporIst= mysql_query("select count(*) as toplam from netpazar where aktif=1");
   if (!$raporIst) 
     die("<font color=red><b>&raquo;Ýstatistik için tabloya eriþilemiyor!</b></font>");
  if (mysql_result($raporIst,0,"toplam")!='0')	 
  printf("<font color=green><br>&raquo;Onaylanmýþ duyuru sayýsý: %d adet</font>",mysql_result($raporIst,0,"toplam"));
  
?>
<div id="resimLayer" style="position:absolute; width:200; height:200; z-index:1; left: 150px; top: 236px; visibility: hidden; overflow: hidden;">gg</div>
<p class="style2">&#8250; Yönetici (<a href="yonetici.php" target="_blank"><img src="img/yonet.png" alt="yonet" width="16" height="16" border="0" align="absmiddle"></a><a href="yonetici.php" target="_blank">Yönetici Giriþi</a>) duyurunuzu <em><strong>uygun</strong></em> bulursa aktif hale getirecektir. <br>
&#8250; Duyurularýnýz kabul tarihinden itibaren <strong><em>bir hafta</em></strong> süre ile aktif kalýr. <br>
&#8250; Sürenizi artýrmak için duyurunuzdaki <em><strong>&quot;Süreyi Uzat&quot;</strong></em> baðýný týklatabilirsiniz. <br>
  <strong><br>
  Programlama:</strong> Tarýk BAÐRIYANIK<br>
  <strong>Tarih:</strong> 28 Temmuz 2005 - 10 Aðustos 2005<br>
  <strong>Süre: </strong>30  Saat, 1650 satýr kod, Sürüm 2.1<br>
  <strong>Program: </strong><a href="http://www.macromedia.com" target="_blank">Macromedia Dreamweaver MX 2004</a>, <a href="http://www.apachefriends.org/en" target="_blank">Xampp</a> <br>
  <br>
  <a href="netpazar.zip"><strong><img src="img/download_arrow.gif" align="absmiddle" border="0">Program Kodlarýný Ýndir! (44KB)</strong></a><br>
  <?
  mysql_close;
?>
</p>
