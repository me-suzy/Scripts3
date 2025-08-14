<?php

//////////////////////////////////////////////////////////////////////////
// Script:        stattab.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class StatTable
{
 var $Table;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Data;
 var $DataPer;
 var $Begin;
 var $MaxLine;
 var $NameFunc;
 var $Dark;
 var $Refrow;
 var $Butrow;
 var $Rows;
 var $PerArraysum;
 var $HArr;

 function StatTable( $fun,$conf,$namef,$beg,$max,$m,$sss,$harr)
          {
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           if($beg>count($m)) $beg=1;
           $this->Begin=$beg;
           $this->MaxLine=$max;
           $this->PerArraysum = $sss;
           if((!strcmp($namef,'vrefpages'))||(!strcmp($namef,'vframes'))) $this->NameFunc=1;
           elseif(!strcmp($namef,'vtop')) $this->NameFunc=2;
           else $this->NameFunc=0;
           if(!strcmp(gettype($m),'array'))
              {
               $this->Data= $m;
               $this->DataPer=$m;
               if($this->PerArraysum)
                   {
                    reset($this->Data);
                    while($el=each($this->Data))
                           if(strcmp($el[1],'|'))$this->DataPer[$el[0]]=sprintf("%.2f",$el[1]/$this->PerArraysum*100);
                   }
              }
            else
                {
                 $this->Data= '|';
                 $this->DataPer= '|';
                }
           $this->HArr=$harr;
          }

 function StatTableInit ()
          {
           $this->CodeError=0;
          }

 function CreateStatTable ()
          {
           $this->Table= new Template($this->Fun,$this->Conf,'table/sth_tpl.php');
           $this->Table->TemplateInit();
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}

           $this->Dark= new Template($this->Fun,$this->Conf,'table/sts_tpl.php');
           $this->Dark->TemplateInit();
           if($this->Dark->CodeError) {$this->CodeError=$this->Dark->CodeError;return;}

           $this->Refrow= new Template($this->Fun,$this->Conf,'table/str_tpl.php');
           $this->Refrow->TemplateInit();
           if($this->Refrow->CodeError) {$this->CodeError=$this->Refrow->CodeError;return;}

           $this->Butrow= new Template($this->Fun,$this->Conf,'table/stk_tpl.php');
           $this->Butrow->TemplateInit();
           if($this->Butrow->CodeError) {$this->CodeError=$this->Butrow->CodeError;return;}

           $blank= new Template($this->Fun,$this->Conf,'table/stb_tpl.php');
           $blank->TemplateInit();
           if($blank->CodeError) {$this->CodeError=$blank->CodeError;return;}

           $this->Rows= new Template($this->Fun,$this->Conf,'');  //create empty template , no need call TemplateInit()

           $i=$this->Begin;
           $sum=0;
           $sumper=0;
           $curr=0;
           $tvar['class']='f12';
           $tvar['hname']=$this->HArr[0];
           $tvar['hvalue']=$this->HArr[1];
           if(!isset($this->Data['No records']))
           {
           reset($this->Data);
           while($elem=each($this->Data))
           {
                $curr++;
                if($curr>=$this->Begin+$this->MaxLine) {$curr--;break;}
                if($curr<$this->Begin) continue;
                if(strcmp($elem[1],"|"))
                       {
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        $tvar['val1']=$i;
                        $tvar['val2']='&nbsp;&nbsp;'.$elem[0];
                        $tvar['val3']=$elem[1];
                        $tvar['val4']=sprintf("%.2f",$this->DataPer[$elem[0]]).' %';
                        $tvar['num']=$curr;
                        if($this->NameFunc==1)
                             {
                              if(strcmp($elem[0],'undefined'))
                                   {
                                    $tvar["val2"]=$this->Refrow->GetTemplate();
                                    $this->Rows->ParseTemplate ($tvar);
                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                    $tvar["val2"]=$elem[0];
                                   }
                             }
                        elseif($this->NameFunc==2)
                             {
                              if(strcmp($elem[0],'undefined'))
                                   {
                                    $tvar["val2"]=$this->Butrow->GetTemplate();
                                    $this->Rows->ParseTemplate ($tvar);
                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                    $tvar["val2"]=$elem[0];
                                   }
                             }
                        $sum+=$elem[1];
                        $sumper+=$this->DataPer[$elem[0]];
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
                else   {
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        $tvar["val1"]=$i;
                        $tvar["val2"]='&nbsp;&nbsp;'.$elem[0];
                        $tvar["val3"]='-';
                        $tvar["val4"]='-';
                        $tvar["num"]=$curr;
                        if($this->NameFunc==1)
                             {
                              if(strcmp($elem[0],"undefined"))
                                   {
                                    $tvar["val2"]=$this->Refrow->GetTemplate();
                                    $this->Rows->ParseTemplate ($tvar);
                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                    $tvar["val2"]=$elem[0];
                                   }
                             }
                        elseif($this->NameFunc==2)
                             {
                              if(strcmp($elem[0],'undefined'))
                                   {
                                    $tvar["val2"]=$this->Butrow->GetTemplate();
                                    $this->Rows->ParseTemplate ($tvar);
                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                    $tvar["val2"]=$elem[0];
                                   }
                             }
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
                $i++;
           }
           $tvar["range"]=$this->Begin.' - '.($i-1).'&nbsp;&nbsp;out of&nbsp;&nbsp;';
           $tvar["maxnum"]=count($this->Data);
           }
           else {
                 $this->Rows->AddTemplate($blank->GetTemplate());
                 $this->Rows->ParseTemplate ($tvar);
                 if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                 $i=2;
                 $tvar["range"]='';
                 $tvar["maxnum"]='';
                }
           $tvar["rows"]=$this->Rows->GetTemplate();
           $tvar["val2"]="Total";
           $tvar["val3"]=$sum;
           if((count($this->Data)<=$this->MaxLine)&&($sumper!=0)) $sumper=100;
           $tvar["val4"]=sprintf("%.2f",$sumper)." %";
           $tvar["url"]=$this->Conf->FullUrl;
           $tvar["beginnum"]=$this->Begin;

           $this->Table->ParseTemplate ($tvar);
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}
          }

 function GetStatTable ()
          {
           $this->CodeError=0;
           return $this->Table->GetTemplate();
          }

 function OutStatTable ()
          {
           $this->CodeError=0;
           $this->Table->OutTemplate();
          }

}

?>