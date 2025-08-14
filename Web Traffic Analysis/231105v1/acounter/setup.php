<?php

//////////////////////////////////////////////////////////////////////////
// Script:        setup.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

$ENameCoF='';

if(isset($_POST['stest']))
       {
        if(!isset($_POST['spath'])) $rpath="";
        else $rpath=$_POST['spath'];
        if(!isset($_POST['surl'])) $rurl="";
        else $rurl=$_POST['surl'];
        $rpath2=get_path($rpath);
        if(!strcmp($rpath2,'31')) out_res("#FF0000","Fail","#FF0000","Incorrect Path");
        $rpath=$rpath2;
        $rurl2=get_path($rurl);
        if(!strcmp($rurl2,'31')) out_res("#FF0000","Fail","#FF0000","Incorrect URL");
        $rurl=$rurl2;
       }
else
       {
        $res=getPath();
        if($res) $rpath="";
        $res=getURL();
        if($res) $rurl="";
       }
if(!$rpath) out_res("#FF0000","Fail","#FF0000","Path not found");
elseif(!$rurl) out_res("#FF0000","Fail","#FF0000","URL not found");
else
       {
        $res=test();
        if($res) out_res("#FF0000","Fail","#FF0000",$cerr);
        else
                {
                 $res=saveConf();
                 if($res) out_res("#FF0000","Fail","#FF0000",$cerr);
                 else out_res("#009900","Ok","#009900","Setup is successfully completed");
                }
       }

function getPath() {
  global $rpath,$cerr;
  if(!isset($_SERVER['PATH_TRANSLATED'])) {
    if((!isset($_SERVER['DOCUMENT_ROOT']))||(!isset($_SERVER['PATH_INFO']))) return 1;
    $rpath=$_SERVER['DOCUMENT_ROOT'].$_SERVER['PATH_INFO'];
  }
  else $rpath=$_SERVER['PATH_TRANSLATED'];
  $rpath=get_path($rpath);
  if(!strcmp($rpath,'31')) return 1;
  return 0;
}

function getURL() {
  global $rurl,$cerr;
  $rurl="http://";
  if(!isset($_SERVER['HTTP_HOST'])) {
    if(!isset($_SERVER['SERVER_NAME'])) return 2;
    $rurl.=$_SERVER['SERVER_NAME'];
  }
  else $rurl.=$_SERVER['HTTP_HOST'];
  if(!isset($_SERVER['REQUEST_URI'])) {
    if(!isset($_SERVER['PATH_INFO'])) return 3;
    $rurl.=$_SERVER['PATH_INFO'];
  }
  else $rurl.=$_SERVER['REQUEST_URI'];
  $rurl=get_path($rurl);
  if(!strcmp($rurl,'31')) return 2;
  return 0;
}

function test() {
  global $rpath,$rurl,$cerr;
  $sdata[0]="<?php echo 'test'; ?>";
  $res=save_to_file($rpath.'test.php',$sdata);
  if($res) return $res;
//  $res=read_from_file($rurl.'test.php',$rdata);
//  if($res) return $res;
//  if(strcmp($rdata[0],'test')) {$cerr="Incorrect result of test";return 4;}
  unlink($rpath.'test.php');
  return 0;
}

function saveConf() {
  global $rpath,$rurl;
  $res=read_from_file($rpath.'config/conf_dat.php',$sdata);
  if($res)
         {
          if($res!=21) return $res;
          $fdata[0]="<?php die('Access restricted');?>\n";
          $res=save_to_file($rpath.'config/conf_dat.php',$fdata);
          if($res) return $res;
          $res=read_from_file($rpath.'config/conf_dat.php',$sdata);
          if($res) return $res;
         }

  $setpath=0;
  $seturl=0;
  reset($sdata);
  $elem=each($sdata);
  while($elem=each($sdata))
         {
          if(strstr($elem[1],'FullBasePath'))
                {
                 $setpath=1;
                 $sdata[$elem[0]]='FullBasePath='.$rpath."\n";
                }
          elseif(strstr($elem[1],'FullUrl'))
                {
                 $seturl=1;
                 $sdata[$elem[0]]='FullUrl='.$rurl."\n";
                }
         }
  if(!$setpath) $sdata[]='FullBasePath='.$rpath."\n";
  if(!$seturl) $sdata[]='FullUrl='.$rurl."\n";

  $res=save_to_file($rpath.'config/conf_dat.php',$sdata);
  if($res) return $res;
  return 0;
}

function save_to_file($name,$fdata) {
  global $cerr;
  $fd=fopen($name,'w');
  if(!$fd) {$cerr="Can't create file '$name'";return 11;}
  chmod($name,0755);
  flock($fd,2);
  reset($fdata);
  while($elem=each($fdata)) {
    if(!fwrite($fd,$elem[1])) {$cerr="Can't write into the file '$name'";return 12;}
  }
  flock($fd,3);
  fclose($fd);
  return 0;
}

function read_from_file($name,&$fdata) {
  global $cerr;
  if((!strstr($name,'http://'))&&(!strstr($name,'ftp://')))
    if(!file_exists($name)) {$cerr="File '$name' is not found";return 21;}
    $file=fopen($name,'r');
    if(!$file) {$cerr="Can't open file '$name'";return 22;}
    flock($file,2);
    while($f=fgets($file,1024)) if($f) $fdata[]=$f;
    flock($file,3);
    fclose($file);
  if(!$fdata) {$cerr="Can't read from file '$name'";return 22;}
  return 0;
}

function get_path($path) {
  eval("\$path=\"$path\";");
  $plast=split("[\\]",$path);
  if(count($plast)<2) $plast=split("/",$path);
  if(!$plast) return 31;

  $cend=count($plast)-1;
  $plast[$cend]='';
  $path=join("/",$plast);
  return $path;
}

function out_res($colT,$Test,$colM,$Mess) {
  global $rpath,$rurl;

echo <<< EOF
<html>
<head>
<title>Actual Counter 1.21 (Free Version) - Setup</title>
<LINK href="./template/css/main.css" type=text/css rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript">
<!--
function goWindow(url) {window.location.href=url;}
//-->
</SCRIPT>
</head>
<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<div align="center">
<form method="post" action="setup.php">
    <table bgcolor="#666699" border="0" cellspacing="1" cellpadding="0"><tr><td>
    <table width=610 bgcolor="#333366" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan=3 height=40 valign=center align=center>
    <table bgcolor="#999999" border="0" cellspacing="1" cellpadding="0"><tr><td valign="top">
          <table bgcolor="#CCCCCC" width="600" height="26" border="0" cellspacing="0" cellpadding="1">
            <tr valign=center>
              <td align=left bgcolor="#CCCCCC">
                <b><font size=2>
                  <SPAN class="f12">&nbsp;&nbsp;Setup</SPAN>
                </font></b>
              </td>
              <td align=right bgcolor="#CCCCCC">
                <input class=b3 type="button" value="Admin" onclick="goWindow('./admin.php')">
                <input class=b3 type="button" value="View" onclick="goWindow('./view.php')">
                <input class=b3 type="button" value="Help">
              </td>
            </tr>
          </table>
    </td></tr></table>
        </td>
      </tr>
      <tr>
        <td width=5>&nbsp;</td>
        <td valign=top height=160 width=600 bgcolor="#CCCCCC">
          <table width="600" height="26" border="1" cellspacing="0" cellpadding="0">
            <tr valign=center>
              <td width=80 align=center>
                <font size=2>
                  <SPAN class="f12">Path</SPAN>
                </font>
              </td>
              <td width=520 align=left>
                  <input type="text" class=box4 size=63 name=spath value="$rpath">
              </td>
            </tr>
            <tr valign=center>
              <td width=80 align=center>
                <font size=2>
                  <SPAN class="f12">URL</SPAN>
                </font>
              </td>
              <td width=520 align=left>
                  <input type="text" class=box4 size=63 name=surl value="$rurl">
              </td>
            </tr>
            <tr valign=center>
              <td width=80 align=center>
                <font size=2>
                  <input class=b7 type="submit" name="stest" value="Test">
                </font>
              </td>
              <td width=520 align=center>
                <b><font size=2 color="$colT">
                  <SPAN class="f12">$Test</SPAN>
                </font></b>
              </td>
            </tr>
            <tr>
              <td colspan=2 width=600 height=1></td>
            </tr>
            <tr>
              <td height=100 colspan=2 align=center>
                <b><font size=2 color="$colM">
                  <SPAN class="f12">$Mess</SPAN>
                </font></b>
              </td>
            </tr>
          </table>
        </td>
        <td width=5>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=3 width="100%">
    <table bgcolor="#333366" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
        <td width=5>&nbsp;</td>
        <td height=20 width=210 align="left"><font size=2 color="#FFFFFF">
            <SPAN class="f9">Actual Counter 1.21 (Free Version)</SPAN>
        </font></td>
        <td width=390 align="right"><font size=2 color="#FFFFFF">
            <SPAN class="f9">Copyright &copy; 2002 <a href="http://www.actualscripts.com/" target="_blank"><b>Actual Scripts</b></a> Company. All Rights Reserved.</SPAN>
        </font></td>
        <td width=5>&nbsp;</td>
    </td></tr></table>
        </td>
      </tr>
    </table>
    </td></tr></table>
</form>
</div>
</body>
</html>

EOF;

exit;
}

?>