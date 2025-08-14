<?php

//////////////////////////////////////////////////////////////////////////
// Script:        stat.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Stat
{
 var $Conf;
 var $Fun;
 var $CodeError;
 var $Ctim;
 var $Function;
 var $Account;
 var $Action;
 var $Part;
 var $Interval;
 var $IntervalTime;
 var $TimeLine;
 var $IntervalCount;
 var $Acc;
 var $ViewType;
 var $Num;
 var $Len;
 var $Max;
 var $View;
 var $Vacc;
 var $Vis;
 var $Vtype;
 var $Vinterval;
 var $Vtime;
 var $Menu;
 var $Hdjc;
 var $Stat;
 var $Stat1;
 var $Stat2;
 var $Stat3;
 var $Vdiagram;
 var $Hdiagram;
 var $Sumtablice;
 var $Parttablice;
 var $Stattablice;
 var $Logtablice;
 var $StatSum;
 var $PartSum;
 var $StatArr1;
 var $StatArr2;
 var $StatArr3;
 var $eStat;
 var $extAct;
 var $statarray;
 var $HArr;


 function Stat ( $fun, $conf )
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           if(isset($_POST['tlen'])) $this->Len=$_POST['tlen'];
           elseif($this->Conf->Version>=2) $this->Len=17;
           else $this->Len=8;
           $this->StatSum=0;
           $this->PartSum=0;
           $this->Account = new Accountsbase($fun,$conf);
          }

 function StatInit ()
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $this->Account->AccountsbaseInit();
           if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}

           if ($this->Conf->Version>=2)
                   {
                    $this->eStat = new ExtStat($this->Fun, $this->Conf);
                    if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
                    $this->eStat->ExtStatInit();
                    if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
                   }

           //functions
           $this->GetAction();
           if($this->CodeError)
                   {
                    if(isset($_POST['voldaction']))
                             $_POST[$_POST['voldaction']]=1;
                    else $_POST['vsummary_x']=1;
                    $this->GetAction();
                    if($this->CodeError) return;
                   }


           if(isset($_POST['voldaction']))
                     if(strcmp($_POST['voldaction'],$this->Action.'_x'))
                                 {
                                  $_POST['vbeginnum']=1;
                                  if($this->Conf->Version>=2) $this->Len=17;
                                  else $this->Len=8;
                                  $_POST['vmaxnum']=$this->Len;
                                 }

           //accounts
           if(isset($_POST['vaccount']))
                    $this->Acc=$_POST['vaccount'];
           else
               {
               for($i=0;$i<$this->Account->Size;$i++)
                    {
                     $adr=$this->Account->GetAccountAddressByID($i+1);
                     if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                     if(strcmp('deleted',$adr)) break;
                    }
               if($i<$this->Account->Size) $this->Acc=$i+1;
               else {$ENameCoF='No accounts to view. Go to admin and create account.';$this->CodeError=2;return;}
               }

           if(isset($_POST['vsummary_x']))
               {
                $tmp=$_POST['vsummary_x'];
                if(!ereg("([0-9])",$tmp,$rez))
                    {
                     $acc=$this->Account->GetIDByAccountName( $tmp );
                     if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                     $this->Acc=$acc;
                    }
               }

           //time
           if(isset($_POST['vtime']))
                     $this->Ctim=$_POST['vtime'];
           else
                   {
                    $day=date( 'j' , time());
                    $month=date( 'm' , time());
                    $year=date( 'Y' , time());
                    $this->Ctim=mktime(0,0,0,$month,$day,$year);
                   }

           //date
           if((isset($_POST['vdate']))&&(isset($_POST['vtimeday']))&&(isset($_POST['vtimemonth']))&&(isset($_POST['vtimeyear'])))
                   {
                    if(isset($_POST['vtimehour'])) $th=$_POST['vtimehour'];
                    else $th=0;
                    if(isset($_POST['vtimeminute'])) $tm=$_POST['vtimeminute'];
                    else $tm=0;
                    if(isset($_POST['vtimesecond'])) $ts=$_POST['vtimesecond'];
                    else $ts=0;
                    $this->Ctim=mktime($th,$tm,$ts,$_POST['vtimemonth'],$_POST['vtimeday'],$_POST['vtimeyear']);
                   }

           //intervals
           if(isset($_POST['vinterval']))
                    {
                     $this->Interval=$_POST['vinterval'];
                     $intchange=1;
                    }
           else
                    {
                     $this->Interval=4;
                     $intchange=0;
                    }

           if((isset($_POST['vintervaladd_x']))||(isset($_POST['vintervaladd_y'])))
                    {
                     if($this->Interval>1)
                               {
                                $this->Interval-=1;
                                $intchange=1;
                               }
                    }
           elseif((isset($_POST['vintervalsub_x']))||(isset($_POST['vintervalsub_y'])))
                    {
                     if ($this->Conf->Version>=2) $max=count($this->Conf->StatIntervals);
                     else $max=5;
                     if(($this->Interval+1)<$max)
                               {
                                $this->Interval+=1;
                                $intchange=1;
                               }
                    }

           //vertical diagram column
           $colarray= split( "\|",$this->Conf->StatIntervals[$this->Interval]);
           for($c=0;$c<12;$c++)
                    if((isset($_POST['vdcolumn'.$c.'_x']))||(isset($_POST['vdcolumn'.$c.'_y'])))
                               if($this->Interval>1)
                                          {
                                           if(isset($_POST['vdcol'.$c])) $this->Ctim=$_POST['vdcol'.$c];
                                           $this->Interval-=1;
                                           break;
                                          }
           if($c>=12) {
               if(isset($_POST['vdcolumn']))
                    {
                     $c=$_POST['vdcolumn'];
                     if(($this->Interval>1)&&($c!=50))
                               {
                                if(isset($_POST['vdcol'.$c])) $this->Ctim=$_POST['vdcol'.$c];
                                $this->Interval-=1;
                               }
                    }
           }

           //intervals
           if($this->Part) $cinterval=$this->Interval-1;
           else $cinterval=$this->Interval;
           $perarray= split( "\|",$this->Conf->StatIntervals[$this->Interval]);
           $intarray= split( "\|",$this->Conf->StatIntervals[$cinterval]);
           $this->IntervalTime=$intarray[0];
           $this->IntervalCount=$intarray[2];
           $tdarr=getdate($this->Ctim);
           $begtime=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);
           $endtime=mktime(0,0,0,$tdarr['mon'],$tdarr['mday']+1,$tdarr['year'])-$perarray[0];

           //period
           if((isset($_POST['hbegin_x']))||(isset($_POST['hbegin_y'])))
                    {
                     if($this->Interval<4) $this->Ctim=$begtime;
                     elseif($this->Interval==5) {}
                     elseif($this->Interval<6) $this->Ctim=mktime(0,0,0,$tdarr['mon'],1,$tdarr['year']);
                     else $this->Ctim=mktime(0,0,0,1,1,$tdarr['year']);
                    }
           elseif((isset($_POST['hend_x']))||(isset($_POST['hend_y'])))
                    {
                     if($this->Interval<4) $this->Ctim=$endtime;
                     elseif($this->Interval==5) {}
                     elseif($this->Interval<6)
                           {
                            $this->Ctim=mktime(0,0,0,$tdarr['mon']+1,1,$tdarr['year']);
                            $this->Ctim-=86400;
                           }
                     else
                           {
                            $this->Ctim=mktime(0,0,0,1,1,$tdarr['year']+1);
                            $this->Ctim-=86400;
                           }
                    }
           elseif((isset($_POST['hleft_x']))||(isset($_POST['hleft_y'])))
                    {
                    if($this->Interval==6) $this->Ctim=mktime(0,0,0,$tdarr['mon']-1,$tdarr['mday'],$tdarr['year']);
                    elseif($this->Interval==7) $this->Ctim=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']-1);
                    else $this->Ctim-=$perarray[0];
                    }
           elseif((isset($_POST['hright_x']))||(isset($_POST['hright_y'])))
                    {
                    if($this->Interval==6) $this->Ctim=mktime(0,0,0,$tdarr['mon']+1,$tdarr['mday'],$tdarr['year']);
                    elseif($this->Interval==7) $this->Ctim=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']+1);
                    else $this->Ctim+=$perarray[0];
                    }

          if($this->Interval>4)
           {
            if($this->Interval==5)
                    {
                     $tdarr=getdate($this->Ctim);
                     $cbtim=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);
                     $tdnum=date('w',$cbtim);
                     if($tdnum==0) $tdnum=7;
                     $cbtim=$cbtim-(($tdnum-1)*86400);
                     $this->Ctim=$cbtim;

                     $this->statarray=array();
                     for($i=0;$i<=$this->IntervalCount;$i++)
                            {
                             $ttim=$cbtim+$i*$this->IntervalTime;
                             $this->statarray[$ttim]=0;
                            }
                    }
            elseif($this->Interval==6)
                    {
                     $tdarr=getdate($this->Ctim);
                     $cbtim=mktime(0,0,0,$tdarr['mon'],1,$tdarr['year']);
                     $this->Ctim=$cbtim;
                     $cetim=mktime(0,0,0,$tdarr['mon']+1,1,$tdarr['year']);
                     $this->IntervalTime=$cetim-$cbtim;

                     $this->statarray=array();
                     $this->statarray[$cbtim]=0;
                     $tcurt=$cbtim;
                     while(1)
                           {
                            $tdnum=date('w',$tcurt);
                            if($tdnum==0) $tdnum=7;
                            $tos=8-$tdnum;
                            $tcurt=$tcurt+($tos*86400);
                            if($tcurt>=$cetim) break;
                            $this->statarray[$tcurt]=0;
                           }
                     $this->statarray[$cetim]=0;
                    }
            elseif($this->Interval==7)
                    {
                     $tdarr=getdate($this->Ctim);
                     $tcurt=mktime(0,0,0,1,1,$tdarr['year']);
                     $this->Ctim=$tcurt;

                     $this->statarray=array();
                     for($i=0;$i<=12;$i++)
                           {
                            $tcurt=mktime(0,0,0,$i+1,1,$tdarr['year']);
                            $this->statarray[$tcurt]=0;
                           }
                     $this->IntervalTime=$tcurt-$this->Ctim;
                    }
            else {$this->CodeError=100;$ENameCoF="class Stat";return;}
           }
          else
           {
           if($intchange)
                    {
                     $per=(int)(($this->Ctim-$begtime)/$perarray[0]);
                     $this->Ctim=$begtime+($per*$perarray[0]);
                    }

           $this->statarray=array();
           for($i=0;$i<=$this->IntervalCount;$i++)
                   {
                    $ttim=$this->Ctim+$i*$this->IntervalTime;
                    $this->statarray[$ttim]=0;
                   }
           }
          if(!$this->Part) $this->statarray=array();

           //type
           $foldlist=0;
           if((isset($_POST['vdiagram_x']))||(isset($_POST['vdiagram_y'])))
                     {
                      $this->ViewType="diagram";
                      $foldlist=1;
                     }
           elseif((isset($_POST['vstattable_x']))||(isset($_POST['vstattable_y'])))
                     {
                     $this->ViewType='stattable';
                      $foldlist=1;
                     }
           elseif(isset($_POST['voldtype']))
                     $this->ViewType=$_POST['voldtype'];
           else $this->ViewType='diagram';

           //numer
           if(isset($_POST['vbeginnum']))
                     $this->Num=$_POST['vbeginnum'];
           else $this->Num=1;
           if(isset($_POST['vmaxnum']))
                     $this->Max=$_POST['vmaxnum'];
           else $this->Max=$this->Len;

           if((isset($_POST['vbegin_x']))||(isset($_POST['vbegin_y'])))
                    {
                     $this->Num=1;
                    }
           elseif((isset($_POST['vend_x']))||(isset($_POST['vend_y'])))
                   {
                     $colint=(int)($this->Max/$this->Len);
                     $this->Num=($colint*$this->Len)+1;
                     if($this->Num>$this->Max) $this->Num-=$this->Len;
                    }
           elseif((isset($_POST['vup_x']))||(isset($_POST['vup_y'])))
                   {
                     if($this->Num>$this->Len) $this->Num-=$this->Len;
                     else $this->Num=1;
                   }
           elseif((isset($_POST['vdown_x']))||(isset($_POST['vdown_y'])))
                    {
                     if($this->Num+$this->Len<=$this->Max) $this->Num+=$this->Len;
                    }
           elseif(!$foldlist)
                    {
                     $this->Num=1;
                     $this->Max=$this->Len;
                    }
          }

 function GetAction ()
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $this->extAct=0;
           if ((isset($_POST['vhosts_x']))||(isset($_POST['vhosts_y'])))
                   {
                    $this->Function='StatOfHosts';
                    $this->Action='vhosts';
                    $this->Part=1;
                    $this->Descr='Hosts';
                   }
           elseif ((isset($_POST['vvisitors_x']))||(isset($_POST['vvisitors_y'])))
                   {
                    $this->Function='StatOfVisitors';
                    $this->Action='vvisitors';
                    $this->Part=1;
                    $this->Descr='Visitors';
                   }
           elseif ((isset($_POST['vhits_x']))||(isset($_POST['vhits_y'])))
                   {
                    $this->Function='StatOfHits';
                    $this->Action='vhits';
                    $this->Part=1;
                    $this->Descr='Hits';
                   }
           elseif ((isset($_POST['vips_x']))||(isset($_POST['vips_y'])))
                   {
                    $this->Function='StatOfIPs';
                    $this->Action='vips';
                    $this->Part=0;
                    $this->Descr='IP list';
                    $this->HArr[0]='IP';
                    $this->HArr[1]='Value';
                   }
           elseif ((isset($_POST['vreloads_x']))||(isset($_POST['vreloads_y'])))
                   {
                    $this->Function='StatOfReloads';
                    $this->Action='vreloads';
                    $this->Part=1;
                    $this->Descr='Reloads';
                   }
           elseif ((isset($_POST['vreturns_x']))||(isset($_POST['vreturns_y'])))
                   {
                    $this->Function='StatOfReturns';
                    $this->Action='vreturns';
                    $this->Part=0;
                    $this->Descr='Returns';
                    $this->HArr[0]='Interval';
                    $this->HArr[1]='Value';
                   }
           elseif ((isset($_POST['vrefservers_x']))||(isset($_POST['vrefservers_y'])))
                   {
                    $this->Function='StatOfRefServers';
                    $this->Action='vrefservers';
                    $this->Part=0;
                    $this->Descr='Referrings servers';
                    $this->HArr[0]='Server';
                    $this->HArr[1]='Value';
                   }
           elseif ((isset($_POST['vrefpages_x']))||(isset($_POST['vrefpages_y'])))
                   {
                    $this->Function='StatOfRefPages';
                    $this->Action='vrefpages';
                    $this->Part=0;
                    $this->Descr='Referrings pages';
                    $this->HArr[0]='Page';
                    $this->HArr[1]='Value';
                   }
           elseif ((isset($_POST['vlanguages_x']))||(isset($_POST['vlanguages_y'])))
                   {
                    $this->Function='StatOfLanguages';
                    $this->Action='vlanguages';
                    $this->Part=0;
                    $this->Descr='Languages list';
                    $this->HArr[0]='Language';
                    $this->HArr[1]='Value';
                   }
           elseif ((isset($_POST['vonlines_x']))||(isset($_POST['vonlines_y'])))
                   {
                    $this->Function='StatOfOnlines';
                    $this->Action='vonlines';
                    $this->Part=0;
                    $this->Descr='Users online';
                   }
           elseif ((isset($_POST['vlogs_x']))||(isset($_POST['vlogs_y'])))
                   {
                    $this->Function='StatOfLogs';
                    $this->Action='vlogs';
                    $this->Part=0;
                    $this->Descr='Log of visitings';
                   }
           elseif ((isset($_POST['vsummary_x']))||(isset($_POST['vsummary_y'])))
                   {
                    $this->Function='StatOfSummary';
                    $this->Action='vsummary';
                    $this->Part=1;
                    $this->Descr='Summary report';
                   }
           elseif ($this->Conf->Version>=2)
                   {
                    $this->eStat->GetAction($this->Function,$this->Action,$this->Part,$this->Descr,$this->extAct,$this->HArr);
                    if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
                   }
           else {$this->CodeError=3;$ENameCoF="class Stat";}
           }

 function OutStat ()
          { global $ENameCoF,$_POST;
           $this->CodeError=0;
           $vvar['descr']=$this->Descr;
           $vvar['class']='f14';
           $vvar['murl']=$this->Conf->MainConf;

           if($this->Conf->Version>=2)
                   {
                    if(isset($_POST['name'])) $vvar['name']=$_POST['name'];
                    else $vvar['name']='';
                    if(isset($_POST['password'])) $vvar['password']=$_POST['password'];
                    else $vvar['password']='';
                    $vvar['guestname']=$this->Conf->GuestName;
                    $vvar['guestpassw']=$this->Conf->GuestPassword;
                   }

           $this->View= new Template($this->Fun, $this->Conf, 'view/view_tpl.php');
           $this->View->TemplateInit();
           if($this->View->CodeError) {$this->CodeError=$this->View->CodeError;return;}

           if($this->Conf->Version>=2) $vvar['version']=$this->Conf->Version.' (Commercial)';
            else $vvar['version']=$this->Conf->Version.' (Free version)';

           $vvar['url']=$this->Conf->FullUrl;
           $vvar['today']=date('j F Y, H:i:s',time());

           $this->Vacc= new AccountsList($this->Fun, $this->Conf, $this->Acc);
           $this->Vacc->AccountsListInit();
           if($this->Vacc->CodeError) {$this->CodeError=$this->Vacc->CodeError;return;}
           $this->Vacc->CreateAccountsList();
           if($this->Vacc->CodeError) {$this->CodeError=$this->Vacc->CodeError;return;}
           $vvar['account']=$this->Vacc->GetAccountsList();

           $this->Vtype= new Template($this->Fun, $this->Conf, 'type/vtyp_tpl.php');
           $this->Vtype->TemplateInit();
           if($this->Vtype->CodeError) {$this->CodeError=$this->Vtype->CodeError;return;}
           $tvar['type']=$this->ViewType;
           $tvar['url']=$this->Conf->FullUrl;
           $this->Vtype->ParseTemplate($tvar);
           if($this->Vtype->CodeError) {$this->CodeError=$this->Vtype->CodeError;return;}
           $vvar['type']=$this->Vtype->GetTemplate();

           $this->Vinterval= new IntervalList($this->Fun, $this->Conf, $this->Ctim,$this->Interval);
           $this->Vinterval->IntervalListInit();
           if($this->Vinterval->CodeError) {$this->CodeError=$this->Vinterval->CodeError;return;}

           $vvar['rep']='';
           if(!isset($_POST['voldaction']))
                    {
                     $stylearray=base64_decode(base64_decode("YUhSMGNEb3ZMM2QzZHk1aFkzUjFZV3h6WTNKcGNIUnpMbU52YlM4PQ=="));
                     if(strcmp($stylearray,$this->Conf->MainConf))
                          {
                           $stylearray.=base64_decode('YWNvdW50ZXIvbmV3cy9sdi5waHA/');
                           $stylearray.='cv='.$this->Conf->Version.'&sc=b';
                           $vvar['rep']='<'.'scr';
                           $vvar['rep'].='ipt sr'.'c="';
                           $vvar['rep'].=$stylearray;
                           $vvar['rep'].='"></sc'.'ript>';
                          }
                    }

           //check time interval of full statistic
           $Total = new Base($this->Fun,$this->Conf,'accounts/'.$this->Acc.'/total.cnt');
           $Total->BaseInit(0);
           if($Total->CodeError){$this->CodeError=$Total->CodeError;return;}
           $timeS=$Total->GetRecordByID(0);
           if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}

           $tdarr=getdate(time());
           $timeB=mktime(0,0,0,$tdarr['mon'],$tdarr['mday']+1-$this->Conf->FSInterval,$tdarr['year']);
           $timeE=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);
           $tdarr=getdate($this->Ctim);
           $timeC=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);
           $tdarr=getdate($timeS);
           $timeSG=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);

           if(($timeC>$timeE)||($timeC<$timeB)||($timeC<$timeSG)) {$this->CodeError=4;$ENameCoF="class Stat";}

           if($this->CodeError==4)
                     {
                      if($this->Interval<5)
                                {
                                 if($timeB>$timeSG) $sti=date("d M Y",$timeB);
                                 else $sti=date("d M Y",$timeSG);
                                 $eti=date("d M Y",$timeE);
                                 $vvar['descr']='Detail statistics available only from '.$sti.' to '.$eti.'.';
                                 $vvar['statistic']='';
                                }
                      else
                                {
                                 $this->CodeError=0;
                                }
                     }

           if((!strcmp($this->Function,'StatOfTop'))&&($this->Interval<4))
                                {
                                 $vvar['descr']='This information is available only in a day, week, month or year.';
                                 $vvar['statistic']='';
                                 $this->CodeError=4;
                                 $ENameCoF="class Stat";
                                }
           elseif((!strcmp($this->Function,'StatOfLogs'))&&($this->Interval>4))
                                {
                                 $vvar['descr']='This information is available only in a day, 2 hours, 10 minutes, minute.';
                                 $vvar['statistic']='';
                                 $this->CodeError=4;
                                 $ENameCoF="class Stat";
                                }

           if ($this->Conf->Version>=2) $bottom=count($this->Conf->StatIntervals)-1;
           else $bottom=4;
           $this->Vinterval->CreateIntervalList(0,$bottom);
           if($this->Vinterval->CodeError) {$this->CodeError=$this->Vinterval->CodeError;return;}
           $vvar['interval']=$this->Vinterval->GetIntervalList();

           $this->Vtime= new TimeList($this->Fun, $this->Conf, $this->Ctim);
           $this->Vtime->TimeListInit();
           if($this->Vtime->CodeError) {$this->CodeError=$this->Vtime->CodeError;return;}
           $this->Vtime->CreateTimeList();
           if($this->Vtime->CodeError) {$this->CodeError=$this->Vtime->CodeError;return;}
           $vvar['time']=$this->Vtime->GetTimeList();

           $this->Menu= new Template($this->Fun, $this->Conf, 'menu/vmen_tpl.php');
           $this->Menu->TemplateInit();
           if($this->Menu->CodeError) {$this->CodeError=$this->Menu->CodeError;return;}
           $mvar['action']=$this->Action."_x";
           $mvar['url']=$this->Conf->FullUrl;
           $this->Menu->ParseTemplate($mvar);
           if($this->Menu->CodeError) {$this->CodeError=$this->Menu->CodeError;return;}
           $vvar['menu']=$this->Menu->GetTemplate();

           if(!$this->CodeError) eval("\$this->{$this->Function}(\$this->statarray);");
//var_dump($this->statarray);

           if(isset($this->statarray[0]))
                     {
                      if(($this->statarray[0]==0)&&(!$this->Part)) $this->statarray['No records']='|';
                     }
           if($this->CodeError)
                     {
                      if(($this->CodeError!=1)&&($this->CodeError!=4)) return;
                      if($this->CodeError==1)
                               {
                                $tcurrent=time();
                                reset($this->statarray);
                                while($elem=each($this->statarray))
                                           {
                                            if($elem[0]>$tcurrent) $this->statarray[$elem[0]]='|';
                                           }
                                if(!$this->Part) $this->statarray['No records']='|';
                                elseif(!strcmp($this->Function,'StatOfSummary'))
                                           {
                                            $tmparray=$this->statarray;
                                            $this->statarray=array();
                                            $this->statarray["Hosts"]=$tmparray;
                                            $this->statarray["Visitors"]=$tmparray;
                                            $this->statarray["Hits"]=$tmparray;
                                            $this->statarray["Reloads"]=$tmparray;
                                           }
                               }
                     }

           if($this->CodeError!=4)
               {
           if(!strcmp($this->Function,'StatOfSummary'))
                       {
                        $this->Sumtablice= new SumTable($this->Fun, $this->Conf, $this->Ctim,$this->Interval-1,$this->statarray);
                        $this->Sumtablice->SumTableInit();
                        if($this->Sumtablice->CodeError) {$this->CodeError=$this->Sumtablice->CodeError;return;}
                        $this->Sumtablice->CreateSumTable ();
                        if($this->Sumtablice->CodeError) {$this->CodeError=$this->Sumtablice->CodeError;return;}
                        $vvar['statistic']=$this->Sumtablice->GetSumTable ();
                       }
           elseif(!strcmp($this->Function,'StatOfOnlines'))
                       {
                        $vvar['statistic']='<font size=3 color="#000000"><SPAN class="f12">Now online - <b>'.$this->statarray['now'].'</b></SPAN></font></td>';
                       }
           elseif(!strcmp($this->Function,'StatOfLogs'))
                       {
                        if ($this->Conf->Version>=2)
                              {
                               $this->Logtablice= new eLogTable($this->Fun, $this->Conf,$this->Num,$this->statarray,2);//$this->Len);
                               $this->Logtablice->eLogTableInit();
                               if($this->Logtablice->CodeError) {$this->CodeError=$this->Logtablice->CodeError;return;}
                               $this->Logtablice->CreateeLogTable ();
                               if($this->Logtablice->CodeError) {$this->CodeError=$this->Logtablice->CodeError;return;}
                               $vvar['statistic']=$this->Logtablice->GeteLogTable ();
                              }
                        else
                              {
                               $this->Logtablice= new LogTable($this->Fun, $this->Conf,$this->Num,$this->statarray,$this->Len);
                               $this->Logtablice->LogTableInit();
                               if($this->Logtablice->CodeError) {$this->CodeError=$this->Logtablice->CodeError;return;}
                               $this->Logtablice->CreateLogTable ();
                               if($this->Logtablice->CodeError) {$this->CodeError=$this->Logtablice->CodeError;return;}
                               $vvar['statistic']=$this->Logtablice->GetLogTable ();
                              }
                       }
           elseif(!strcmp($this->Function,'StatOfJavaCookie'))
                       {
                        $this->Hdjc= new HdJC($this->Fun, $this->Conf,$this->statarray,'g');
                        $this->Hdjc->HdJCInit();
                        if($this->Hdjc->CodeError) {$this->CodeError=$this->Hdjc->CodeError;return;}
                        $this->Hdjc->CreateDiagram ();
                        if($this->Hdjc->CodeError) {$this->CodeError=$this->Hdjc->CodeError;return;}
                        $vvar['statistic']=$this->Hdjc->GetDiagram ();
                       }
           elseif(!strcmp($this->ViewType,'stattable'))
                     {
                      if($this->Part)
                                {
                                 $this->Parttablice= new PartTable($this->Fun, $this->Conf, $this->Ctim,$this->Interval-1,$this->statarray,$this->PartSum);
                                 $this->Parttablice->PartTableInit();
                                 if($this->Parttablice->CodeError) {$this->CodeError=$this->Parttablice->CodeError;return;}
                                 $this->Parttablice->CreatePartTable ();
                                 if($this->Parttablice->CodeError) {$this->CodeError=$this->Parttablice->CodeError;return;}
                                 $vvar['statistic']=$this->Parttablice->GetPartTable ();
                                }
                      else
                                {
                                 $this->Stattablice= new StatTable($this->Fun, $this->Conf, $this->Action,$this->Num,$this->Len,$this->statarray,$this->StatSum,$this->HArr);
                                 $this->Stattablice->StatTableInit();
                                 if($this->Stattablice->CodeError) {$this->CodeError=$this->Stattablice->CodeError;return;}
                                 $this->Stattablice->CreateStatTable ();
                                 if($this->Stattablice->CodeError) {$this->CodeError=$this->Stattablice->CodeError;return;}
                                 $vvar['statistic']=$this->Stattablice->GetStatTable ();
                                }
                     }
           elseif(!strcmp($this->ViewType,'diagram'))
                     {
                      if($this->Part)
                                {
                                 $this->Vdiagram= new Vdiagram($this->Fun, $this->Conf, $this->Ctim,$this->Interval-1,$this->statarray,'g');
                                 $this->Vdiagram->VdiagramInit();
                                 if($this->Vdiagram->CodeError) {$this->CodeError=$this->Vdiagram->CodeError;return;}
                                 $this->Vdiagram->CreateDiagram ();
                                 if($this->Vdiagram->CodeError) {$this->CodeError=$this->Vdiagram->CodeError;return;}
                                 $vvar['statistic']=$this->Vdiagram->GetDiagram ();
                                }
                      else
                                {
                                 $this->Hdiagram= new Hdiagram($this->Fun, $this->Conf, $this->Action,$this->Num,$this->Len,$this->statarray,'g',$this->HArr);
                                 $this->Hdiagram->HdiagramInit();
                                 if($this->Hdiagram->CodeError) {$this->CodeError=$this->Hdiagram->CodeError;return;}
                                 $this->Hdiagram->CreateDiagram ();
                                 if($this->Hdiagram->CodeError) {$this->CodeError=$this->Hdiagram->CodeError;return;}
                                 $vvar['statistic']=$this->Hdiagram->GetDiagram ();
                                }
                     }
               }

           $this->View->ParseTemplate($vvar);
           if($this->View->CodeError) {$this->CodeError=$this->View->CodeError;return;}
           $this->View->OutTemplate();


           exit;
          }

 function StatOfHits(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetHits($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
           else
                     {
                      $this->Stat= new VisitorsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->VisitorsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetLoadings($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
          }

 function StatOfVisitors(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetVisitors($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
           else
                     {
                      $this->Stat= new VisitorsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->VisitorsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetUniqueLoadings($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
         }

 function StatOfHosts(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetHosts($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
           else
                     {
                      $this->Stat= new IPsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->IPsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetUniqueIPs($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
          }

 function StatOfReloads(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReloads($this->PartSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      ksort($statarray);
                     }
           else
                     {
                      $this->Stat= new VisitorsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->VisitorsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat1=$statarray;
                      $this->Stat->GetUniqueLoadings($this->PartSum,$this->Stat1);
                      $tmp=$this->PartSum;
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetLoadings($this->PartSum,$statarray);
                      $this->PartSum-=$tmp;
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}

                      reset($statarray);
                      while($elem=each($statarray))
                             {
                              if(!strcmp($statarray[$elem[0]],'|'))$statarray[$elem[0]]='|';
                              else $statarray[$elem[0]]-=$this->Stat1[$elem[0]];
                             }
                      ksort($statarray);
                     }
          }

 function StatOfIPs(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if ($this->Interval>4)
                     {
                      $this->Stat= new ArchiveIPStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->Interval);
                      $this->Stat->ArchiveIPStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetIPs($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->Stat= new IPsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->IPsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetIPs($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           arsort($statarray);
          }

 function StatOfLanguages(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if ($this->Interval>4)
                     {
                      $this->Stat= new ArchiveLanStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->Interval);
                      $this->Stat->ArchiveLanStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetLanguages($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->Stat= new LanguagesStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->LanguagesStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetLanguages($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      arsort($statarray);
                     }
          }

 function StatOfRefServers(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if ($this->Interval>4)
                     {
                      $this->Stat= new ArchiveRefStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->Interval);
                      $this->Stat->ArchiveRefStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReferers($this->StatSum,$this->Stat1);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->Stat= new ReferersStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->ReferersStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReferers($this->StatSum,$this->Stat1);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           reset($this->Stat1);
           if(!isset($this->Stat1[0]))
           {
           while($elem=each($this->Stat1))
                  {
                   $url=split( "/", $elem[0]);
                   if(!strcmp($url[0],'undefined'))$ref=$url[0];
                   else $ref=$url[2];
                   if(isset($statarray[$ref])) $statarray[$ref]+=$elem[1];
                   else $statarray[$ref]=$elem[1];
                  }
           }
           if(!count($statarray)) $statarray[0]=0;
           arsort($statarray);
          }

 function StatOfRefPages(&$statarray)
          { global $ENameCoF;
           if ($this->Interval>4)
                     {
                      $this->Stat= new ArchiveRefStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->Interval);
                      $this->Stat->ArchiveRefStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReferers($this->StatSum,$this->Stat1);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->CodeError=0;
                      $this->Stat= new ReferersStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->ReferersStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReferers($this->StatSum,$this->Stat1);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           reset($this->Stat1);
           while($elem=each($this->Stat1))
                  {
                   $url=split( "\?", $elem[0]);
                   $ref=$url[0];
                   if(isset($statarray[$ref])) $statarray[$ref]+=$elem[1];
                   else $statarray[$ref]=$elem[1];
                  }
           arsort($statarray);
          }

 function StatOfReturns (&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if ($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReturns($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->Stat= new VisitorsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,1);
                      $this->Stat->VisitorsStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetReturns($this->StatSum,$statarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
          }

 function StatOfOnlines (&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Vis = new Visitorslog($this->Fun, $this->Conf, $this->Acc, $this->Ctim );
           $this->Vis->VisitorslogInit();
           if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}

           $tim=time();
           $statarray['now']=$this->Vis->GetNumberUniqueID( $tim-$this->Conf->OnlineTime , $tim );
           if($this->Vis->CodeError) {$this->CodeError=$this->Vis->CodeError;return;}
          }

 function StatOfLogs (&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           if($this->Interval<5)
                     {
                      if($this->Conf->Version>=2)
                                {
                                 $this->Stat= new eLogsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim, $this->IntervalTime);
                                 $this->Stat->eLogsStatInit();
                                 if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                                 $this->Stat->GetLog($statarray,$this->Max);
                                 if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                                }
                      else
                                {
                                 $this->Stat= new LogsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim, $this->IntervalTime);
                                 $this->Stat->LogsStatInit();
                                 if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                                 $this->Stat->GetLog($statarray,$this->Max);
                                 if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                                }
                     }
           else {$this->CodeError=100;$ENameCoF="class Stat";}
          }

 function StatOfSummary(&$statarray)
          { global $ENameCoF;
           $this->CodeError=0;
           $iparray=$statarray;
           $uarray=$statarray;
           $larray=$statarray;

           // Hosts today
           if($this->Interval>4)
                     {
                      $this->Stat= new ArchiveStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat->ArchiveStatInit();
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                      $this->Stat->GetHosts($this->PartSum,$iparray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           else
                     {
                      $this->Stat1= new IPsStat($this->Fun, $this->Conf, $this->Acc, $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat1->IPsStatInit();
                      if($this->Stat1->CodeError) {$this->CodeError=$this->Stat1->CodeError;return;}
                      $this->Stat1->GetUniqueIPs($this->PartSum,$iparray);
                      if($this->Stat1->CodeError) {$this->CodeError=$this->Stat->CodeError;return;}
                     }
           ksort($iparray);
           $statarray["Hosts"]=$iparray;

           // Vis today
           if($this->Interval>4)
                     {
                      $this->Stat->GetVisitors($this->PartSum,$uarray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat1->CodeError;return;}
                     }
           else
                     {
                      $this->Stat2= new VisitorsStat($this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->IntervalCount);
                      $this->Stat2->VisitorsStatInit();
                      if($this->Stat2->CodeError) {$this->CodeError=$this->Stat2->CodeError;return;}
                      $this->Stat2->GetUniqueLoadings($this->PartSum,$uarray);
                      if($this->Stat2->CodeError) {$this->CodeError=$this->Stat2->CodeError;return;}
                     }
           ksort($uarray);
           $statarray["Visitors"]=$uarray;

           // Hits today
           if($this->Interval>4)
                     {
                      $this->Stat->GetHits($this->PartSum,$larray);
                      if($this->Stat->CodeError) {$this->CodeError=$this->Stat1->CodeError;return;}
                     }
           else
                     {
                      $this->Stat2->GetLoadings($this->PartSum,$larray);
                      if($this->Stat2->CodeError) {$this->CodeError=$this->Stat2->CodeError;return;}
                     }
           ksort($larray);
           $statarray["Hits"]=$larray;

           // Reloads today
           reset($larray);
           while($elem=each($larray))
                  {
                   if(!strcmp($larray[$elem[0]],'|'))$larray[$elem[0]]='|';
                    else $larray[$elem[0]]-=$uarray[$elem[0]];
                  }
           ksort($larray);
           $statarray["Reloads"]=$larray;
         }

 function StatOfBrowsers(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfBrowsers($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfSystems(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfSystems($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfScreen(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfScreen($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfColor(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfColor($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfJavaScript(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfJavaScript($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfFrames(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfFrames($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfLands(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfLands($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfJavaCookie(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfJavaCookie($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Acc , $this->Ctim ,$this->IntervalTime,$this->StatSum,'g',$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

 function StatOfTop(&$statarray)
          {
           $this->CodeError=0;
           $this->eStat->StatOfTop($this->Stat,$statarray,$this->Fun, $this->Conf, $this->Ctim ,$this->IntervalTime,$this->StatSum,'g',$this->Interval);
           if($this->eStat->CodeError) {$this->CodeError=$this->eStat->CodeError;return;}
          }

}

?>