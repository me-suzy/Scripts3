<?

require 'func.inc.php';

if(isset($_POST['webftp_user'],$_POST['webftp_pass']) && is_null($conf->d_password))
{
  session_start();

  $_SESSION['user'] = $_POST['webftp_user'];
  $_SESSION['pass'] = base64_encode($_POST['webftp_pass']);

  $_SESSION['serv'] = ($conf->change_serv && isset($_POST['webftp_serv']) ? $_POST['webftp_serv'] : $conf->d_serv);
  $_SESSION['port'] = ($conf->change_serv && isset($_POST['webftp_port']) ? $_POST['webftp_port'] : $conf->d_port);

  $_SESSION['timeout'] = (isset($_POST['webftp_timeout']) ? $_POST['webftp_timeout'] : $conf->d_timeout);
  $_SESSION['seccon'] = ($conf->change_seccon && isset($_POST['webftp_seccon']) ? $_POST['webftp_seccon'] : $conf->d_seccon);
  $_SESSION['encod'] = ($conf->change_encod && isset($_POST['webftp_encod']) ? $_POST['webftp_encod'] : $conf->d_trans_mode);
  $_SESSION['ispassive'] = ($conf->change_ispassive && isset($_POST['webftp_ispassive']) ? $_POST['webftp_ispassive'] : $conf->d_ispassive);

  $_SESSION['dir'] = (isset($_POST['webftp_ordir']) ? $_POST['webftp_ordir'] : $conf->d_origdir);
  $_SESSION['md5'] = clesec($_SESSION['pass']);
  $_SESSION['tripar'] = $conf->d_tripar;
  $_SESSION['ordre'] = 0;

  setCookie('webftp_user',$_POST['webftp_user']);
  setCookie('webftp_serv',$_POST['webftp_serv']);

  header('Location: index.php?'.SID);
  die;
}
elseif(isset($_GET['bye']))
{
  session_start(); $_SESION=Array(); session_destroy();
  die(header('Location: index.php?status=logout'));
}
elseif(!verif_session())
{
  if(!is_null($conf->d_password))
  {
   $_SESSION['user'] = $conf->d_username; $_SESSION['pass'] = $conf->d_password;
   $_SESSION['serv'] =  $conf->d_serv; $_SESSION['port'] =  $conf->d_port;

   $_SESSION['timeout'] = $conf->d_timeout; $_SESSION['seccon'] = $conf->d_seccon;
   $_SESSION['encod'] = $conf->d_trans_mode; $_SESSION['ispassive'] = $conf->d_ispassive;

   $_SESSION['dir'] = $conf->d_origdir; $_SESSION['md5'] = clesec($_SESSION['pass']);
   $_SESSION['tripar'] = $conf->d_tripar; $_SESSION['ordre'] = 0;

   header('Location: index.php?'.SID);
   die;
  }

  $data = Array('SERV'=>(isset($_COOKIE['webftp_serv']) ? $_COOKIE['webftp_serv'] : $conf->d_serv),
          'PORT' => $conf->d_port, 'URL' => getenv('SCRIPT_NAME'), 'CHANGETOUT'=> $conf->change_timeout,
          'CHANGESERVEUR' => $conf->change_serv, 'CHANGESECCON' => $conf->change_seccon,
          'CHANGEENCOD' => $conf->change_encod, 'CHANGEISPASSIVE' => $conf->change_ispassive,
          'TIME' => date(t('date')), 'TIMEOUT' => $conf->d_timeout, 'ORIGDIR' => $conf->d_origdir,
          'ISBIN' => ($conf->d_trans_mode == FTP_BINARY?1:0), 'ISPASSIVE' => $conf->d_ispassive,
          'ISASCII' => ($conf->d_trans_mode == FTP_ASCII?1:0), 'CHANGELANG' => $conf->change_lang?1:0,
          'LANGUAGES' => $conf->valide_lang, 'LANGS' => count($conf->valide_lang), 'LANG' => $lg,
          'ISSECCON' => $conf->d_seccon, 'STATUS' => (isset($lang['s'.@$_GET['status']]) ? ' '.$lang['s'.$_GET['status']].' ':''),
          'USER' => (isset($_COOKIE['webftp_user']) ? $_COOKIE['webftp_user'] : $conf->d_username));
  echo parse_template("login.tpl",$data);
  die;
}
else
{
  $MSG = $BANNER = "";
  if(isset($_GET['set_mode']))
  {
    if($_GET['set_mode'] == 'ascii'){ $_SESSION['encod']=FTP_ASCII; $MSG = t(122);}
    else{ $_SESSION['encod'] = FTP_BINARY; $MSG = t(123); }
  }

  $ftp = ftpc($_SESSION['serv'],$_SESSION['port'],$_SESSION['timeout'],$_SESSION['seccon'],$_SESSION['user'],$_SESSION['pass'],$_SESSION['encod'],$_SESSION['dir'],$_SESSION['ispassive']);

  if(isset($_FILES['up'],$_POST['type'],$_POST['fn']) && $conf->allow_upload)
  if(!empty($_FILES['up']['name']))
  {
    if(!is_uploaded_file($_FILES['up']['tmp_name'])) die;
    $t_deb = gm(); if(empty($_POST['fn'])) $_POST['fn']=basename($_FILES['up']['name']);

    switch($_POST['type'])
    {
     case '1': @require("ziplib.class.php");
             $zip = new zip;

             if(!$zip->Extract($_FILES['up']['tmp_name'],'temp/'.basename($_FILES['up']['name'])))
               $MSG = t(125);
             else
             {
               $stat = array('f'=>0,'d'=>0,'t'=>gm(),'s'=>0);
               $stat = send_ftp_dir('temp/'.basename($_FILES['up']['name']).'/',$_SESSION['dir'],$stat,$ftp);
               @ftp_chdir($ftp,$_SESSION['dir']);

               $MSG = sprintf(t(124),$stat['f'],$stat['d'],round((gm()-$stat['t'])*1000,2),round($stat['s']/1024,2));
             }

             break;

     case '2': @require("tarlib.class.php");

             if(isset($_POST['uctype']))
             {
               $comp = (int)$_POST['uctype'];
               if($comp != 1 && $comp != 2 && $comp != -1) $comp = -1;
             }
             else $comp = -1;

             if($comp == -1)
             {
               $ext = strtolower(substr($_FILES['up']['name'],-3));
               if($ext == '.gz') $comp=1; elseif($ext == 'bz2') $comp=2; else $comp=0;
             }

             $tar = new CompTar($_FILES['up']['tmp_name'],$comp);

             $i = $tar->Extract(FULL_ARCHIVE,'temp/'.basename($_FILES['up']['name']));
             if(!is_array($i)) $MSG = t(125)." : ".$tar->TarErrorStr($i);

             else
             {
              $stat = array('f'=>0,'d'=>0,'t'=>gm(),'s'=>0);
              $stat = send_ftp_dir('temp/'.basename($_FILES['up']['name']).'/',$_SESSION['dir'],$stat,$ftp);
              ftp_chdir($ftp,$_SESSION['dir']);

              $MSG = sprintf(t(124),$stat['f'],$stat['d'],round((gm()-$stat['t'])*1000,2),round($stat['s']/1024,2));
             }

             break;

     default:
      if(@ftp_put($ftp,$_POST['fn'],$_FILES['up']['tmp_name'],$_SESSION['encod']))
        $MSG = sprintf(t(127),basename($_POST['fn']),round((gm()-$t_deb)*1000,2),round($_FILES['up']['size']/1024,2));
      else
        $MSG = t(126)." &laquo; ".basename($_POST['fn'])." &raquo;";
    }
  }

  if(isset($_GET['dl']))
  {
    if(!isset($_GET['ok'])) die(parse_template("download_file.tpl",array('URL'=>getenv('SCRIPT_NAME').'?'.SID,'FILE'=>$_GET['dl'],'F'=>basename($_GET['dl']))));

    if(($size = @ftp_size($ftp,$_GET['dl'])) < 0 || !($fp = @fopen('php://stdout','w')))
     die("<script>alert('".t(88)."');self.close();</script>");

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($_GET['dl']));
    header('Content-Length: '.$size);

    if(!@ftp_fget($ftp,$fp,$_GET['dl'],$_SESSION['encod'])) die("<script>alert('".t(89)."');self.close();</script>");
    die;
  }

  if(isset($_POST['site']) && $conf->action_pannel)
  if(!empty($_POST['site']))
  {
    $site = $_POST['site'];
    if(@ftp_site($ftp,$site))
      $MSG = t(90)." &laquo; SITE $site &raquo; ".t(91)." !";
    else
      $MSG = t(90)." &laquo; SITE $site &raquo; ".t(92)." ! ";
  }

  if(isset($_POST['chmod'],$_POST['sel']) && $conf->action_pannel)
  if(is_array($_POST['sel']) && ereg("^[0-7]{3,}\$",($mod=$_POST['chmod'])))
  {
     if($mod[0] != '0') $mod = '0'.$mod;
     $t_deb = gm(); $tot=0;

     foreach($_POST['sel'] as $s)
     {
       if(@ftp_site($ftp,"SITE CHMOD $mod \"$s\"")) $tot++;
     }

     $MSG = "$tot / ".count($_POST['sel']).t(93).$mod.t(94).round(gm()-$t_deb,2)."ms";
  }

  if(isset($_REQUEST['edit']) && $conf->allow_edit)
  {
    if(!$conf->allow_edit) die("<script>self.close();</script>");
    $f = urldecode($_REQUEST['edit']);

    if(!isset($_POST['mode']))
    {
      # Empty or doesn't exists
      if(empty($f) || $size=@ftp_size($ftp,$f) < 0)
      {
       die("<html><script>alert('".$lang[55]."');self.close();</script></html>");
      }
      # No Access Rights
      elseif(@ftp_rename($ftp, $f, $f) != true)
      {
       die("<html><script>alert('".$lang[56]."');self.close();</script></html>");
      }

      if(strtolower(substr($f,-3))==".js" || in_array(strtolower(substr($f,-4)),Array('.txt','.htm','html','.php','.css','php3','.htc','php4','.asp','.xml','maxg','.tpl','.inc'))){$dtxt=1;$dhex=0;}else{$dtxt=0;$dhex=1;}
      if(in_array(strtolower(substr($f,-4)),Array('.php','php3','php4','phps','maxg'))) $iphp = 1; else $iphp = 0;
      die(parse_template("edit_mode.tpl",Array('URL'=>getenv('SCRIPT_NAME').'?'.SID,'TOOLARGE'=>($size>102400?1:0),'FILE'=>basename($f),'EDIT'=>$f,'DTXT'=>$dtxt,'DHEX'=>$dhex, 'ISPHP' => $iphp)));
    }

    if(isset($_POST['save'],$_POST['buf']))
    {
      # This should prevent some stupid errors :-P
      if(@ftp_size($ftp,$_REQUEST['edit'].".bak")>=0) @ftp_delete($ftp,$_REQUEST['edit'].".bak");
      $ren = @ftp_rename($ftp,$_REQUEST['edit'],$_REQUEST['edit'].".bak");
      if(!$ren) die("<script>alert('".$lang[61]."');self.close();</script>");

      $fp = @tmpfile();
      if(!$fp) die("<script>alert('".$lang[60]."');self.close();</script>");

     if($_POST['mode'] == 1){
      $buf = explode(' ',trim($_POST['buf'])); $out = "";
      foreach($buf as $b) $out .= chr(hexdec($b));
     }else $out = $_POST['buf'];

      @fwrite($fp,$out,strlen($out)); @rewind($fp);

      $up_deb = gm();
      @ftp_fput($ftp, $_REQUEST['edit'], $fp, FTP_BINARY);
      $up_time = gm()-$up_deb;

      fclose($fp); $size = ftp_size($ftp,$_REQUEST['edit']);
      ftp_quit($ftp);

      die("<html><script>alert('".sprintf($lang[58],basename($_REQUEST['edit']),$size,round($up_time*1000,2),round(($size/1024)/$up_time,2),basename($_REQUEST['edit']))."');self.close();</script>");
    }

    $fp = @tmpfile();
    if(!$fp) die("<script>alert('".$lang[60]."');self.close();</script>");

    $ok = @ftp_fget($ftp,$fp,$_REQUEST['edit'],FTP_BINARY); rewind($fp);
    if(!$ok) die("<script>alert('".$lang[59]."');self.close();</script>");

    if($_POST['mode'] == 1)
    {
    $c=""; $offset = 0; $size = 0;

    while($cnt = fread($fp,16)){ $size += strlen($cnt); @set_time_limit(10);
      $c .= "<tr>\n<td class=\"offset\">".strtoupper(dechex($offset))."h</td>";
      $c .= '<td class="hex" onclick="tdclick(this,'.$offset.',0);">';
      for($i=0; $i < strlen($cnt); $i++) $c .= sprintf("%02X ",ord($cnt[$i]));
      $c .= '</td><td class="asc" onclick="tdclick(this,'.$offset.',1);">';
      for($i=0; $i < strlen($cnt); $i++){ $hx=ord($cnt[$i]); if($hx<32||$hx>126) $hx=ord(".");
      $c .= htmlentities(chr($hx)); } $c .= "</td></tr>\n"; $offset += 16;
      @header('X-WebFTP-Ping: Pong!',0);
    }
    if(!$c){ $c='<tr><td align="center" style="font-size:14pt;height:50px;">'.t('95').'</td></tr>';$flag=0;}
    else $flag = 1; fclose($fp);

    print(parse_template("edit_hex.tpl",array('URL' => getenv('SCRIPT_NAME').'?'.SID, 'EMPTY'=>$flag,'DATA' => $c,'SIZE' => $size,'FILE'=>basename($f),'EDIT'=>$f)));die;
   }
   elseif($_POST['mode'] == 2)
   {
     $data = "";
     while($d = fread($fp,64)) $data .= $d;

     die("<html><head><title>WebFTP - $f</title></head><body style=\"margin:0px;\">".surli($data,1)."</body></html>");
   }
   elseif($_POST['mode'] == 3)
   {
     $data = ""; $ext = strtolower(substr($f,-4));
     while($d = fread($fp,64)) $data .= $d;

     if(strtolower(substr($f,-8)) == ".tar.bz2" || strtolower(substr($f,-7)) == ".tar.gz" || $ext == ".zip" || $ext == ".tar")
     {
      if($ext == ".zip")
      {
       include('ziplib.class.php');
       $zip = new Zip;

       $list = $zip->get_List($f);
       if(!is_array($list)) die("<script>alert('".t(125)."');self.close();</script>");

       $names = $ics = $crc = $size = $comp = $ratio = $date = Array();
       $un = array('By','Kb','Mb','Gb');

       foreach($list as $l)
       {
         $names[] = $l['filename'];
         $ics[] = ($l['folder'] ? 'fold' : get_ext($l['filename']));
         $crc[] =  $l['crc'];

         for($u=0, $s = $l['size']; $s >= 1024; $u++) $s /= 1024;
         $size[] = round($s,1)." ".$un[$u];

         for($u=0, $c = $l['compressed_size']; $c >= 1024; $u++) $c /= 1024;
         $comp[] = round($c,1)." ".$un[$u];

         $ratio[] = round(($c / $s+1)*100);
         $date[] = @date('d/m/Y',$l['mtime']);
       }

       die(parse_template("view_zip.tpl",array('URL' => getenv('SCRIPT_NAME')."?".SID,'EDIT' => $f,'FILE'=>basename($f),'EMPT'=>(count($cont)?0:1),'FILES'=>count($list),'NAMES'=>$names,'ICS'=>$ics,'CRC'=>$crc,'EMPT'=>count($list)?'0':'1','COMP' => $comp, 'RATIO' => $ratio, 'SIZES' => $size, 'DATE'=>$date)));
      }
      else
      {
       include('tarlib.class.php');
       $tar = new CompTar($f, COMPRESS_DETECT);

       $cont = $tar->ListContents();
       if(!is_array($cont)) die("<script>alert('".t(125)."');self.close();</script>");

       $chks = $names = $modes = $sizes = $mtimes = $ics = $unames = $gnames = Array();
       $un = array('By','Kb','Mb','Gb');

       foreach($cont as $c)
       {
         for($u=0,$size=$c['size'];$size >= 1024; $u++) $size /= 1024;
         $chks[] = DecHex($c['checksum']); $names[] = $c['filename']; $mtimes[] = date('d/m/Y',$c['mtime']);
         $modes[] = octdec(octdec($c['mode'])); $unames[] = $c['uname']; $gnames[] = $c['gname'];
         $ics[] = $c['typeflag'] ? 'folder' : get_ext($c['filename']); $sizes[] = round($size,1)." ".$un[$u];
       }

       print(parse_template("view_tar.tpl",array('URL' => getenv('SCRIPT_NAME')."?".SID,'EDIT' => $f,'FILE'=>basename($f),'EMPT'=>(count($cont)?0:1),'FILES'=>count($cont),'NAMES'=>$names,'CHKS'=>$chks,'MODES'=>$modes,'UIDS'=>$unames,'GIDS'=>$gnames,'ICS'=>$ics,'SIZES'=>$sizes,'TIMES'=>$mtimes))); die;
      }
     }
     elseif($ext == '.jpg' || $ext == '.png' || $ext == '.gif' || $ext == '.bmp')
     {
       header('Content-Type: image/'.substr($f,-3));
       die($data);
     }
     else
     {
       if(empty($data)) die("<script>alert('".t(95)."');self.close();</script>");
       if(substr($f,-4) != ".htm" && substr($f,-5) != ".html") $data = "<html><body><pre>".htmlentities($data)."</pre></body></html>";
       die($data);
     }
   }
   else
   {
    $data = ""; while($d = fread($fp,32)) $data.=$d; fclose($fp);
    print(parse_template("edit_text.tpl",array('URL' => getenv('SCRIPT_NAME').'?'.SID,'FILE'=>basename($f),'EDIT'=>$f,'DATA'=>$data))); die;
   }
  }

  if(isset($_POST['sel'],$_POST['tar']) && $conf->allow_tar)
  if(@count($_POST['sel']) && ($f=basename($_POST['tar'])))
  {
    $ok=@require('tarlib.class.php');
    if(!$ok) start_error(t(96));

      $tar = new CompTar(ARCHIVE_DYNAMIC,COMPRESS_AUTO);
    $tar->Create(Array(Array("@WebFTP/info.txt",t(97))));
    $fp = @tmpfile(); if(!$fp) $MSG=t(98); else{

    $stat = array('deb'=>gm(),'addfiles'=>0,'addirs'=>0,'addsize'=>0,'date'=>date("d M Y h:i s\""));
    $add_list = Array();

    foreach($_POST['sel'] as $s)
    {
      # See funcs.inc.php for this function declaration
      $stat = tar_ftp_dir($ftp,$tar,$fp,$s,$stat);
    }

    # All archives have two info and status files in the WebFTP directory
    $stat['temps'] = round((gm()-$stat['deb'])*1000,2);
    $add_list[] = Array("@WebFTP/status.txt",t(99).$f.'.'.$tar->getCompression(1)."> :\r\n# -------------------------------------------\r\n# ".$stat['addfiles']." files and ".$stat['addirs'].t(100).$stat['addsize']." byes) in ".$stat['temps'].t(101).$stat['date']."]\r\n# -------------------------------------------\r\n#");
    $tar->Add($add_list,false,$_SESSION['dir']);

    $tar->sendClient("$f.".$tar->getCompression(1),NULL,TRUE);
    fclose($fp); die;
  }}

  if(isset($_REQUEST['webftp_setdir']))
  {
   if(strtolower($_REQUEST['webftp_setdir'])!=strtolower($_SESSION['dir']))
   if(!@ftp_chdir($ftp,$_REQUEST['webftp_setdir']))
   {
     $MSG = $lang['echdir']." &laquo; ".$_REQUEST['webftp_setdir']."&raquo;";
     @ftp_chdir($ftp,$_SESSION['dir']);
   }
  }

  if(isset($_GET['webftp_setordre'],$_GET['webftp_setsens']))
  {
   if(in_array($_GET['webftp_setordre'],Array(2,3,4,5,6)))
     $_SESSION['tripar'] = $_GET['webftp_setordre'];
   $_SESSION['ordre'] = ($_GET['webftp_setsens'] ? 1:0);
  }

 #
 # Prevent the script from being fooled if
 # action pannel is not enabled
 #
 if($conf->action_pannel)
 {
  if(isset($_POST['sel'],$_POST['del_x']))
  if(is_array($_POST['sel'])){
    $debut = gm();
    foreach($_POST['sel'] as $f)
    {
      if(substr($f,-1)=='/') $sta = ftp_deldir($ftp,$f);
      else $sta = @ftp_delete($ftp, $f);
      if($sta !== TRUE){ $MSG = t(102).($sta===false?$f:$sta); break;}
    }
    if(!$MSG) $MSG=t(103).round((gm()-$debut)*1000,2).t(104);
  }

  if(isset($_POST['sel'],$_POST['ren_x']))
  if(is_array($_POST['sel']))
  {
    $debut = gm(); $ren=0;
    foreach($_POST['sel'] as $f)
    {
      list($old,$new)=explode('<*>',$f);
      if(!$old || !$new) continue;
      if(@ftp_rename($ftp,$old,$new)) $ren++;
    }
    $MSG = "$ren / ".count($_POST['sel']).t(105).round((gm()-$debut)*1000,2).t(104);
  }

  if(isset($_POST['mkdir']))
  if(!empty($_POST['mkdir']))
  {
    $name = $_POST['mkdir'];
    if($name[0] != "/") $name = $_SESSION['dir'].$name;
    if(@ftp_mkdir($ftp,$name))
      $MSG = sprintf($lang[53],basename($name));
    else
      $MSG = sprintf($lang[54],basename($name));
  }

  if(isset($_POST['sel'],$_POST['moveto']))
  if(is_array($_POST['sel'])&&!empty($_POST['moveto']))
  {
    $to = $_POST['moveto']; $flag=0;
    if($to[0] != "/") $to = $_SESSION['dir'].$to; else $flag=1;
    if(substr($to,-1)!='/') $to.='/';

    if(!@ftp_chdir($ftp, $to))
    {
      if(!@ftp_mkdir($ftp,$to))
      {
        $MSG = t(106).basename($to)." &raquo;";
      }
    }else
      @ftp_chdir($ftp,$_SESSION['dir']); # Go back to CWD

    if(!$MSG){ # Don't waste time
     $debut = gm(); $mov=0;
     foreach($_POST['sel'] as $f)
     {
      if(@ftp_rename($ftp, $f, "$to".basename($f))) $mov++;
     }
     $MSG = "$mov / ".count($_POST['sel']).t(107).round(gm()-$debut,3).t(108);
    }
  }
 }

  $_SESSION['dir'] = @ftp_pwd($ftp); if(substr($_SESSION['dir'],-1)!="/") $_SESSION['dir'].='/';
  $list = ftp_parsedir($ftp,(isset($_POST['webftp_search']) ? $_POST['webftp_search'] : ''));

  if(isset($_POST['webftp_search']))
  {
   if(strtoupper(substr($_POST['webftp_search'],0,2)) != "@U") $list=tripar($list);
   $MSG = t(109).$_POST['webftp_search']." &raquo; :";
  }
  else $list = tripar($list);

  $folds = $own = $grp = $nf = $tf = $dts = $dtes = $isf = $isnf = $ics = $hn = Array();
  foreach($list as $i=>$l)
  {
    if(!$l[6]) continue;
    if($l[7] || $l[0][0] == 'l'){ list($folds[],) = explode(' -> ',$l[6]);
    $isf[]=1; $isnf[]=0; }else{ $isf[]=0; $isnf[] = 1;}

    if(empty($BANNER) && $conf->banner_type)
    {
     if(eregi($conf->banner_type,$l[6]))
     {
      $fp = tmpfile(); $BANNER = "";
      @ftp_fget($ftp, $fp, $_SESSION['dir'].$l[6], FTP_ASCII);

      rewind($fp); while($temp = fread($fp,32)) $BANNER.=$temp; fclose($fp);
      if(!empty($BANNER) && !eregi('<HTML>',$BANNER))
       $BANNER='<pre style="font-size:10pt;font-family: courier;">'.trim($BANNER).'</pre>';
     }
    }

    $t = $l[4]; $un = Array("By","Kb","Mb","Gb","Tb");
    for($u=0; $t >= 1024; $t /= 1024) $u++;
    $t = round($t,2)." ".$un[$u];

    $nf[]=$l[6]; $tf[]=!$l[7]?$t:'&nbsp;'; $dts[]=$l[0]; $own[] = $l[2];
    $dtes[] = $l[5]; $grp[] = $l[3];
    list($hn[],)=explode(' -> ',str_replace("'","\\'",$l[6]));
    $ics[] = ($l[7]?'fold':($l[0][0]=='l'?'link':get_ext($l[6])));
  }

  $data = Array('SERV'=>(isset($_COOKIE['webftp_serv']) ? $_COOKIE['webftp_serv'] : $conf->d_serv),
          'PORT' => $conf->d_port, 'URL' => getenv('SCRIPT_NAME').'?'.SID, 'STATUSMSG'=>@$_GET['status'],
          'FTPMODE' => FTPMODE, 'DIR' => $_SESSION['dir'], 'DIRS' => $folds, 'DIRC' => count($folds), 'SETMODE' => (FTPMODE ? 'ascii':'binary'),
          'MSG' => $MSG, 'ISMSG' => ($MSG ? 1:0),'TIME' => date(t('date')),'NOFILES' => (!count($list)?1:0), 'NEWMODE' => (FTPMODE?$lang[22]:$lang[21]),
          'FILEC' => count($nf), 'FNAMES' => $nf, 'FSIZES' => $tf, 'FRIGHTS' => $dts, 'FHTML'=>$hn, 'ACTIONP' => $conf->action_pannel ? 1 : 0,
          'SEARCH' => @$_POST['webftp_search'], 'SYSTYPE' => $_SESSION['syst'], 'ALLOWEDIT' => $conf->allow_edit?1:0,
          'STMODE' => (FTPMODE?$lang[21]:$lang[22]), 'SHORTDIR' => (strlen($_SESSION['dir'])>25?'(...)'.substr($_SESSION['dir'],-25):$_SESSION['dir']),
          'FDATES' => $dtes, 'FOWNS' => $own, 'FGRPS' => $grp, 'FICON' => $ics, 'ISFOLD' => $isf, 'ALLOWUP' => $conf->allow_upload ? 1 : 0,
          'ISBANNER' => (empty($BANNER) ? 0 : 1), 'BANNER' => $BANNER, 'ISNOTFOLD' => $isnf, 'ALLOWTAR' => $conf->allow_tar?1:0,
          'ORDRE' => $_SESSION['ordre'], 'NONSENS' =>$_SESSION['ordre']?0:1, 'ISNOM' => ($_SESSION['tripar'] == 6 ? 1:0),
          'ISTAILLE' => ($_SESSION['tripar'] == 4 ? 1:0), 'ISDATE' => ($_SESSION['tripar'] == 5 ? 1:0), 'LANG' => $lg,
          'ISGRP' => ($_SESSION['tripar'] == 3 ? 1:0), 'ISUSER' => ($_SESSION['tripar'] == 2 ? 1:0),
          'USER' => (isset($_COOKIE['webftp_user']) ? $_COOKIE['webftp_user'] : $conf->d_username));

  echo parse_template("browse.tpl",$data);
  ftp_close($ftp);
}
?>