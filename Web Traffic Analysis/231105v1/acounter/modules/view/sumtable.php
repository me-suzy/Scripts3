<?php

//////////////////////////////////////////////////////////////////////////
// Script:        sumtable.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class SumTable
{
 var $Table;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Data;
 var $BeginTime;
 var $Interval;
 var $Dark;
 var $Rows;

 function SumTable( $fun,$conf,$bt,$interval,$m)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Data=$m;
           $this->BeginTime= $bt;
           $this->Interval= $interval;
          }

 function SumTableInit ()
          { global $ENameCoF;
           $this->CodeError=0;
          }

 function Add($x1,$x2)
          {
           $this->CodeError=0;
           $res[2]=$x2+$x1;
           if(!strcmp($x1,"|")) { $res[0]='-'; $res[2]=$x2; }
            else $res[0]=$x1;
           if(!strcmp($x2,"|")) { $res[1]='-'; $res[2]=$x1; }
            else $res[1]=$x2;
           return $res;
          }

 function CreateSumTable ()
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Table= new Template($this->Fun,$this->Conf,'table/sumh_tpl.php');
           $this->Table->TemplateInit();
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}

           $this->Dark= new Template($this->Fun,$this->Conf,'table/sums_tpl.php');
           $this->Dark->TemplateInit();
           if($this->Dark->CodeError) {$this->CodeError=$this->Dark->CodeError;return;}

           $this->Rows= new Template($this->Fun,$this->Conf,'');  //create empty template , no need call TemplateInit()

           $tvar['class']='f12';
           $sum['Visitors']=0;
           $sum['Hosts']=0;
           $sum['Reloads']=0;
           $sum['Hits']=0;
           $curr=0;
           reset($this->Data['Visitors']);
           $e=each($this->Data['Visitors']);
           $begintime=$e[0];

           $elem=each($this->Data['Visitors']);
           while($elem)
           {
                        if($this->Action=='vtop') {if(($curr+1)>count($this->Data['Visitors'])) break;}
                        elseif(($curr+2)>count($this->Data['Visitors'])) break;

                        $e=each($this->Data['Visitors']);
                        $tkey=$e[0];
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        if($this->Interval<4) $tvar['vals2']=date('H:i:s',$elem[0]).' - '.date('H:i:s',$tkey);
                        elseif($this->Interval==4) $tvar['vals2']=date('j F Y',$elem[0]);
                        elseif($this->Interval==5) $tvar['vals2']=date('j F Y',$elem[0]).' - '.date('j F Y',$tkey-1);
                        elseif($this->Interval==6) $tvar['vals2']=date('F Y',$elem[0]);
                        else {$this->CodeError=100;$ENameCoF="SumTable";return;}
                        $rrr=$this->Add($elem[1],0);
                        $tvar['vals3']=$rrr[0];
                        $rrr=$this->Add($this->Data['Hosts'][$elem[0]],0);
                        $tvar['vals4']=$rrr[0];
                        $rrr=$this->Add($this->Data['Reloads'][$elem[0]],0);
                        $tvar['vals5']=$rrr[0];
                        $rrr=$this->Add($this->Data['Hits'][$elem[0]],0);
                        $tvar['vals6']=$rrr[0];
                        $tvar['num']=$curr;
                        $tvar['inttim']=$elem[0];

                        $rrr=$this->Add($sum['Visitors'],$elem[1]);
                        $sum['Visitors']=$rrr[2];
                        $rrr=$this->Add($sum['Hosts'],$this->Data['Hosts'][$elem[0]]);
                        $sum['Hosts']=$rrr[2];
                        $rrr=$this->Add($sum['Reloads'],$this->Data['Reloads'][$elem[0]]);
                        $sum['Reloads']=$rrr[2];
                        $rrr=$this->Add($sum['Hits'],$this->Data['Hits'][$elem[0]]);
                        $sum['Hits']=$rrr[2];

                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                        $curr++;
                        $elem=$e;
           }
           $tvar['rows']=$this->Rows->GetTemplate();
           if($this->Interval<3) $tvar['range']=date('j F Y, H:i:s',$begintime).' - '.date('H:i:s',$elem[0]);
           elseif($this->Interval==3) $tvar['range']=date('j F Y',$begintime);
           elseif($this->Interval==4) $tvar['range']=date('j F Y',$begintime).' - '.date('j F Y',$elem[0]-1);
           elseif($this->Interval==5) $tvar['range']=date('F Y',$begintime);
           elseif($this->Interval==6) $tvar['range']=date('Y',$begintime);
           else {$this->CodeError=100;$ENameCoF="class SumTable";return;}

                        $tvar['valh3']='Visitors';
                        $tvar['valh4']='Hosts';
                        $tvar['valh5']='Reloads';
                        $tvar['valh6']='Hits';
                        $rrr=$this->Add($sum['Visitors'],0);
                        $tvar['vale3']=$rrr[0];
                        $rrr=$this->Add($sum['Hosts'],0);
                        $tvar['vale4']=$rrr[0];
                        $rrr=$this->Add($sum['Reloads'],0);
                        $tvar['vale5']=$rrr[0];
                        $rrr=$this->Add($sum['Hits'],0);
                        $tvar['vale6']=$rrr[0];

           $tvar['url']=$this->Conf->FullUrl;
           $this->Table->ParseTemplate ($tvar);
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}
          }

 function GetSumTable ()
          {
           $this->CodeError=0;
           return $this->Table->GetTemplate();
          }

 function OutSumTable ()
          {
           $this->CodeError=0;
           $this->Table->OutTemplate();
          }

}


?>