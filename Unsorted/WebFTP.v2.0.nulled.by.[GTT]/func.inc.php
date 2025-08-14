<?

///////////////////////////////////////////////////////////
#
#  The Maxg Network 2003-2004 - WebFTP²
# ---------------------------------------------
# This file contains the functions used by the script.
# Language files are stored in 'lang/' folder and
# templates in 'template/' folder.
# ---------------------------------------------
#
//////////////////////////////////////////////////////////

# -------------------------------------
#  Constants of the script, explained
#  on the online documentation
# -------------------------------------
define('FTPLOG_ERROR',2);
define('FTPLOG_STARTPAGE',3);
define('FTPLOG_LOGIN',5);
define('FTPLOG_LOGUSERDATA',7);
define('FTP_VERION','2.0ß');
# -------------------------------------
define('YES',1); define('NO',0);
define('OUI',1); define('NON',0);
define('JA',1); define('NEIN',0);
define('SI',1); define('SIM',1);
# -------------------------------------
define('SORT_NAME',6); define('SORT_SIZE',4);
define('SORT_DATE',5); define('SORT_OWNER',2);
define('SORT_GROUP',3);
# -------------------------------------

$ok = @include 'config.php';
if(!$ok) start_error("There is an error in your config file !<br>Please check it !");

$conf = new Config;

function start_error($err)
{
 die('<html><body><table style="border:1px solid red;background:#FFEFEF;width:60%;margin-top:50px;height:85px;" align="center" border=0 cellspacing=0>
 <tr><td colspan=2 style="height:25px;border-bottom:1px solid red;" align="center"><b>- Startup error ! -</b></td></tr><tr><td width=150 align="center">
 <img src="img/err.gif"></td><td>'.$err.'</td></tr></table></body></html>');
}

unset($lg); @chmod("temp",0777);
if(isset($_GET['webftp_setlang']))
{
  if(file_exists('lang/'.$_GET['webftp_setlang'].'.lang.php'))
  {
    setCookie('webftp_lang',$_GET['webftp_setlang']);
    $lg = $_GET['webftp_setlang'];
    $_GET['status'] = 'newlang';
  }
  else{ $_GET['status'] = 'badlang'; $lg = $conf->d_lang; }
}
elseif(isset($_COOKIE['webftp_lang']))
{
  if(file_exists('lang/'.$_COOKIE['webftp_lang'].'.lang.php'))
   $lg = $_COOKIE['webftp_lang'];
  else{ $_GET['status'] = 'badlang'; $lg = $conf->d_lang; }
}
else $lg = $conf->d_lang;

$ok = @include('lang/'.$lg.'.lang.php');
if(!$ok)
{
  start_error("Specified language doesn't exists or contains errors !<br>Check your config.php file (".$lang.")");
}

#
# Some features need PHP 4.2+
#
if(!function_exists('version_compare'))
{
   //////////////////////////////TODO/////////////////////////////////////
  start_error("You PHP version is too old to run WebFTP correctly.<br>Please upgrade your PHP version.");
}

# Some parameters needed in PHP.INI
@ini_set("session.name","WebFtpID");
@ini_set("session.use_cookies","0");
@ini_set("url_rewriter.tags","");

#
# The little + of security : check if IP, browser and language didn't change
#
function clesec($ps)
{
  return md5($ps.getenv('REMOTE_ADDR').getenv('HTTP_USER_AGENT').getenv('HTTP_ACCEPT_LANGUAGE'));
}

#
# Check if a session is valid
#
function verif_session()
{
  session_start();

  if(!isset($_SESSION['pass'],$_SESSION['user'])) return false;
  if($_SESSION['md5'] != clesec($_SESSION['pass'])) die(header("Location: index.php?status=badhash"));

  return true;
}

#
# Parse the structure of a template
# See the manual for more informations
#
function parse_template($file,$cp)
{
  global $conf,$lang;
  $f = @implode('',@file("template/$file"));
  if(!$f) start_error("Error reading template $file !");  ////////////////////////TODO

  for($i=0,$j=substr_count($f,"#FOREACH:");$i<$j;$i++)
  {
    $tmp=substr($f,strpos($f,"[$i#FOREACH:")+11,strlen($f));
    $var=substr($tmp,0,strpos($tmp,"]"));
    $val=substr($tmp,strpos($tmp,"]")+1,strlen($tmp));
    $val=substr($val,0,strpos($val,"[$i#ENDEACH]"));
    $idx=substr_count($val,"#SUBIF:");

    for($k=0,$r="";$k<$cp["$var"];$k++)
    {
      $r.=preg_replace("/\[([0-9]*)#SUB/e",'"[".(\\1+($k*$idx))."#SUB"',
          eregi_replace("\{([A-Z]*)\\\$\}","{\\1$k}",$val));
    }
    $f=eregi_replace("\[$i#FOR.*\[$i#ENDEACH\]",$r."[#BUILDSUBINDEXES#]",$f);
  }

  $f = preg_replace("/\{LANG:([0-9]*)\}/e",'$lang[\\1]',$f);
  $f = preg_replace("/\{([A-Z]*)\}/e",'$cp["\\1"]',$f);
  $f = preg_replace("/\{([A-Z]*)([0-9]*)\}/e",'$cp["\\1"]["\\2"]',$f);

  for($i=0,$j=substr_count($f,"#IF:");$i<=$j;$i++){
   $f = ereg_replace("\[$i#IF:0\](.*)\[$i#ENDIF\]","",$f);
   $f = ereg_replace("\[$i#IF:.?.?.?\](.*)\[$i#ENDIF\]","\\1",$f);
  }

  $g = explode("[#BUILDSUBINDEXES#]",$f); $f="";
  foreach($g as $h)
  {
    $h = ereg_replace("\[SUBIF:0\]([^¤]*)\[¤SUBENDIF\]","",$h);
    $h = ereg_replace("\[SUBIF:.?.?.?\]([^¤]*)\[¤SUBENDIF\]","\\1",$h);
    $f.=$h;
  }

  $f = preg_replace("/\[##CODE:(.*)##\]/e",'\\1',$f);

  # ~~~~~~~~~ :-D ~~~~~~~~
  return($f);
}

#
# Return a transmated string
#
function t($id)
{
  global $lang;
  return $lang[$id];
}

#
# Connect to FTP server with many options
# Some features are recent, that's why PHP 4.2+ is needed
#
function ftpc($host,$port,$timeout,$boolsecure,$user,$pass,$ftpcod,$ordir,$boolpassif)
{
  $pass = base64_decode($pass);

  if($boolsecure == TRUE)
  {
   if(!function_exists('ftp_ssl_connect')) die(header("Location: index.php?status=badssl"));
   $conid = @ftp_ssl_connect(@gethostbyname($host), $port, (int)$timeout);
  }

  else $conid = @ftp_connect(@gethostbyname($host), $port, (int)$timeout);
  if(!$conid) die(header("Location: index.php?status=badserv"));

  ftp_set_option($conid, FTP_TIMEOUT_SEC, (int)$timeout);

  $login = ftp_login($conid, $user, $pass);
  if(!$login) die(header("Location: index.php?status=badlog"));

  if($boolpassif) if(!ftp_pasv($conid,TRUE))
    die(header("Location: index.php?status=badpasv"));

  define('FTPMODE', $ftpcod);
  $ok = ftp_chdir($conid, $ordir);
  if(!$ok) die(header('Location: index.php?status=badfold'));

  if(!isset($_SESSION['syst'])) $_SESSION['syst']=ucfirst(strtolower(ftp_systype($conid)));

  return $conid;
}

#
# Parse a raw FTP list and return unsorted array
#
function ftp_parsedir($ftp,$search)
{
  if(strtoupper(substr($search,0,2))=="@U") $search=trim(substr($search,2));
  $list = ftp_rawlist($ftp,$search);

  if(!is_array($list)){
   ftp_pwd($ftp);
  # Try a second attempt ?
  if(!is_array($list = ftp_rawlist($ftp,$search)))
  {
   global $MSG; $MSG="An error occured while reading the folder contents";
   return array();
  }}

  foreach($list as $f)
  {
    list($mod,$o)=explode(' ',$f,2);
    list($num,$o)=explode(' ',ltrim($o),2);
    list($own,$o)=explode(' ',ltrim($o),2);
    list($grp,$o)=explode(' ',ltrim($o),2);
    list($siz,$o)=explode(' ',ltrim($o),2);
    list($mon,$o)=explode(' ',ltrim($o),2);
    list($day,$o)=explode(' ',ltrim($o),2);
    list($yet,$f)=explode(' ',ltrim($o),2);
    $ret[]=Array($mod,$num,$own,$grp,$siz,"$day $mon $yet",$f,$mod[0]=='d'?1:0);
  }

  return (is_array($ret)?$ret:Array());
}

#
# Micro Timer - used many time
#
function gm()
{
 list($usec, $sec) = explode(" ",microtime());
 return ((float)$usec + (float)$sec);
}

#
# Create the list of the extensions with icons
#
$v_exts = array();
$d = opendir('img/ic'); readdir($d); readdir($d);
while($f=readdir($d)) if(substr($f,-4)==".gif") $v_exts[] = substr($f,0,-4);

#
# Return a name of an icon file if it exists
# or a default icon or folder icon.
#
function get_ext($i)
{
 global $v_exts;
 $ext = explode('.',strtolower($i));
 $ext = $ext[count($ext)-1];

 if(@in_array($ext, $v_exts)) return 'ic/'.$ext;

 return 'file';
}

#
# Order a function by key using array_multisort
# with dynamical key arguments for priority and sorting options
#
function array_csort()
{
  $args = func_get_args(); $i=0;
  $marray = array_shift($args);

  $msortline = "return(array_multisort(";
  foreach ($args as $arg)
  {
    $i++;
    if (is_string($arg))
    {
      foreach ($marray as $row) $sortarr[$i][] = $row[$arg];
    }
    else $sortarr[$i] = $arg;
    $msortline .= "\$sortarr[".$i."],";
  }
  $msortline .= "\$marray));";

  @eval($msortline);
  return $marray;
}

#
# Order the file list using some multi-dimentionnal key
#
function tripar($data)
{
  if(!is_array($data)) return array();
  $tripar = $_SESSION['tripar'];
  $ordre = $_SESSION['ordre'];


  if($tripar == SORT_NAME)
  {
    $n = array();
    foreach($data as $d) $n[] = strtolower($d[6]); if($ordre) array_reverse($n);
    return array_csort($data, "7", SORT_DESC, $n, SORT_STRING, ($ordre ? SORT_DESC : SORT_ASC));
  }

  return array_csort($data, "7", SORT_DESC, "$tripar", ($tripar==4?SORT_NUMERIC:SORT_REGULAR), ($ordre ? SORT_DESC : SORT_ASC));
}

#
# Recursive function to delete a non-empty directory
#
function ftp_deldir($ftp, $dir)
{
  $list = @ftp_nlist($ftp,$dir);
  if(!is_array($list))
  {
   $l = ftp_parsedir($ftp,$dir);
   if(is_array($l)){ $list = array();
   foreach($l as $u) if(!empty($u[6])) $list[] = $u[6];}
  }

  if (is_array($list))
  {
   foreach($list as $f)
   {
     $f = basename($f);
     if (ftp_size($ftp, $dir.$f) < 0)
       $err = @ftp_deldir($ftp, $dir.$f."/");
     else
       $err = @ftp_delete($ftp, $dir.$f);
     if($err !== TRUE) return ($err==FALSE? $dir.$f :$err);
   }
  }else return $dir;
  if(!@ftp_rmdir($ftp, $dir)) return $dir;
  return TRUE;
}

#
# What a strange function. Recursivity for parsing
# file(s) and/or dirs and add them to a TAR archive
#
function tar_ftp_dir($ftp,$tar,$fp,$name,$stat)
{
  # Try to avoid timeout :-/
  @set_time_limit(20); header('X-WebFTP-Ping: Pong!',0);

  if(substr($name,-1)=="/")
  {
    # This is a dir ... Let's see the contents and fetch this
    if(!@ftp_chdir($ftp,$name)) return $stat;
    $dir = @ftp_nlist($ftp,"."); if(!$dir) return $stat;
    foreach($dir as $d)
    {
      $d = $name.$d; $is_d = @ftp_size($ftp,$d)<0 ? 1 : 0;
      $stat = tar_ftp_dir($ftp,$tar,$fp,$d.($is_d ? '/' : ''),$stat);
      if($is_d) $stat['addirs']++;
    }
  }
  else
  {
    # This is a file, get the contents and add it to the tar add list
    $ok = @ftp_fget($ftp,$fp,$name,FTP_BINARY); rewind($fp);
    $dat = ""; $d = ""; if(!$ok) return $stat;

    while($d = fread($fp,512)) $dat .= $d;
    global $add_list; $stat['addsize'] += strlen($dat);

    $add_list[] = Array($name,$dat);
    ftruncate($fp,0); rewind($fp);
    $stat['addfiles']++;
  }
  # Return the status informations
  return $stat;
}

#
# Send the content of a directory to FTP
# The files and folders are then deleted.
#
function send_ftp_dir($d, $p, $stat,$ftp)
{
  if(!@ftp_chdir($ftp,$p))
  {
   if(@ftp_mkdir($ftp,$p)) $stat['d']++;
   else return $stat;
   @ftp_chdir($ftp,$p);
  }

  # Try to avoid timeout
  @set_time_limit(20); @header('X-WebFTP-Ping: Pong!',0);

  $w = opendir($d); if(!$w) return $stat;
  readdir($w); readdir($w);

  while($f = readdir($w))
  {
    if(is_dir($d.$f))
     $stat = send_ftp_dir($d.$f.'/',$p.$f."/",$stat,$ftp);
    else
    {
      if(@ftp_put($ftp,$p.$f,$d.$f,$_SESSION['encod']))
      {
       $stat['f']++; $stat['s']+=@filesize($d.$f);
      }
      @unlink($d.$f);
    }
  }

  closedir($w); @rmdir($d);
  return $stat;
}

function cleartemp($dir)
{
 $d = opendir($dir);
 readdir($d); readdir($d);

 while($f = readdir($d))
 {
  if(is_dir($dir.$f)) cleartemp($dir.$f.'/');
  @unlink($dir.$f);
 }
 if($dir != 'temp/') @rmdir($dir);
}
cleartemp('temp/');

function surli($string, $line=0)
{
  $linecount = substr_count($string, "\n") + 1;
  $string = highlight_string($string, true);
  $ret = '<table bgcolor="#EFEFEF" style="width:100%;height:100%" cellspacing="0" border="0" cellpadding="2"><tr valign="top"><td bgcolor="#DCDCDC" width="0" align="right">';
  for($i=1; $i<=$linecount; $i++) $ret .= "$i<br>\n";
  $ret .= '</td><td nowrap>' . $string . '</td></tr></table>';
  return $ret;
}

?>