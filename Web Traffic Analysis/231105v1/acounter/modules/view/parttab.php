<?php

//////////////////////////////////////////////////////////////////////////
// Script:        parttab.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class PartTable
{
 var $Table;
 var $CodeError;
 var $Fun;
 var $Conf;
 var $Data;
 var $DataPer;
 var $BeginTime;
 var $Interval;
 var $Dark;
 var $Rows;
 var $PerArraysum;

 function PartTable( $fun,$conf,$bt,$interval,$m,$sss)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Data= $m;
           $this->DataPer= $m;
           $this->PerArraysum = $sss;

               if($this->PerArraysum)
                   {
                    reset($this->Data);
                    while($el=each($this->Data))
                           if(strcmp($el[1],'|'))$this->DataPer[$el[0]]=sprintf("%.2f",$el[1]/$this->PerArraysum*100);
                   }

           $this->BeginTime= $bt;
           $this->Interval= $interval;
          }

 function PartTableInit ()
          { global $ENameCoF;
           $this->CodeError=0;
          }

 function CreatePartTable ()
          { global $ENameCoF;
           $this->Table= new Template($this->Fun,$this->Conf,'table/pth_tpl.php');
           $this->Table->TemplateInit();
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}

           $this->Dark= new Template($this->Fun,$this->Conf,'table/pts_tpl.php');
           $this->Dark->TemplateInit();
           if($this->Dark->CodeError) {$this->CodeError=$this->Dark->CodeError;return;}

           $this->Rows= new Template($this->Fun,$this->Conf,'');  //create empty template , no need call TemplateInit()

           $sum=0;
           $sumper=0;
           $tvar['class']='f12';
           $curr=0;
           reset($this->Data);
           $e=each($this->Data);
           $begintime=$e[0];

           $elem=each($this->Data);
           while($elem)
           {
                if(($curr+2)>count($this->Data)) break;
                $e=each($this->Data);
                $tkey=$e[0];
                if($this->Interval<4) $tvar['val2']=date('H:i:s',$elem[0]).' - '.date('H:i:s',$tkey);
                elseif($this->Interval==4) $tvar['val2']=date('j F Y',$elem[0]);
                elseif($this->Interval==5) $tvar['val2']=date('j F Y',$elem[0]).' - '.date('j F Y',$tkey-1);
                elseif($this->Interval==6) $tvar['val2']=date('F Y',$elem[0]);
                else {$this->CodeError=100;$ENameCoF="class PartTable";return;}
                $tvar['num']=$curr;
                if(strcmp($elem[1],"|"))
                       {
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        $tvar['val3']=$elem[1];
                        $tvar['val4']=sprintf("%.2f",$this->DataPer[$elem[0]]).' %';
                        $tvar['inttim']=$elem[0];
                        $sum+=$elem[1];
                        $sumper+=$this->DataPer[$elem[0]];
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
                else   {
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        $tvar['val3']='-';
                        $tvar['val4']='-';
                        $tvar['inttim']=$elem[0];
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
                $curr++;
                $elem=$e;
           }
           $tvar['rows']=$this->Rows->GetTemplate();

           if($this->Interval<3) $tvar['range']=date('j F Y, H:i:s',$begintime).' - '.date('H:i:s',$elem[0]);
           elseif($this->Interval==3) $tvar['range']=date('j F Y',$begintime);
           elseif($this->Interval==4) $tvar['range']=date('j F Y',$begintime).' - '.date('j F Y',$elem[0]-1);
           elseif($this->Interval==5) $tvar['range']=date('F Y',$begintime);
           elseif($this->Interval==6) $tvar['range']=date('Y',$begintime);
           else {$this->CodeError=100;$ENameCoF="class PartTable";return;}

           $tvar['val2']='Total';
           $tvar['val3']=$sum;
           $tvar['val4']=sprintf("%.2f",$sumper).' %';
           $tvar['url']=$this->Conf->FullUrl;
           $this->Table->ParseTemplate ($tvar);
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}
          }

 function GetPartTable ()
          {
           $this->CodeError=0;
           return $this->Table->GetTemplate();
          }

 function OutPartTable ()
          {
           $this->CodeError=0;
           $this->Table->OutTemplate();
          }

}


?>