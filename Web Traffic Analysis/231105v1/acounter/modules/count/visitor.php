<?php

//////////////////////////////////////////////////////////////////////////
// Script:        visitor.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Visitor
{

 var $ID;
 var $IP;
 var $Ipb;
 var $Vis;
 var $Referer;
 var $Ref;
 var $Uniq;
 var $Language;
 var $Lang;
 var $CodeError;
 var $CurrentID;
 var $Account;
 var $Total;
 var $Conf;
 var $Fun;
 var $Acc;
 var $Ctim;
 var $TBeg;
 var $TAll;
 var $TDay;
 var $TCur;
 var $Image;
 var $Unique;
 var $Lasttime;
 var $UnForArh;
 var $eParam;

 function Visitor($fun,$conf,$time)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $time;
           $this->Account = new Accountsbase($fun,$conf);
           if($this->Conf->Version>=2) $this->Image = new GIF($conf,$fun,$this->Conf->FullBasePath.'images/blank.txt',$this->Conf->FullBasePath.'v2/data/color.dat');
           else $this->Image = new GIF($conf,$fun,$this->Conf->FullBasePath.'images/blank.txt',$this->Conf->FullBasePath.'data/color.dat');
          }

 function VisitorInit()
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Account->AccountsbaseInit();
           if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
           $this->Image->GIFInit();
           if($this->Image->CodeError) {$this->CodeError=$this->Image->CodeError;return;}
          }

 function GetAccount ()
          { global $ENameCoF,$_SERVER,$_GET;
           $this->CodeError=0;
           if(isset($_GET['acid'])) {
                   $acc=$_GET['acid'];
                $this->Account->GetAccountNameByID($acc);
                if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                 $this->Acc=$acc;
                 return;
           }

           if(!isset($_SERVER['HTTP_REFERER'])) {$this->CodeError=101;$ENameCoF="HTTP_REFERER";return;}
           else $ref=$_SERVER['HTTP_REFERER'];
           if(empty($ref)) {$this->CodeError=101;$ENameCoF="HTTP_REFERER";return;}
           $ref=split("\?",$ref);
           $ref=$ref[0];
           $ref=preg_replace("/(\/)*$/","",$ref);
           $acc=$this->Account->GetIDByAccountAddress($ref);
           if($this->Account->CodeError)
                {
                 if($this->Account->CodeError!=2) {$this->CodeError=$this->Account->CodeError;return;}

                 $ref2=$ref;
                 if(!strcmp(substr($ref2,0,11),'http://www.'))
                    $ref2=str_replace('http://www.','http://',$ref2);
                 else $ref2=str_replace('http://','http://www.',$ref2);
                 $acc=$this->Account->GetIDByAccountAddress($ref2);
                 if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                }
           $this->Acc=$acc;
          }

 function Recount()
          { global $ENameCoF;
           $this->CodeError=0;

           $this->Total = new Base($this->Fun,$this->Conf,'accounts/'.$this->Acc.'/total.cnt');
           $this->Total->BaseInit(0);
           if($this->Total->CodeError)
                     {
                      if($this->Total->CodeError!=1) {$this->CodeError=$this->Total->CodeError;return;}
                      $this->Total->SaveRecord($this->Ctim);
                      if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
                      $this->Total->AddRecord("0");
                      if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
                      $this->Total->AddRecord($this->Ctim);
                      if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
                      $this->Total->AddRecord("0");
                      if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
                     }

           $this->TBeg=$this->Total->GetRecordByID(0);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $this->TAll=$this->Total->GetRecordByID(1)+1;
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $this->Lasttime=$this->Total->GetRecordByID(2);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $curday=getdate($this->Ctim);
           $lastday=getdate($this->Lasttime);
           if(($curday["yday"]==$lastday["yday"])&&($curday["year"]==$lastday["year"]))
               {
                $this->TDay=$this->Total->GetRecordByID(3)+1;
                if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
                if($this->TDay>1500) {return;}
               }
            else
                {
                 $this->TDay=1;
                 if($this->Conf->Version>=2)
                     {
//                      require("./v2/modules/base/archref.php");
//                      require("./v2/modules/base/archip.php");
//                      require("./v2/modules/base/archlan.php");
//                      require("./v2/modules/base/archfr.php");

                      $ArhRef=new ArchiveRef($this->Fun,$this->Conf,$this->Acc,$this->Ctim,$this->Lasttime);
                      $ArhRef->ArchiveRefInit();
                      if($ArhRef->CodeError){$this->CodeError=$ArhRef->CodeError;return;}
                      $ArhRef->UpdateRef();
                      if($ArhRef->CodeError){$this->CodeError=$ArhRef->CodeError;return;}

                      $ArhIP=new ArchiveIP($this->Fun,$this->Conf,$this->Acc,$this->Ctim,$this->Lasttime);
                      $ArhIP->ArchiveIPInit();
                      if($ArhIP->CodeError){$this->CodeError=$ArhIP->CodeError;return;}
                      $ArhIP->UpdateIP();
                      if($ArhIP->CodeError){$this->CodeError=$ArhIP->CodeError;return;}

                      $ArhLan=new ArchiveLan($this->Fun,$this->Conf,$this->Acc,$this->Ctim,$this->Lasttime);
                      $ArhLan->ArchiveLanInit();
                      if($ArhLan->CodeError){$this->CodeError=$ArhLan->CodeError;return;}
                      $ArhLan->UpdateLan();
                      if($ArhLan->CodeError){$this->CodeError=$ArhLan->CodeError;return;}

                      $ArhFr=new ArchiveFr($this->Fun,$this->Conf,$this->Acc,$this->Ctim,$this->Lasttime);
                      $ArhFr->ArchiveFrInit();
                      if($ArhFr->CodeError){$this->CodeError=$ArhFr->CodeError;return;}
                      $ArhFr->UpdateFr();
                      if($ArhFr->CodeError){$this->CodeError=$ArhFr->CodeError;return;}
                     }
                 $this->DelFiles();
                }
          }

 function GetVisitorID ()
          { global $ENameCoF,$_SERVER,$_GET,$_COOKIE;
           $this->CodeError=0;
           $pgsmnew='';
           $this->ID=0;

           $cver='ac1';
           if($this->Conf->Version>=2) $cver='ac2';

           if(isset($_COOKIE[$cver]))
                  {
                   $param=$_COOKIE[$cver] ;
                   if(!empty($param))
                          {
                           $curtime=getdate($this->Ctim);
                           $apgs=preg_split("/_/",$param,0,PREG_SPLIT_NO_EMPTY);
                           $apgsm=sizeof($apgs);
                           for($i=0;$i<$apgsm;$i++) {
                                   $temp=preg_split("/\|/",$apgs[$i]);
                                        $usertime=getdate($temp[2]);
                                        if($curtime["yday"]==$usertime["yday"])
                                                {
                                            $pgsmnew.='_'.$temp[0].'|'.$temp[1].'|'.$temp[2];
                                          if(!strcmp($temp[0],$this->Acc))
                                                  {
                                                  $this->ID=$temp[1];
                                                 }
                                         }
                                 }
                          }
                  }

           if($this->ID)
                   {
                  SetCookie($cver,$pgsmnew,time()+$this->Conf->CookieExp);
                  return;
                 }


           $this->Visitor = new Base($this->Fun,$this->Conf,"accounts/$this->Acc/daily/visitors/visitor.cnt");
           $this->Visitor->BaseInit(0);
           if($this->Visitor->CodeError)
                     {
                      if($this->Visitor->CodeError!=1) {$this->CodeError=$this->Visitor->CodeError;return;}
                      $ID=0;
                     }
           else
                     {
                      $ID=$this->Visitor->GetRecordByID(0);
                      if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                     }
           if($this->TDay==1)
               {
                $ID=1;
                $this->Visitor->SaveRecord($ID);
                if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
               }
           else
               {
                $ID++;
                $this->Visitor->SaveRecord($ID);
                if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
               }

           $this->ID=$ID;
           $pgsmnew.='_'.$this->Acc.'|'.$this->ID.'|'.$this->Ctim;
           SetCookie($cver,$pgsmnew,time()+$this->Conf->CookieExp);
          }

 function GetEnvironments()
          { global $ENameCoF,$_SERVER,$_GET;
           $this->CodeError=0;
           if(!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) $this->Language[0]="undefined";
           else $this->Language=split(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
           if(empty($this->Language[0])) $this->Language[0]="undefined";
           else
                  {
                   $tmp=split(";",$this->Language[0]);
                   $this->Language[0]=trim($tmp[0]);
                  }

           if(!isset($_SERVER["REMOTE_ADDR"])) $this->IP="undefined";
           else $this->IP=$_SERVER["REMOTE_ADDR"];
           if(empty($this->IP)) $this->IP="undefined";

           if(!isset($_GET["acr"])) $this->Referer="undefined";
           else $this->Referer=$_GET["acr"];
           if(empty($this->Referer)) $this->Referer="undefined";
           $ref=split("\?",$this->Referer);
           $this->Referer=$ref[0];
           if(!strcmp(substr($this->Referer,0,7),'http://'))
                  {
                   if(strcmp(substr($this->Referer,0,11),'http://www.'))
                           {
                            $ref2=str_replace('http://','http://www.',$this->Referer);
                            $this->Referer=$ref2;
                           }
                  }

           if($this->Conf->Version>=2)
               {
                $this->eParam=new ExtParam($this->Fun,$this->Conf,$this->Acc,$this->Ctim,$this->ID,$this->Referer);
                $this->eParam->ExtParamInit();
                if($this->eParam->CodeError) {$this->CodeError=$this->eParam->CodeError;return;}
                $this->eParam->GetEnvironments();
                if($this->eParam->CodeError) {$this->CodeError=$this->eParam->CodeError;return;}
               }
          }

 function SaveEnvironments()
          { global $ENameCoF;
           $this->CodeError=0;

           //ip filter
           $fname='accounts/fip.dat';
           $ipbase = new Base($this->Fun,$this->Conf,$fname);
          $ipbase->BaseInit(1);
           if($ipbase->CodeError){$this->CodeError=$ipbase->CodeError;return;}

           for($i=0;$i<$ipbase->Size;$i++) {
                   if(!strcmp($ipbase->BaseFile[$i],$this->IP)) return;
           }

           $avis=0;
           $ahost=0;
           $aret='|';
           $this->Lang = new Languageslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Lang->LanguageslogInit();
           if($this->Lang->CodeError) {$this->CodeError=$this->Lang->CodeError;return;}
           $this->Lang->AddLanguageToLog( $this->Ctim , $this->ID , $this->Language[0] );
           if($this->Lang->CodeError) {$this->CodeError=$this->Lang->CodeError;return;}

           $this->Ipb = new IPslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Ipb->IPslogInit();
           if($this->Ipb->CodeError) {$this->CodeError=$this->Ipb->CodeError;return;}
           $this->Ipb->AddIPToLog( $this->Ctim , $this->ID , $this->IP, $this->UnForArh);
           if($this->Ipb->CodeError) {$this->CodeError=$this->Ipb->CodeError;return;}

           if($this->UnForArh) $ahost=1;

           $this->Ref = new Refererslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Ref->RefererslogInit();
           if($this->Ref->CodeError) {$this->CodeError=$this->Ref->CodeError;return;}
           $this->Ref->AddRefererToLog( $this->Ctim , $this->ID , $this->Referer );
           if($this->Ref->CodeError) {$this->CodeError=$this->Ref->CodeError;return;}

           $this->Vis = new Visitorslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Vis->VisitorslogInit();
           if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}

           $this->Uniq = new Uniqueslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Uniq->UniqueslogInit();
           if($this->Uniq->CodeError) {$this->CodeError=$this->Uniq->CodeError;return;}

           //unique
           $nrec=$this->Vis->GetIDbyVisitor( $this->ID );
           if($this->Vis->CodeError)
                  {
                   if($this->Vis->CodeError!=2) {$this->CodeError=$this->Vis->CodeError;return;}
                   $this->Uniq->AddUniqueToLog( $this->Ctim , $this->ID );
                   if($this->Uniq->CodeError) {$this->CodeError=$this->Uniq->CodeError;return;}
                   $avis=1;
                  }
           else
               {
                $bas=$this->Vis->GetRecordByID(0);
                if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}
                $rec=$this->Vis->GetRecordByID($nrec);
                if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}
                $rec=split("\|",$rec);
                $inter=$this->Ctim-($rec[0]+$bas);
                if($inter<=$this->Conf->ReturnIntervals[0])$aret=0;
                else
                    for($aret=1;$aret<count($this->Conf->ReturnIntervals);$aret++)
                         if(($inter>$this->Conf->ReturnIntervals[$aret-1])&&($inter<=$this->Conf->ReturnIntervals[$aret]))
                              break;
               }

           //visitor
           $this->Vis->AddVisitorToLog( $this->Ctim , $this->ID );
           if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}

           $this->TCur=$this->Vis->GetNumberUniqueID( $this->Ctim-$this->Conf->OnlineTime , $this->Ctim );
           if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}

           $this->Total->SaveRecord($this->TBeg);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $this->Total->AddRecord($this->TAll);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $this->Total->AddRecord($this->Ctim);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}
           $this->Total->AddRecord($this->TDay);
           if($this->Total->CodeError) {$this->CodeError=$this->Total->CodeError;return;}

           if($this->Conf->Version>=2)
               {
                $this->eParam->SaveEnvironments($ahost,$avis,$aret);
                if($this->eParam->CodeError) {$this->CodeError=$this->Arh->CodeError;return;}
               }
          }
 function OutImage()
          { global $ENameCoF;
           $this->Image->SetStatistic($this->TAll,$this->TDay,$this->TCur);
           if($this->Image->CodeError) {$this->CodeError=$this->Image->CodeError;return;}
           $this->Image->Coding ();
           $color=$this->Account->GetColorByID($this->Acc);
           if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
           $this->Image->Output ($color);

           exit;
          }

 function RemoveFile($pat,$tim,$ext)
          { global $ENameCoF;
           $this->CodeError=0;
           $per=$this->Conf->RemoveTime;
           $name=$this->Conf->FullBasePath.$pat.date('zY',$tim-$per).$ext;
           if(file_exists($name))
               if(!unlink($name))
                  {
                   $this->CodeError=14;
                   $ENameCoF=$this->Conf->FullBasePath.$pat.date('zY',$tim-$per).$ext;
                   return;
                  }
          }

 function DelFiles()
          { global $ENameCoF;
           $this->CodeError=0;
           $tim=$this->Lasttime;
           while($tim<=$this->Ctim)
             {
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/language/',$tim,'.lnb');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/referers/',$tim,'.rfb');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/visitors/',$tim,'.vsl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/visitors/',$tim,'.uql');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/referers/',$tim,'.rfl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/language/',$tim,'.lnl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/ips/',$tim,'.ipl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/ips/',$tim,'.ipb');
              if($this->CodeError) return;

           if($this->Conf->Version>=2)
             {
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/browsers/',$tim,'.brl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/cookies/',$tim,'.ckl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/dcolors/',$tim,'.dcl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/frames/',$tim,'.frl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/frames/',$tim,'.frb');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/java/',$tim,'.jvl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/jscripts/',$tim,'.jsl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/oss/',$tim,'.osl');
              if($this->CodeError) return;
              $this->RemoveFile('accounts/'.$this->Acc.'/daily/res/',$tim,'.rsl');
              if($this->CodeError) return;
             }
              $tim+=86400;
             }
          }
}

?>