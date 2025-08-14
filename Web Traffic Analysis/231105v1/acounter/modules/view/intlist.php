<?php

//////////////////////////////////////////////////////////////////////////
// Script:        intlist.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class IntervalList
{
 var $List;
 var $Conf;
 var $Fun;
 var $Active;
 var $Ctim;
 var $Item;
 var $Items;
 var $Parts;
 var $CodeError;

 function IntervalList ( $fun, $conf, $ctime, $active )
          {
           $this->CodeError=0;
           $this->Ctim=$ctime;
           $this->Active=$active;
           $this->Fun = $fun;
           $this->Conf = $conf;
          }

 function IntervalListInit ()
          {
           $this->CodeError=0;
          }

 function CreateIntervalList ($top,$bottom)
          {
           $this->CodeError=0;
           $this->List= new Template($this->Fun,$this->Conf,'interval/int_tpl.php');
           $this->List->TemplateInit();
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
           $vvar['url']=$this->Conf->FullUrl;
           $vvar['ext']=' onChange="jump_fun(this.form)"';

           $this->Item= new Template($this->Fun,$this->Conf,'interval/inti_tpl.php');
           $this->Item->TemplateInit();
           if($this->Item->CodeError) {$this->CodeError=$this->Item->CodeError;return;}

           $this->Items= new Template($this->Fun,$this->Conf,'');

           for($c=$bottom;$c>$top;$c--) {
                  $intarray= split( "\|",$this->Conf->StatIntervals[$c]);
                  $tvar['value']=$c;
                  $tvar['name']=$intarray[1];
                  if($c==$this->Active) $tvar['active']=' selected';
                  else $tvar['active']='';
                  $this->Items->AddTemplate($this->Item->GetTemplate());
                  $this->Items->ParseTemplate ($tvar);
                  if($this->Items->CodeError) {$this->CodeError=$this->Items->CodeError;return;}
           }
           $vvar['items']=$this->Items->GetTemplate();

           $this->Parts = new Template($this->Fun,$this->Conf,'');

           $prevarray= split( "\|",$this->Conf->StatIntervals[$this->Active]);
           if($this->Active==4)
                    {
                     $tdarr=getdate($this->Ctim);
                     $cbtim=mktime(0,0,0,$tdarr['mon'],$tdarr['mday'],$tdarr['year']);
                     $tdnum=date('w',$cbtim);
                     if($tdnum==0) $tdnum=7;
                     $cbtim=$cbtim-(($tdnum-1)*86400);

                     for($i=0;$i<$prevarray[2];$i++)
                            {
                            $ttim=$cbtim+$i*$prevarray[0];
                            $tvar['name']=date('j F Y',$ttim);
                            if($ttim==$this->Ctim) $tvar['active']=' selected';
                            else $tvar['active']='';
                            $tvar['value']=$ttim;
                            $this->Parts->AddTemplate($this->Item->GetTemplate());
                            $this->Parts->ParseTemplate ($tvar);
                            if($this->Parts->CodeError) {$this->CodeError=$this->Parts->CodeError;return;}
                            }
                    }
           elseif($this->Active==5)
                    {
                     $tdarr=getdate($this->Ctim);
                     $cbtim=mktime(0,0,0,$tdarr['mon'],1,$tdarr['year']);
                     $tdnum=date('w',$cbtim);
                     if($tdnum==0) $tdnum=7;
                     $cbtim=$cbtim-(($tdnum-1)*86400);
                     $cetim=mktime(0,0,0,$tdarr['mon']+1,1,$tdarr['year']);
                     $ttim=$cbtim;
                     while(1)
                           {
                            $tdnum=date('w',$ttim);
                            if($tdnum==0) $tdnum=7;
                            $tos=8-$tdnum;
                            $endtim=$ttim+($tos*86400);
                            if($ttim>=$cetim) break;
                            $tvar['name']=date('j M',$ttim).' - '.date('j M',$endtim-40000);
                            if(date("z",$ttim)==date("z",$this->Ctim)) $tvar['active']=' selected';
                            else $tvar['active']='';
                            $tvar['value']=$ttim;
                            $this->Parts->AddTemplate($this->Item->GetTemplate());
                            $this->Parts->ParseTemplate ($tvar);
                            if($this->Parts->CodeError) {$this->CodeError=$this->Parts->CodeError;return;}
                            $ttim=$endtim;
                           }
                    }
            elseif($this->Active==6)
                    {
                     $tdarr=getdate($this->Ctim);
                     for($i=0;$i<12;$i++)
                           {
                            $ttim=mktime(0,0,0,$i+1,1,$tdarr['year']);
                            $tvar['name']=date('F Y',$ttim);
                            if($ttim==$this->Ctim) $tvar['active']=' selected';
                            else $tvar['active']='';
                            $tvar['value']=$ttim;
                            $this->Parts->AddTemplate($this->Item->GetTemplate());
                            $this->Parts->ParseTemplate ($tvar);
                            if($this->Parts->CodeError) {$this->CodeError=$this->Parts->CodeError;return;}
                           }
                    }
            elseif($this->Active==7)
                    {
                     $tdarr=getdate($this->Ctim);
                     for($i=2002;$i<2006;$i++)
                           {
                            $ttim=mktime(0,0,0,1,1,$i);
                            $tvar['name']=date('Y',$ttim);
                            if($ttim==$this->Ctim) $tvar['active']=' selected';
                            else $tvar['active']='';
                            $tvar['value']=$ttim;
                            $this->Parts->AddTemplate($this->Item->GetTemplate());
                            $this->Parts->ParseTemplate ($tvar);
                            if($this->Parts->CodeError) {$this->CodeError=$this->Parts->CodeError;return;}
                           }
                    }
            else
                    {
                     $prevarray= split( "\|",$this->Conf->StatIntervals[$this->Active+1]);
                     $curarray= split( "\|",$this->Conf->StatIntervals[$this->Active]);
                     $sdate=getdate($this->Ctim);
                     $timeS=mktime(0,0,0,$sdate['mon'],$sdate['mday'],$sdate['year']);
                     $t=(int)(($this->Ctim-$timeS)/$prevarray[0]);
                     $timeB=$timeS+$t*$prevarray[0];
                     for($c=0;$c<$curarray[2];$c++)
                              {
                               $btime=$timeB+$c*$curarray[0];
                               $etime=$timeB+($c+1)*$curarray[0];
                               $tvar['name']=date('H:i:s',$btime).' - '.date('H:i:s',$etime);
                               if($btime==$this->Ctim) $tvar['active']=' selected';
                               else $tvar['active']='';
                               $tvar['value']=$btime;
                               $this->Parts->AddTemplate($this->Item->GetTemplate());
                               $this->Parts->ParseTemplate ($tvar);
                               if($this->Parts->CodeError) {$this->CodeError=$this->Parts->CodeError;return;}
                              }
                    }
           $vvar['parts']=$this->Parts->GetTemplate();
           $this->List->ParseTemplate ($vvar);
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
          }

 function GetIntervalList ()
          {
           $this->CodeError=0;
           return $this->List->GetTemplate();
          }

 function OutIntervalList ()
          {
           $this->CodeError=0;
           $this->List->OutTemplate();
          }

}

?>