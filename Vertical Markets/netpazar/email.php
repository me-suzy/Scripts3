<?
include "conf.php";
?>
<Title>Eposta At</title>
<link rel="stylesheet" href=stil.css>
<meta http-equiv=Content-Type content="text/html; charset=Windows-1254">
<meta http-equiv=Content-Language content=tr>
<body  leftMargin=10 topMargin=10 marginheight=10 marginwidth=10>
<h1 align="center"><span class="style2"><span class="style1 baslik style3"><strong><img src="img/trade.gif"  height="30" align="absmiddle">&nbsp;</strong></span></span><span class="style3"><strong>Net Pazar</strong> - Eposta At</span></h1>
<?php
function hata($metin,$out)
{
echo "<font class='lessbigred'>".$metin;
echo "<br><a href=# onclick=window.close(); target=_self><font color=red><b>&raquo;Ýþlem tamamlanamadý.</b></font></a></font>";
exit;
return;
}

$conn_id=baglan('');
		
if (!$conn_id) hata("$errLevel1",$close);

$address	= $HTTP_GET_VARS["address"];
$address2	= $HTTP_POST_VARS["address2"];
if (empty($address)) $address=$address2;
$subject	= $HTTP_POST_VARS["subject"];
$bodisi		= $HTTP_POST_VARS["bodisi"];

if (empty($address)) hata("$errLevel11",$close);
   		
	if ($bodisi!=''){	
        if (@mail("$address2", "$maintitle $subject", "$bodisi"))
			 echo ("&raquo;Mesajýnýz gönderildi.");
			 else
			 echo ("&raquo;Mesajýnýz gönderilemedi!");
		echo "<br><button value=Kapat  onclick=window.close();  accesskey=k title='Pencereyi Kapat (Alt+K)' >Kapat</button>";
 	}else{
		echo "
		      <form method=post name=Form1 ACTION=email.php >
		       <input type=hidden name=address value='$address'>
		       <input type=hidden name=address2 value='$address'>
		       <b>Konu<br><input type=text name=subject size=35  class=butoon><br>
		      <b> Mesaj Metni<br><textarea name=bodisi rows=10 cols=70  class=butoon></textarea>
		       <br><button TYPE=submit VALUE=Tamam NAME=Submit class=butoon>Gönder</button>
		      &nbsp;<button value=Kapat  onclick=window.close();  accesskey=k title='Pencereyi Kapat (Alt+K)' >Kapat</button>
		      &nbsp;<button value='Geri Dön'  onclick='history.go(-1);'  title='Önceki sayfaya git' >Geri Dön</button>
		      </form><br>Eposta Adresi: $address<br>
			  <font color=red><b>&raquo;Mesaj metnine baðlantý adresinizi veya telefonunuzu belirtmeyi unutmayýnýz! Yoksa sizinle baðlantýya geçilemez.</b></font>
		      ";
	     }
?>
</body>
<STYLE TYPE=text/css> 
<!-- 
A:link{text-decoration:none}
A:visited{text-decoration:none}
A:active{text-decoration:none}

BORDER-LEFT:#1F9FFF 0px solid;BORDER-RIGHT:#1F9FFF 0px solid;
BORDER-TOP:#1F9FFF 0px solid;COLOR:#1F9FFF;
FONT-WEIGHT:bold}body{scrollbar-face-color:#f2f2f2;scrollbar-shadow-color:#0c88e4;
scrollbar-highlight-color:#0c88e4;scrollbar-3dlight-color:#f2f2f2;scrollbar-darkshadow-color:#f2f2f2;
scrollbar-track-color:#f2f2f2;scrollbar-arrow-color:#0c88e4;;border-top-width:thin
}
-->
