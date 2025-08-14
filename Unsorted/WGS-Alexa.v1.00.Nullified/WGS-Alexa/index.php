<?
srand((double)microtime()*1000000);
do{$tFile="t".rand(0,999999).".tmp";}while(file_exists("temp/".$tFile));
$msg="";
function post($value){return htmlspecialchars(trim(urldecode(@$_POST[$value])));}
function ispost($value){return (strlen(trim(urldecode(@$_POST[$value])))!=0)?true:false;}

if(ispost("sbSend")){
  switch(post("sbSend")){
  case "Upload":
    if(is_uploaded_file($_FILES["file"]["tmp_name"])){
	  if(move_uploaded_file($_FILES["file"]["tmp_name"],"proxy/".$_FILES["file"]["name"])){	   
	    $msg="File ".$_FILES["file"]["name"]." uploaded";	   
	  }else{
	    $msg="File ".$_FILES["file"]["name"]." not uploaded";
	  }
	}
  break;
  case "Delete":
    if(ispost("slProxyList")){
      if(unlink("proxy/".post("slProxyList")))
        $msg="File ".post("slProxyList")." deleted";
	  }else{
	    $msg="File ".post("slProxyList")." not deleted";
	  }
  break;
  case "Generate users":
    if(ispost("edUrl")){
      $res=post("edUrl")."@@".(ispost("edRef")?post("edRef"):"0")."@@".post("slProxy")."@@".(isset($_POST["chWait"])?"1":"0")."@@".(ispost("edPerH")?post("edPerH"):"0")."@@".(ispost("edMax")?post("edMax"):"0");
	  $file=fopen("temp/".$tFile,"a");
	  if($file){
	    fputs($file,$res."\n");		
	    fclose($file);
		$tt="http://www.yoursite.com/alexa/task.php?tmp=".$tFile;
		$msg = "Generation users. Results see in new window. Disable Pop up Killers";
	  }
    }else{
      $msg="Url is not indicated";
    }
  break;
  }
}
?>
<html>
<head><title>Script and Fake Hit Generator</title>
<script type="text/javascript" language="JavaScript">
<!--
function breakAll(){
document.mForm.edTest.value="break";
document.mForm.edRef.value="break";
document.mForm.edProxy.value="break";
document.mForm.sbb.Submit();
//alert();
//parent.set.location.href="set.php?var="+document.mForm.edTest.value+"@@"+document.mForm.edRef.value+"@@"+document.mForm.edProxy.value;
}
<?
if(isset($tt)){
  printf("var v=window.open(\"".$tt."\");\n");
}
?>
-->
</script>

</head>
<style type="text/css">
  body,td{font-size: 10px;font-family : Verdana, Arial, Helvetica;}
  .file{font-family: Verdana, sans-serif; font-size: 11px;}
</style>
<body>
<h3 align="center">Fake Hit Generator</h3>
<TABLE width="100%">  
  <TR><TD align=middle>Message: Always use fresh Proxies Get New Proxies Every Month <?printf($msg);?></TD></TR>  
  <TR><TD><form name="mForm" enctype="multipart/form-data" method="post" >
      <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
        <TR><TD align=right>Url:</TD>
          <TD width=10></TD><TD><INPUT class=file size=90 name=edUrl></TD>
          <TD></TD></TR>
		<TR><TD align=right>Referer (optional):</TD><TD></TD>
          <TD><INPUT class=file size=90 name=edRef></TD><TD></TD></TR>
        <TR><TD align=right nowrap>Wait for notify from server:</TD><TD></TD>
          <TD><INPUT class=file type=checkbox value="" name=chWait></TD><TD></TD></TR>
        <TR><TD align=right>List of unique ip:</TD><TD></TD>
          <TD><SELECT class=file name=slProxy><?$h=opendir("proxy"); while(FALSE!=($file=readdir($h))){if($file!="." && $file!=".."){echo "<option value=\"".$file."\">".$file."</option>";}}?></SELECT></TD><TD></TD></TR>
        <TR><TD align=right>Users per hour (optional):</TD><TD></TD>
          <TD><INPUT class=file name=edPerH size="6" maxlength="6"></TD><TD></TD></TR>
        <TR><TD align=right>Max users (optional):</TD><TD></TD>
          <TD><INPUT class=file name=edMax size="6" maxlength="6"></TD><TD></TD></TR>
        <TR><TD></TD><TD></TD><TD><INPUT class=file onclick=addTask() type=submit value="Generate users" name=sbSend></TD><TD></TD></TR>
        <TR><TD></TD><TD></TD><TD></TD><TD></TD></TR></TABLE></form></TD></TR>
  <TR><TD><hr></TD></TR>
  <TR><TD>
      <FORM id=frmAdd name=frmAdd action="" method=post encType=multipart/form-data>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>        
        <TR><TD align=right>List of unique ip:</TD><TD width=10></TD>
          <TD><SELECT class=file name=slProxyList><?$h=opendir("proxy"); while(FALSE!=($file=readdir($h))){if($file!="." && $file!=".."){echo "<option value=\"".$file."\">".$file."</option>";}}?></SELECT></TD></TR>        
        <TR><TD align=right nowrap>Upload unique ip List:</TD><TD width=10></TD><TD><INPUT class=file type=file name=file></TD></TR>
        <TR><TD align=right><INPUT class=file type=submit value=Delete name=sbSend></TD><TD></TD><TD><INPUT class=file type=submit value=Upload name=sbSend></TD></TR></TABLE></FORM></TD></TR>
  <TR><TD><hr></TD></TR></TABLE>

           <p align="center"><br>Copyright © 2004 <!--CyKuH [WTN]-->HeyMichelles Scripts<br><br>
            </span></p>


</body>
</html>
