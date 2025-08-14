<?php

//////////////////////////////////////////////////////////////////////////
// Script:        setting.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Setting
{
 var $Conf;
 var $Fun;
 var $eSetting;
 var $CodeError;
 var $Menu;
 var $Vacc;
 var $Admin;
 var $Descr;
 var $Function;
 var $Action;
 var $Account;
 var $Acc;

 function Setting ( $fun, $conf )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Account = new Accountsbase($fun,$conf);
          }

 function SettingInit ()
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;

           if ($this->Conf->Version>=2)
                   {
                    $this->eSetting = new ExtSetting($this->Fun, $this->Conf);
                    if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
                    $this->eSetting->ExtSettingInit();
                    if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
                   }

           //create folders structure
           $fname=$this->Conf->FullBasePath.'accounts/accounts.lst';
           if(!file_exists($fname))
               {
                $file=fopen($this->Conf->FullBasePath.'accounts/accounts.lst','w');
                if(!$file) {$this->CodeError=10;$ENameCoF=$this->Conf->FullBasePath."accounts/accounts.lst";return;}
                chmod($this->Conf->FullBasePath.'accounts/accounts.lst',0755);
                fclose($file);
               }

           $this->Account->AccountsbaseInit();
           if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
           if(isset($_POST['vaccount'])) $this->Acc=$_POST['vaccount'];
           else
               {
               for($i=0;$i<$this->Account->Size;$i++)
                    {
                     $adr=$this->Account->GetAccountAddressByID($i+1);
                     if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                     if(strcmp('deleted',$adr)) break;
                    }
               if($i<$this->Account->Size) $this->Acc=$i+1;
               else
                   {
                    if($this->Conf->Version>=2) $fname=$this->Conf->FullBasePath."v2/data/folders.dat";
                    else $fname=$this->Conf->FullBasePath."data/folders.dat";
                    if(file_exists($fname))
                       {
                        $this->Fun->extfile($fname,$folders);
                        if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}
                        if(!isset($folders)) {$this->CodeError=13;$ENameCoF="$fname";return;}
                        $num=1;
                        reset($folders);
                        while($elem=each($folders))
                           {
                            $elem[1]=str_replace("\r",'',$elem[1]);
                            $elem[1]=str_replace("\n",'',$elem[1]);
                            if(empty($elem[1])) continue;
                            $elem[1]=str_replace("%%num%%",$num,$elem[1]);
                            mkdir($this->Conf->FullBasePath.'accounts/'.$elem[1],0777);
                            chmod($this->Conf->FullBasePath.'accounts/'.$elem[1],0777);
                           }
                        $rec="http://|new page $num|1|";
                        $this->Account->BaseFile[ $num -1 ]=$rec;
                        $this->Account->Update();
                        if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                       }
                    else {$this->CodeError=1;$ENameCoF="$fname";return;}
                    $this->Acc=$num;
                   }
               }
           $this->cv=urlencode($this->Conf->Version);

           $Total = new Base($this->Fun,$this->Conf,'accounts/'.$this->Acc.'/total.cnt');
           $Total->BaseInit(0);
           if($Total->CodeError)
                     {
                      if($Total->CodeError!=1) {$this->CodeError=$Total->CodeError;return;}
                      $Total->SaveRecord(time());
                      if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
                      $Total->AddRecord("0");
                      if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
                      $Total->AddRecord(time() - $this->Conf->OnlineTime);
                      if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
                      $Total->AddRecord("0");
                      if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
                     }

           $this->GetAction ();
           if($this->CodeError)
                   {
                    if(isset($_POST['voldaction']))
                             $_POST[$_POST['voldaction']]=1;
                    else $_POST['aconfig_x']=1;
                    $this->GetAction();
                    if($this->CodeError) return;
                   }
          }

 function GetAction ()
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;
           if ((isset($_POST['aconfig_x']))||(isset($_POST['aconfig_y'])))
                   {
                    $this->Function='ACounterConfig';
                    $this->Action='aconfig';
                    $this->Descr='Script configuration';
                   }
           elseif ((isset($_POST['aaccount_x']))||(isset($_POST['aaccount_y'])))
                   {
                    $this->Function='AAccount';
                    $this->Action='aaccount';
                    $this->Descr='Account settings';
                   }
           elseif ((isset($_POST['ahtml_x']))||(isset($_POST['ahtml_y'])))
                   {
                    $this->Function='ACounterHTML';
                    $this->Action='ahtml';
                    $this->Descr='HTML code';
                   }
           elseif ($this->Conf->Version>=2)
                   {
                    if ((isset($_POST['aaddpage_x']))||(isset($_POST['aaddpage_y'])))
                         {
                          $this->Function='ACounterAddPage';
                          $this->Action='aaddpage';
                          $this->Descr='Add page';
                         }
                    elseif ((isset($_POST['adelpage_x']))||(isset($_POST['adelpage_y'])))
                         {
                          $this->Function='ACounterDeletePage';
                          $this->Action='adelpage';
                          $this->Descr='Delete page';
                         }
                    else {$this->CodeError=3;$ENameCoF="Ext class Setting";}
                   }
           else {$this->CodeError=3;$ENameCoF="class Setting";}

           }

 function OutSetting ()
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;

           $vvar['url']=$this->Conf->FullUrl;
           $vvar['class']='f14';
           $vvar['today']=date('j F Y, H:i:s',time());
           $vvar['murl']=$this->Conf->MainConf;

           if(!isset($_POST['ver']))
                   {
                    $nam=$this->Conf->MainConf;
                    $nam.=base64_decode('YWNvdW50ZXIvbmV3cy9sdi5waHA/');
                    $nam.='cv='.$this->cv;
                    $vvar['ver1']='';
                    $vvar['ver2']="<script src=\"$nam\"></script>";
                   }
           else
                   {
                    $vvar['ver1']=$_POST['ver'];
                    $vvar['ver2']=$_POST['ver'];
                   }

           eval("\$this->{$this->Function}(\$vvar['setting']);");
           if($this->CodeError) return;

           $this->Conf->ConfigInit ();
           if($this->Conf->CodeError) {$this->CodeError=$this->Conf->CodeError;return;}

           $vvar['descr']=$this->Descr;

           if($this->Conf->Version>=2)
                   {
                    if(isset($_POST['name'])) $vvar['name']=$this->Conf->AdminName;
                    else $vvar['name']='';
                    if(isset($_POST['password'])) $vvar['password']=$this->Conf->AdminPassword;
                    else $vvar['password']='';
                    $vvar['guestname']=$this->Conf->GuestName;
                    $vvar['guestpassw']=$this->Conf->GuestPassword;
                   }

           if((!strcmp($this->Action,'ahtml'))||(!strcmp($this->Action,'aconfig')))
                  $vvar['account']='';
           else
                  {
                   $this->Vacc= new AccountsList($this->Fun, $this->Conf, $this->Acc);
                   $this->Vacc->AccountsListInit();
                   if($this->Vacc->CodeError) {$this->CodeError=$this->Vacc->CodeError;return;}
                   $this->Vacc->CreateAccountsList();
                   if($this->Vacc->CodeError) {$this->CodeError=$this->Vacc->CodeError;return;}
                   $vvar['account']=$this->Vacc->GetAccountsList();
                  }

           $this->Admin= new Template($this->Fun, $this->Conf, 'admin/adm_tpl.php');
           $this->Admin->TemplateInit();
           if($this->Admin->CodeError) {$this->CodeError=$this->Admin->CodeError;return;}
           if($this->Conf->Version>=2) $vvar['version']=$this->Conf->Version.' (Commercial)';
            else $vvar['version']=$this->Conf->Version.' (Free version)';

           $this->Menu= new Template($this->Fun, $this->Conf, 'admin/menu_tpl.php');
           $this->Menu->TemplateInit();
           if($this->Menu->CodeError) {$this->CodeError=$this->Menu->CodeError;return;}
           $tvar['url']=$this->Conf->FullUrl;
           $tvar['action']=$this->Action."_x";
           $this->Menu->ParseTemplate($tvar);
           if($this->Menu->CodeError) {$this->CodeError=$this->Menu->CodeError;return;}
           $vvar['menu']=$this->Menu->GetTemplate();

           $this->Admin->ParseTemplate($vvar);
           if($this->Admin->CodeError) {$this->CodeError=$this->Admin->CodeError;return;}
           $this->Admin->OutTemplate();

           exit;
          }

 function ACounterConfig (&$setting)
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;

           if($this->Conf->Version>=2)
                   {
                    $this->eSetting->ACounterConfig($setting,$this->Account,$this->Acc);
                    if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
                    return;
                   }

           $tvar['class']='f12';

           $page= new Template($this->Fun, $this->Conf, 'admin/scrc_tpl.php');
           $page->TemplateInit();
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}

           if(isset($_POST['asavethis']))
                {
                 $setpath=0;
                 $seturl=0;
                 if(isset($_POST['aurl'])) $aurl=$_POST['aurl'];
                 else $aurl="";
                 if(isset($_POST['apath'])) $apath=$_POST['apath'];
                 else $apath="./";
                 if(file_exists('./config/conf_dat.php'))
                     {
                      $this->Fun->extfile('./config/conf_dat.php',$cdata);
                      if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}

                      if(!isset($cdata)) {$this->CodeError=13;$ENameCoF="./config/conf_dat.php";return;}
                      reset($cdata);
                      $elem=each($cdata);
                      while($elem=each($cdata))
                             {
                              if(strstr($elem[1],'FullBasePath'))
                                 {
                                  $setpath=1;
                                  $cdata[$elem[0]]='FullBasePath='.$apath."\n";
                                 }
                              elseif(strstr($elem[1],'FullUrl'))
                                 {
                                  $seturl=1;
                                  $cdata[$elem[0]]='FullUrl='.$aurl."\n";
                                 }
                             }
                     }
                 else
                     {
                      $cdata[]="<?php die('Access restricted');?>\n";
                     }
                 if(!$setpath) $cdata[]='FullBasePath='.$apath."\n";
                 if(!$seturl) $cdata[]='FullUrl='.$aurl."\n";
                 $file_dat=fopen('./config/conf_dat.php','w');
                 if(!$file_dat) {$this->CodeError=10;$ENameCoF="./config/conf_dat.php";return;}
                 chmod('./config/conf_dat.php',0755);
                 flock($file_dat,2);
                 reset($cdata);
                 while($elem=each($cdata))
                        {
                         if(!fwrite($file_dat,$elem[1])) {$this->CodeError=15;$ENameCoF="./config/conf_dat.php";return;}
                        }
                 flock($file_dat,3);
                 fclose($file_dat);
                 $this->Conf->FullUrl=$aurl;
                 $this->Conf->FullBasePath=$apath;
                }

           $tvar['aurl']=$this->Conf->FullUrl;
           $tvar['apath']=$this->Conf->FullBasePath;

           $page->ParseTemplate($tvar);
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
           $setting=$page->GetTemplate();
          }

 function AAccount (&$setting)
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;

           if(isset($_POST['asavethis']))
                   {
                    $emes='';
                    if(isset($_POST['apageurl']))
                         if(!empty($_POST['apageurl']))
                              {
                               $acc=$this->Account->GetIDByAccountAddress($_POST['apageurl']);
                               if($this->Account->CodeError)
                                         {
                                          if($this->Account->CodeError!=2) {$this->CodeError=$this->Account->CodeError;return;}
                                         }
                               elseif($this->Acc!=$acc) $emes='URL \'<b>'.$_POST['apageurl'].'</b>\' already exist';
                              }

                   $_POST['ashorturl']='';
                    if(isset($_POST['defpage']))
                              {
                                  if(isset($_POST['apageurl']))
                                               if(!empty($_POST['apageurl']))
                                                 {
                                                     $shortmas=split("/",$_POST['apageurl']);
                                                     if(count($shortmas)>3)
                                                          {
                                                          $shortmas[count($shortmas)-1]="";
                                                           $_POST['ashorturl']=join("/",$shortmas);
                                                                       $acc=$this->Account->GetIDByAccountAddress($_POST['ashorturl']);
                                                                       if($this->Account->CodeError)
                                                                {
                                                                   if($this->Account->CodeError!=2) {$this->CodeError=$this->Account->CodeError;return;}
                                                                 }
                                                                elseif($this->Acc!=$acc) $emes='Default URL \'<b>'.$_POST['ashorturl'].'</b>\' already exist. Can be only one default page for each folder.';
                                                         }
                                                }
                              }

                    if(isset($_POST['apagename']))
                         if(!empty($_POST['apagename']))
                              {
                               $acc=$this->Account->GetIDByAccountName($_POST['apagename']);
                               if($this->Account->CodeError)
                                         {
                                          if($this->Account->CodeError!=2) {$this->CodeError=$this->Account->CodeError;return;}
                                         }
                               elseif($this->Acc!=$acc) $emes='Name \'<b>'.$_POST['apagename'].'</b>\' already exist';
                              }

                    if($emes)
                              {
                               $tvar['class']='f12';
                               $tvar['emes']=$emes;

                               $page= new Template($this->Fun, $this->Conf, 'admin/er_tpl.php');
                               $page->TemplateInit();
                               if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
                               $page->ParseTemplate($tvar);
                               if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
                               $setting=$page->GetTemplate();
                               return;
                              }
                   }

           if($this->Conf->Version>=2)
                   {
                    $this->eSetting->AAccount($setting,$this->Account,$this->Acc);
                    if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
                    return;
                   }

           $tvar['url']=$this->Conf->FullUrl;
           $tvar['class']='f12';

           $page= new Template($this->Fun, $this->Conf, 'admin/acc_tpl.php');
           $page->TemplateInit();
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}

           $rec=$this->Account->GetRecordByID( $this->Acc - 1 );
           if($this->Account->CodeError) {$this->CodeError=2;$ENameCoF='Account not found';return;}

           $taccarray=split("\|",$rec);
           if(isset($_POST['asavethis']))
                {
                 if(isset($_POST['apageurl'])) $taccarray[0]=$_POST['apageurl'];
                 if(isset($_POST['apagename'])) $taccarray[1]=$_POST['apagename'];
                 if(isset($_POST['ashorturl'])) $taccarray[3]=$_POST['ashorturl'];
                 $rec=join("|",$taccarray);
                 $this->Account->BaseFile[ $this->Acc - 1 ]=$rec;
                 $this->Account->Update();
                 if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                }
           if(!strcmp($taccarray[0],'deleted'))
                {
                 $taccarray[0]='http://';
                 $taccarray[1]="new page $this->Acc";
                 $taccarray[2]=1;
                 $taccarray[3]="";
                }
           $tvar['apageurl']=$taccarray[0];
           $tvar['apagename']=$taccarray[1];
           if($taccarray[3]) $tvar['defpage']='CHECKED';
           else $tvar['defpage']='';

           if(isset($_POST['abgcolor'])) $tvar['abgcolor']=$_POST['abgcolor'];
           else $tvar['abgcolor']="CCCCCC";

           $color=$this->Account->GetColorByID(1);
           if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}

           $cmax=5;

           for($c=1;$c<=$cmax;$c++)
                 if((isset($_POST['aimgset'.$c.'_x']))||(isset($_POST['aimgset'.$c.'_y']))) break;
           if($c>$cmax)
                {
                 if(isset($_POST['aimgset'])) $c=$_POST['aimgset'];
                }

           if($c<=$cmax)
                {
                 $ccolor=$c;
                 $rec=$this->Account->GetRecordByID( $this->Acc - 1 );
                 if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                 $taccarray=split("\|",$rec);
                 $taccarray[2]=$ccolor;
                 $rec=join("|",$taccarray);
                 $this->Account->BaseFile[$this->Acc-1]=$rec;
                 $this->Account->Update();
                 if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                }
           else $ccolor=$color;

           for($i=1;$i<=$cmax;$i++)
                {
                 if($ccolor==$i) $tvar['i'.$i]=' checked';
                 else $tvar['i'.$i]='';
                }
           $page->ParseTemplate($tvar);
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
           $setting=$page->GetTemplate();
          }

 function ACounterHTML (&$setting)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Conf->Version>=2)
                   {
                    $this->eSetting->ACounterHTML($setting,$this->Account,$this->Acc);
                    if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
                    return;
                   }

           $tvar['class']='f12';
           $page= new Template($this->Fun, $this->Conf, 'admin/html_tpl.php');
           $page->TemplateInit();
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}

           $htmlcode= new Template($this->Fun, $this->Conf, "");
           if($this->Conf->Version>=2) $fname=$this->Conf->FullBasePath.'v2/data/htmlcode.dat';
           else $fname=$this->Conf->FullBasePath.'data/htmlcode.dat';

           if(file_exists($fname))
              {
               $this->Fun->extfile($fname,$htmldata);
               if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}
               if(!isset($htmldata)) {$this->CodeError=13;$ENameCoF="$fname";return;}
               reset($htmldata);
               while($elem=each($htmldata))
                  {
                   $elem[1]=str_replace("\r",'',$elem[1]);
                   $elem[1]=str_replace("\n",'',$elem[1]);
                   $htmldata[$elem[0]]=$elem[1];
                  }
               $htmlcode->AddTemplate($htmldata);
               $tvar['url']=$this->Conf->FullUrl;
               $tvar['ver']=$this->Conf->Version;
               $htmlcode->ParseTemplate($tvar);
               if($htmlcode->CodeError) {$this->CodeError=$htmlcode->CodeError;return;}
               $tvar['ahtml']=$htmlcode->GetTemplate();
              }
           else $tvar['ahtml']="";

           $page->ParseTemplate($tvar);
           if($page->CodeError) {$this->CodeError=$page->CodeError;return;}
           $setting=$page->GetTemplate();
          }

 function ACounterAddPage (&$setting)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->eSetting->ACounterAddPage($setting,$this->Account,$this->Acc);
           if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
           $this->Action='aaccount';
           $this->Descr='Account settings';
           $this->AAccount($setting);
          }

 function ACounterDeletePage (&$setting)
          { global $ENameCoF,$_SERVER,$_GET,$_POST;
           $this->CodeError=0;
           $this->eSetting->ACounterDeletePage($setting,$this->Account,$this->Acc);
           if($this->eSetting->CodeError) {$this->CodeError=$this->eSetting->CodeError;return;}
           if(!isset($_POST['delconfirm'])) return;
           if($this->Acc==0)
               {
                $this->Action='aconfig';
                $this->Descr='Script configuration';
                $this->ACounterConfig($setting);
               }
           else
               {
                $this->Action='aaccount';
                $this->Descr='Account settings';
                $this->AAccount($setting);
               }
          }

}

?>