<?php

//////////////////////////////////////////////////////////////////////////
// Script:        funct.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Funct
{
 var $CodeError;
 var $Conf;
 var $Version;

 function extfile ($name,&$buf)
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $buf=file($name);
          }

 function Auth ($conf,$sname,$guest)
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $this->Conf=$conf;
           if($conf->Version<2) return;
           if((!isset($_POST['password']))||(!isset($_POST['name'])))
               {
                $page= new Template($this, $conf, 'auth/log_tpl.php');
                $page->TemplateInit();
                if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
           if($this->Conf->Version>=2) $tvar['version']=$conf->Version.' (Commercial)';
            else $tvar['version']=$conf->Version.' (Free version)';

                $tvar['url']=$conf->FullUrl.$sname;
                $page->ParseTemplate($tvar);
                if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
                $page->OutTemplate();
                exit;
               }
           if(strcmp($conf->AdminName,$_POST['name'])||strcmp($conf->AdminPassword,$_POST['password']))
               {
                if($guest)
                    {
                     if((!strcmp($conf->GuestName,$_POST['name']))&&(!strcmp($conf->GuestPassword,$_POST['password']))) return;
                    }
                $page= new Template($this, $conf, 'auth/log_tpl.php');
                $page->TemplateInit();
                if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
           if($this->Conf->Version>=2) $tvar['version']=$conf->Version.' (Commercial)';
            else $tvar['version']=$conf->Version.' (Free version)';

                $tvar['url']=$conf->FullUrl.$sname;
                $page->ParseTemplate($tvar);
                if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
                $page->OutTemplate();
                exit;
               }
           return;
          }

 function Error ($code,$visible)
          { global $ENameCoF;
           if(file_exists('errors.log')) {
                   $file=fopen('errors.log','a');
                   if(!$file) exit;
           }
           else {
                   $file=fopen('errors.log','w');
                   if(!$file) exit;
                 chmod('errors.log',0755);
           }
           flock($file,2);
           fputs($file,date("d.m.Y H:i:s",time()).'|'.$code.'|'.$ENameCoF."\n");
           flock($file,3);
           fclose($file);
           if($visible==1)
               {
                $tmp=file('data/errors.dat');
                if(!isset($tmp)) exit;
                $note='unknown';
                $recomendation='Connect to technical support';
                for($i=0;$i<count($tmp);$i++)
                     {
                      $rec=split("\|",$tmp[$i]);
                      if(!isset($rec[0])) continue;
                      if($code==$rec[0])
                          {
                           if(isset($rec[1])) $note=$rec[1];
                           if(isset($rec[2])) $recomendation=$rec[2];
                           break;
                          }
                     }
                $what=$ENameCoF;
                if($this->Conf->Version>=2) $this->Version=$this->Conf->Version.' (Commercial)';
                    else $this->Version=$this->Conf->Version.' (Free version)';
                $this->out_res($code,$what,$recomendation,$note);
               }
           exit;
          }

 function Funct()
          {
          }

 function SumArray($res)         // Return of sum of array
          {
           $something=0;
           if(strcmp(gettype($res),"array")) $something=$res;
            else
                {
                 reset($res);
                 while($elem=each($res))
                       {
                        $temp=$this->SumArray($elem[1]);
                        if(strcmp($temp,'|')) $something+=$temp;
                       }
                }
           return $something;
          }

 function PerArray($res) // Calculate percents and return array
          { global $ENameCoF;
           static $PerArraysum=0;
           static $PerArraydepthrecur=0;
           $PerArraydepthrecur++;
           if($PerArraydepthrecur==1) $PerArraysum=$this->SumArray($res);
           if(!$PerArraysum)
              {
               $PerArraydepthrecur--;
               return $res;
              }
           if(strcmp(gettype($res),'array'))
              {
               if(strcmp($res,'|')) $res=sprintf("%.2f",$res/$PerArraysum*100);//$res=((int)($res/$PerArraysum*10000))/100;
              }
            else
                {
                 reset($res);
                 while($elem=each($res))
                       {
                        $res[$elem[0]]=$this->PerArray($elem[1]);
                       }
                }
           $PerArraydepthrecur--;
           return $res;
          }

 function PerArray1D($res) // Calculate percents and return array (only for 1d-array)
          {
           $PerArraysum=0;
           foreach($res as $k=>$v)if(strcmp($v,'|'))$PerArraysum+=$v;
           if(!$PerArraysum)return $res;
           foreach($res as $k=>$v)if(strcmp($v,'|'))$res[$k]=sprintf("%.2f",$v/$PerArraysum*100);
           return $res;
          }

 function QuantityArray($res)         // Return quantity of elements of array
          {
           $something=0;
           if(strcmp(gettype($res),"array")) { if(strcmp($res,'|')) $something=1; }
            else
                {
                 reset($res);
                 while($elem=each($res))
                       {
                        $something+=$this->QuantityArray($elem[1]);
                       }
                }
           return $something;
          }

 function out_res($code,$what,$recomendation,$note)
         {

echo <<< EOF
<html>
<head>
<title>Actual Counter {$this->Version} - Setup</title>
<LINK href="./template/css/main.css" type=text/css rel=stylesheet>
</head>
<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<div align="center">
<form method="post" action="funct.php">
    <table bgcolor="#666699" border="0" cellspacing="1" cellpadding="0">
     <tr>
      <td>
       <table width=610 bgcolor="#333366" border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td colspan=3 height=80 valign=center align=center>
          <table bgcolor="#999999" border="0" cellspacing="1" cellpadding="0">
           <tr>
            <td valign="top">
             <table bgcolor="#CCCCCC" width="600" height="66" border="0" cellspacing="0" cellpadding="1">
              <tr valign=center>
               <td align=center valign=center bgcolor="#CCCCCC">
                <b><font size=2 color="#FF0000">
                  <SPAN class="f12">&nbsp;&nbsp;Code error $code:</SPAN>
                </font></b>
                <b><font size=2 color="#000000">
                  <SPAN class="f12">&nbsp;$note - $what</SPAN>
                </font></b>
               </td>
              </tr>
             </table>
            </td>
           </tr>
          </table>
         </td>
       </tr>

       <tr>
        <td width=5>
        </td>
        <td valign=top height=200 width=530 bgcolor="#CCCCCC">
         <table width="600" height="200" border="1" cellspacing="0" cellpadding="0">
          <tr>
           <td height=200 align=left valign=top>
            <b><font size=2 color="#000000">
             <SPAN class="f12">&nbsp;&nbsp;<u>Recomendation:</u></SPAN>
            </font></b>
            <b><font size=2 color="#000000">
             <SPAN class="f12">&nbsp;$recomendation.</SPAN>
            </font></b>
           </td>
          </tr>
         </table>
        </td>
        <td width=5>
        </td>
       </tr>

       <tr>
        <td colspan=3 width="100%">
         <table bgcolor="#333366" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
          <td width=5>
          </td>
          <td height=20 width=210 align="left">
           <font size=2 color="#FFFFFF">
            <SPAN class="f9">Actual Counter {$this->Version}</SPAN>
           </font>
          </td>
          <td width=390 align="right">
           <font size=2 color="#FFFFFF">
            <SPAN class="f9">Copyright &copy; 2002 <a href="http://www.actualscripts.com/" target="_blank"><b>Actual Scripts</b></a> Company. All Rights Reserved.</SPAN>
           </font>
          </td>
          <td width=5>
          </td>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</form>
</div>
</body>
</html>

EOF;
exit;
         }

 }

?>