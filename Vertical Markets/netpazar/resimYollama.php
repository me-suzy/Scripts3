<?
include "conf.php";
session_save_path("$sessPath");
session_start();
session_register("resimAdi");
 if ($HTTP_SESSION_VARS["girisVar"]!='1') die ("<font color=red><b>&raquo;Yetkisiz giriþ algýlandý!</b></font>");
$sirano=$HTTP_GET_VARS["sirano"];
//if (empty($sirano)) die("<font color=red><b>&raquo;Kayýt bilgisi yok!</b></font>");
?>
<?
$storage_dir = "yollanan"; // storage directory (chmod 777)
$max_filesize = 1 * pow(1024,2); // maximum filesize (x MiB)
$allowed_fileext = array("gif","jpg","jpeg","png");// allowed extensions

if (isset($_FILES['file']))
uploadfile($_FILES['file']);

function uploadfile($file) {
	global $storage_dir, $max_filesize, $allowed_fileext, $errormsg;

	if ($file['error']!=0) {
		switch ($file['error']) {
			case 1: $errormsg = "<font color=red><b>&raquo;Dosya boyutunuz fazladýr!</b></font>"; break;
			case 2: $errormsg = "<font color=red><b>&raquo;Dosya boyutunuz fazladýr!</b></font>"; break;
			case 3: $errormsg = "<font color=red><b>&raquo;Dosya tam yollanamadý!</b></font>"; break;
			case 4: $errormsg = "<font color=red><b>&raquo;Hiç dosya göderilemedi!</b></font>"; break;
			case 6: $errormsg = "<font color=red><b>&raquo;Geçici dizin yok!</b></font>"; break;
		}
		return;
	}
	
	$filesource=$file['tmp_name'];

	$filename=$file['name'];
	if (isset($_POST['filename']) && $_POST['filename']!="") $filename=$_POST['filename'];
	if (!in_array(strtolower(extname($filename)), $allowed_fileext)) $filename .= ".badext";


	$filesize=$file['size'];
	if ($filesize > $max_filesize) {
		$errormsg = "<font color=red><b>&raquo;Dosya yollama limiti (".getfilesize($max_filesize).") olmalýdýr.</b></font>";
		return;
	}

	$filedest="$storage_dir/$filename";
	if (file_exists($filedest)) {
		$errormsg = "<font color=red><b>&raquo;$filename dosyasý zaten var!</b></font>";
		return;
	}

	if (!copy($filesource,$filedest)) {
		$errormsg = "<font color=red><b>&raquo;Dosya göderilemedi!</b></font>";
	}else
	{
		$_SESSION["resimAdi"]="".$filename."";
		$errormsg = ("<font color=blue><b>&raquo;$filename dosyasý göderildi!</b> </font>");
	}
}



if (isset($_GET['download']))
downloadfile($_GET['download']);

function downloadfile($file){
	global $storage_dir;
	$file = "$storage_dir/".basename($file);
	if (!is_file($file)) { return; }
	header("Content-Type: application/octet-stream");
	header("Content-Size: ".filesize($file));
	header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
	header("Content-Length: ".filesize($file));
	header("Content-transfer-encoding: binary");
	@readfile($file);
	exit(0);
}


require("Sajax.php");

function deletefile($cell) {
	global $storage_dir;
	$cell=strip_tags($cell);
	//$file=substr($cell,0,strlen($cell)-1);
	$file=$cell;

	$file = "$storage_dir/".basename($file);

	$return = @unlink($file);
	if ($return) return "'$file' dosyasý silindi\n\nSayfayý tazelemeyi unutmayýnýz!"; else return "'$file'\n\ndosyasý silinemedi!";
}
$sajax_request_type = "GET";
sajax_init();
sajax_export("deletefile");
sajax_handle_client_request();
echo "  <link rel=stylesheet type=text/css href=stil.css>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-9" />
  <title>Dosya Yolla - Yönetici</title>
  <meta name="content-language" content="tr" />
  <script type="text/javascript" src="sorttable.js"></script>  
  <script type="text/javascript">
<!--//<![CDATA[
  <?php sajax_show_javascript(); ?>

  var row = null;

  function deletefile_cb(status) {
  	if (status=="OK")
  	row.parentNode.removeChild(row);
  	else {
  		row.className='off';
  		alert(status);
  	}
  	row = null;
  }

  function deletefile(r) {
  	if (row==null) {
  		r.className='delete';
  		var cell = r.cells[0].innerHTML;
  		row = r;
  		x_deletefile(cell, deletefile_cb);
  	}
  }

  function renameSync() {
  	var fn = document.getElementById("file").value;
  	if (fn == ""){
  		document.getElementById("filename").value = '';
  	} else {
  		var b = fn.match(/[\/|\\]([^\\\/]+)$/);
  		document.getElementById("filename").value = b[1];
  	}

  	filetypeCheck();
  }
  function filetypeCheck() {
  	var allowedtypes = '.<? echo join(".",$allowed_fileext); ?>.';

  	var fn = document.getElementById("filename").value;
  	if (fn == ""){
  		document.getElementById("allowed").className ='';
  		document.getElementById("upload").disabled = true;
  	} else {
  		var ext = fn.split(".");
  		if (ext.length==1)
  		ext = '.noext.';
  		else
  		ext = '.' + ext[ext.length-1].toLowerCase() + '.';

  		if (allowedtypes.indexOf(ext) == -1) {
  			document.getElementById("allowed").className ='red';
  			document.getElementById("upload").disabled = true;
  		} else {
  			document.getElementById("allowed").className ='';
  			document.getElementById("upload").disabled = false;
  		}
  	}

  }
//]]>-->
</script>
</head>

<body>
<div id="page">
<div align="center"><span class="style2 style7"><span class="style1 baslik style3"><strong><strong><img src="img/trade.gif" height="30" align="absmiddle" /><span class="style4 style4">&nbsp;Net Pazar</span></strong><span class="style8 style4 style4"> - Yönetici Giriþi - Dosya Yolla</span></strong></span></span></div>

<div id="content">
 	<div id="errormsg">
 	 <p class="red"><? if (isset($errormsg)) {echo $errormsg;} ?></p>
 	</div>
 	<div id="uploadform">
		<form method="post" enctype="multipart/form-data" action="">
		<p>
		  <label for="file"><strong>Dosya Adý&nbsp;&nbsp;</strong> </label>
		  <input type="file" id="file" name="file" size="50" class="butoon" onchange="renameSync();" />
&nbsp;
<input name="submit" type="submit" disabled="disabled" class="butoon" id="upload" value="Gönder" />
&nbsp;<a href="duyuruDuzenle.php?sirano=<?echo $sirano?>">Geri Dön</a> /
<a href="?sirano=<?echo $sirano?>">Tazele</a> 
 </p>
		<p>
		  <label for="filename"><strong>Ad deðiþtir </strong></label>
          <input type="text" id="filename" name="filename" class="butoon" onkeyup="filetypeCheck();" size="50" />
        </p>
		<p class="small"><span id="allowed">Ýzin verilen dosya türleri= <? echo join(", ",$allowed_fileext); ?></span>
		<br />Dosya boyut sýnýrý= <? echo getfilesize($max_filesize); ?></p>
		</form>
 	</div>
	<div id="filelisting">
		<? listfiles($storage_dir); ?>
	</div>
</div>
</div>
</body>
</html>
<?php

function listfiles($dir) {
?>
<hr color="blue" noshade>
<table id="t1" class="sortable" width="500" align="center">
  <tr bordercolor=#FFFF00 bgcolor=#CCCCCC>
    <th id="th1" class="lefted">Dosya Adý</th>
    <th id="th2">Tür</th>
    <th id="th3">Boyut</th>
    <th id="th4" class="unsortable">Silme</th>
  </tr>
<?php
if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".."  && $file != "index.html"&& $file != "index.php") {
			$size=filesize($dir."/".$file);
			$ext=strtolower(extname($file));
			print("<tr class=\"off\" onmouseover=\"if (this.className!='delete') {this.className='on'};\" onmouseout=\"if (this.className!='delete') {this.className='off'};\">");
			printf("<td class=\"lefted\"> <a href=\"?download=$file&sirano=%d\"><img src=\"img/download_arrow.gif\" align= absmiddle alt=\"(download)\"  border=0 title=\"Ýndir\" /></a>",$sirano);
			print("<a href=\"$dir/$file\" target=_blank title='Görüntüle'><img src=\"$dir/$file\" width=30 border=0 align=absmiddle></a>$file</td>");
			
			print("<td>$ext</td>");
			print("<td align=right>".getfilesize($size)."</td>");
			print("<td align=center><a title=\"Sil\" onclick=\"deletefile(this.parentNode.parentNode); return false;\" href=\"\"><img src=\"img/delete.gif\" alt=\"delete\"  border=0 title=\"Dosya sil!\" /></a></td>");
			print("</tr>\n");
		}
	}
	closedir($handle);
}
?>
</table>
<?php
}

function extname($file) {
	$file = explode(".",basename($file));
	return $file[count($file)-1];
}

function getfilesize($size) {
	if ($size < 2) return "$size byte";
	$units = array(' byte', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
	return round($size, 2).$units[$i];
}
?>