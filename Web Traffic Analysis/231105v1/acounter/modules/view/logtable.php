<?php

//////////////////////////////////////////////////////////////////////////
// Script:        logtable.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class LogTable
{
 var $Table;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Data;
 var $DataPer;
 var $Begin;
 var $Dark;
 var $Rows;
 var $Max;

 function LogTable( $fun,$conf,$beg,$m,$max)
          {
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Begin=$beg;
           $this->Max=$max;
           if(is_array($m))
              {
               $this->Data= $m;
              }
            else
                {
                 $this->Data['-']= '|';
                }
          }

 function LogTableInit ()
          {
           $this->CodeError=0;
          }

 function CreateLogTable ()
          {
           $this->Table= new Template($this->Fun,$this->Conf,'table/lth_tpl.php');
           $this->Table->TemplateInit();
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}

           $this->Dark= new Template($this->Fun,$this->Conf,'table/lts_tpl.php');
           $this->Dark->TemplateInit();
           if($this->Dark->CodeError) {$this->CodeError=$this->Dark->CodeError;return;}

           $this->Tbox= new Template($this->Fun,$this->Conf,'table/ltr_tpl.php');
           $this->Tbox->TemplateInit();
           if($this->Tbox->CodeError) {$this->CodeError=$this->Tbox->CodeError;return;}

           $blank= new Template($this->Fun,$this->Conf,'table/ltb_tpl.php');
           $blank->TemplateInit();
           if($blank->CodeError) {$this->CodeError=$blank->CodeError;return;}

           $this->Rows= new Template($this->Fun,$this->Conf,'');

           $curr=0;
           $sum=0;
           $sumper=0;
           $tvar['class']='f12';
           if(!isset($this->Data['No records']))
           {
           reset($this->Data);
           while($elem=each($this->Data))
                       {
                        $curr++;
                        if($curr>=$this->Begin+$this->Max) {$curr--;break;}
                        if($curr<$this->Begin) continue;
                        $this->Rows->AddTemplate($this->Dark->GetTemplate());
                        $tvar['num']=$curr;
                        $tvar['val1']=$elem[1]['time'];
                        $tvar['val2']=$elem[1]['ip'];
                        $tvar['val3']=$elem[1]['lang'];
                        if(strcmp($elem[1]['ref'],'undefined'))
                                    {
                                     $tvar["val4"]=$this->Tbox->GetTemplate();
                                     $this->Rows->ParseTemplate ($tvar);
                                     if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                     $tvar["val4"]=$elem[1]['ref'];
                                    }
                        else $tvar["val4"]='&nbsp;&nbsp;'.$elem[1]['ref'];
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
           $tvar["range"]=$this->Begin.' - '.($curr).'&nbsp;&nbsp;out of&nbsp;&nbsp;';
           $tvar["maxnum"]=count($this->Data);
           }
           else
                       {
                        $this->Rows->AddTemplate($blank->GetTemplate());
                        $this->Rows->ParseTemplate ($tvar);
                        if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                        $curr=1;
                        $tvar["range"]='';
                        $tvar["maxnum"]='';
                       }
           $tvar["rows"]=$this->Rows->GetTemplate();
           $tvar["url"]=$this->Conf->FullUrl;
           $tvar["beginnum"]=$this->Begin;

           $this->Table->ParseTemplate ($tvar);
           if($this->Table->CodeError) {$this->CodeError=$this->Table->CodeError;return;}
          }

 function GetLogTable ()
          {
           $this->CodeError=0;
           return $this->Table->GetTemplate();
          }

 function OutLogTable ()
          {
           $this->CodeError=0;
           $this->Table->OutTemplate();
          }

}

?>