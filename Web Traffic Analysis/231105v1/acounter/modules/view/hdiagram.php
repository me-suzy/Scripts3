<?php

//////////////////////////////////////////////////////////////////////////
// Script:        hdiagram.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class Hdiagram
{
 var $Widths;
 var $Color;
 var $Begin;
 var $Diagram;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Dwidth;
 var $Dfill;
 var $MaxLine;
 var $Action;
 var $Row;
 var $Zrow;
 var $Nrow;
 var $Refrow;
 var $Butrow;
 var $Rows;
 var $blag;
 var $HArr;

 function Hdiagram ( $fun, $conf, $action,$beg, $max, $values, $color, $harr)
          {
           $this->CodeError=0;
           if($beg>count($values)) $beg=1;
           $this->Action=$action;
           if((!strcmp($this->Action,'vrefpages'))||(!strcmp($this->Action,'vframes'))) $this->bflag=1;
           elseif(!strcmp($this->Action,'vtop')) $this->bflag=2;
           else $this->bflag=0;
           $this->Begin=$beg;
           $this->Widths= $values;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Color=$color;
           $this->MaxLine=$max;
           $this->Dwidth=210;
           $this->Dfill=7/10;
           $this->HArr=$harr;
          }

 function HdiagramInit ()
          {
           $this->CodeError=0;
          }

 function CreateDiagram ()
          {
           $this->CodeError=0;
           $mwidth=0;
           reset($this->Widths);
           while($elem=each($this->Widths))
                   if($elem[1]>$mwidth) $mwidth=$elem[1];

           $this->Diagram= new Template($this->Fun,$this->Conf,'diagram/hd_tpl.php');
           $this->Diagram->TemplateInit();
           if($this->Diagram->CodeError) {$this->CodeError=$this->Diagram->CodeError;return;}

           $this->Row= new Template($this->Fun,$this->Conf,'diagram/hdrw_tpl.php');
           $this->Row->TemplateInit();
           if($this->Row->CodeError) {$this->CodeError=$this->Row->CodeError;return;}

           $this->Zrow= new Template($this->Fun,$this->Conf,'diagram/hdzr_tpl.php');
           $this->Zrow->TemplateInit();
           if($this->Zrow->CodeError) {$this->CodeError=$this->Zrow->CodeError;return;}

           $this->Nrow= new Template($this->Fun,$this->Conf,'diagram/hdnr_tpl.php');
           $this->Nrow->TemplateInit();
           if($this->Nrow->CodeError) {$this->CodeError=$this->Nrow->CodeError;return;}

           $this->Refrow= new Template($this->Fun,$this->Conf,'diagram/hdrf_tpl.php');
           $this->Refrow->TemplateInit();
           if($this->Refrow->CodeError) {$this->CodeError=$this->Refrow->CodeError;return;}

           $this->Butrow= new Template($this->Fun,$this->Conf,'diagram/hdk_tpl.php');
           $this->Butrow->TemplateInit();
           if($this->Butrow->CodeError) {$this->CodeError=$this->Butrow->CodeError;return;}

           $blank= new Template($this->Fun,$this->Conf,'diagram/hdbl_tpl.php');
           $blank->TemplateInit();
           if($blank->CodeError) {$this->CodeError=$blank->CodeError;return;}

           $this->Rows= new Template($this->Fun,$this->Conf,'');  //create empty template , no need call TemplateInit()

           $tvar['url']=$this->Conf->FullUrl;
           $tvar['color']=$this->Color;
           if($mwidth) $koeff=$this->Dwidth*$this->Dfill/$mwidth;
           else $koeff=$mwidth;

           $tvar['hname']=$this->HArr[0];
           $tvar['hvalue']=$this->HArr[1];
           $tvar['class']='f12';
           if(!isset($this->Widths['No records']))
           {
           reset($this->Widths);
           $curr=0;
           $total=0;
           while($elem=each($this->Widths)) {
                $curr++;
                if($curr>=$this->Begin+$this->MaxLine) {$curr--;break;}
                if($curr<$this->Begin) continue;
                if(strcmp($elem[1],"|"))
                       {
                        if($elem[1]>0)
                                 {
                                  $total+=$elem[1];
                                  $this->Rows->AddTemplate($this->Row->GetTemplate());
                                  $tvar['width']=(int)($elem[1]*$koeff);
                                  $tvar['name']="&nbsp;&nbsp;".$elem[0];
                                  $tvar['value']=$elem[1];
                                  $tvar['num']=$curr;
                                  if($this->bflag==1)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                                   {
                                                    $tvar['name']=$this->Refrow->GetTemplate();
                                                    $this->Rows->ParseTemplate ($tvar);
                                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                                    $tvar['ref']=$elem[0];
                                                   }
                                              }
                                  elseif($this->bflag==2)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                                   {
                                                    $tvar['name']=$this->Butrow->GetTemplate();
                                                    $this->Rows->ParseTemplate ($tvar);
                                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                                    $tvar['val2']=$elem[0];
                                                   }
                                              }
                                  $this->Rows->ParseTemplate ($tvar);
                                  if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                 }
                        else     {
                                  $this->Rows->AddTemplate($this->Zrow->GetTemplate());
                                  $tvar['name']="&nbsp;&nbsp;".$elem[0];
                                  $tvar['num']=$curr;
                                  if($this->bflag==1)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                               {
                                               $tvar['name']=$this->Refrow->GetTemplate();
                                               $this->Rows->ParseTemplate ($tvar);
                                               if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                               $tvar['ref']=$elem[0];
                                               }
                                              }
                                  elseif($this->bflag==2)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                                   {
                                                    $tvar['name']=$this->Butrow->GetTemplate();
                                                    $this->Rows->ParseTemplate ($tvar);
                                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                                    $tvar['val2']=$elem[0];
                                                   }
                                              }
                                  $this->Rows->ParseTemplate ($tvar);
                                  if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                 }
                       }
                else   {
                                  $this->Rows->AddTemplate($this->Nrow->GetTemplate());
                                  $tvar['name']="&nbsp;&nbsp;".$elem[0];
                                  $tvar['num']=$curr;
                                  if($this->bflag==1)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                               {
                                               $tvar['name']=$this->Refrow->GetTemplate();
                                               $this->Rows->ParseTemplate ($tvar);
                                               if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                               $tvar['ref']=$elem[0];
                                               }
                                              }
                                  elseif($this->bflag==2)
                                              {
                                               if(strcmp($elem[0],'undefined'))
                                                   {
                                                    $tvar['name']=$this->Butrow->GetTemplate();
                                                    $this->Rows->ParseTemplate ($tvar);
                                                    if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                                                    $tvar['val2']=$elem[0];
                                                   }
                                              }
                                  $this->Rows->ParseTemplate ($tvar);
                                  if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                       }
           }
           $tvar['total']=$total;

           $tvar['beginnum']=$this->Begin;
           $tvar['maxnum']=count($this->Widths);

           $tvar['begnum']=$this->Begin.'-';
           $tvar['endnum']=$curr.'&nbsp;&nbsp;out of&nbsp;&nbsp;';
           }
           else {
                 $this->Rows->AddTemplate($blank->GetTemplate());
                 $this->Rows->ParseTemplate ($tvar);
                 if($this->Rows->CodeError) {$this->CodeError=$this->Rows->CodeError;return;}
                 $curr=1; $total=1;
                 $tvar['total']=0;

                    $tvar['beginnum']='';
                    $tvar['maxnum']='';

                    $tvar['begnum']='';
                    $tvar['endnum']='';
                }
                  $tvar['rows']=$this->Rows->GetTemplate();

           $this->Diagram->ParseTemplate ($tvar);
           if($this->Diagram->CodeError) {$this->CodeError=$this->Diagram->CodeError;return;}
          }

 function GetDiagram ()
          {
           $this->CodeError=0;
           return $this->Diagram->GetTemplate();
          }

 function OutDiagram ()
          {
           $this->CodeError=0;
           $this->Diagram->OutTemplate();
          }

}

?>