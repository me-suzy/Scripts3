<?php

//////////////////////////////////////////////////////////////////////////
// Script:        vdiagram.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Vdiagram
{

 var $Heights;
 var $Color;
 var $Diagram;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Dheight;
 var $Dfill;
 var $Interval;
 var $Ctim;
 var $Column;
 var $Columns;
 var $Ncolumn;
 var $Zcolumn;
 var $Timeitem;
 var $Items;

 function Vdiagram ( $fun, $conf, $ctime,$interval,$values, $color)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Ctim= $ctime;
           $this->Interval= $interval;
           $this->Heights= $values;
           ksort($this->Heights);
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Color=$color;
           $this->Dheight=210;
           $this->Dfill=7/10;
          }

 function VdiagramInit ()
          { global $ENameCoF;
           $this->CodeError=0;
          }

 function CreateDiagram ()
          { global $ENameCoF;
           $this->CodeError=0;
           $mheight=0;
           $total=0;
           reset($this->Heights);
           while($elem=each($this->Heights))
                   {
                    if($elem[1]>$mheight) $mheight=$elem[1];
                    $total+=$elem[1];
                   }

           $this->Diagram= new Template($this->Fun,$this->Conf,'diagram/vd_tpl.php');
           $this->Diagram->TemplateInit();
           if($this->Diagram->CodeError) {$this->CodeError=$this->Diagram->CodeError;return;}

           $this->Column= new Template($this->Fun,$this->Conf,'diagram/vcol_tpl.php');
           $this->Column->TemplateInit();
           if($this->Column->CodeError) {$this->CodeError=$this->Column->CodeError;return;}

           $this->Ncolumn= new Template($this->Fun,$this->Conf,'diagram/vnc_tpl.php');
           $this->Ncolumn->TemplateInit();
           if($this->Ncolumn->CodeError) {$this->CodeError=$this->Ncolumn->CodeError;return;}

           $this->Zcolumn= new Template($this->Fun,$this->Conf,'diagram/vzc_tpl.php');
           $this->Zcolumn->TemplateInit();
           if($this->Zcolumn->CodeError) {$this->CodeError=$this->Zcolumn->CodeError;return;}

           $this->Columns= new Template($this->Fun,$this->Conf,''); //create empty template , no need call TemplateInit()

           $tvar['url']=$this->Conf->FullUrl;
           $tvar['color']=$this->Color;
           if($mheight) $koeff=$this->Dheight*$this->Dfill/$mheight;
           else $koeff=$mheight;

           $tvar['class']='f8';
           $curr=0;
           reset($this->Heights);
           while($elem=each($this->Heights)) {
                if(($curr+2)>count($this->Heights)) break;
                $tvar['num']=$curr;
                if(strcmp($elem[1],"|"))
                       {
                        if($elem[1]>0)
                                 {
                                  $this->Columns->AddTemplate($this->Column->GetTemplate());
                                  $tvar['height']=(int)($elem[1]*$koeff);
                                  $tvar['value']=$elem[1];
                                  $tvar['inttim']=$elem[0];
                                  $this->Columns->ParseTemplate ($tvar);
                                  if($this->Columns->CodeError) {$this->CodeError=$this->Columns->CodeError;return;}
                                 }
                        else     {
                                  $this->Columns->AddTemplate($this->Zcolumn->GetTemplate());
                                  $tvar['inttim']=$elem[0];
                                  $this->Columns->ParseTemplate ($tvar);
                                  if($this->Columns->CodeError) {$this->CodeError=$this->Columns->CodeError;return;}
                                 }
                       }
                else   {
                        $this->Columns->AddTemplate($this->Ncolumn->GetTemplate());
                       }
                $curr++;
           }
           $tvar['columns']=$this->Columns->GetTemplate();

           $this->Timeitem= new Template($this->Fun,$this->Conf,'diagram/vdtm_tpl.php');
           $this->Timeitem->TemplateInit();
           if($this->Timeitem->CodeError) {$this->CodeError=$this->Timeitem->CodeError;return;}

           $tvar['dwidth']=(count($this->Heights)-1)*35;
           if(($this->Interval<4)||($this->Interval==5)) $tvar['twidth']=(count($this->Heights))*35;
           else $tvar['twidth']=(count($this->Heights)-1)*35;

           $this->Items= new Template($this->Fun,$this->Conf,'');
           $darray= split( "\|",$this->Conf->StatIntervals[$this->Interval]);
           $curr=0;
           reset($this->Heights);

           $e=each($this->Heights);
           $begintime=$e[0];

           reset($this->Heights);
           while($elem=each($this->Heights)) {
                if(($curr+2)>count($this->Heights)) break;
                 $this->Items->AddTemplate($this->Timeitem->GetTemplate());
                 $tvar['item']=date($darray[3],$elem[0]);
                 $tvar['fcolor']='#000000';
                 $tvar['fsize']='2';
                 $tvar['class']='f10';
                 if(($tvar['item']==0)&&($this->Interval<4))
                          {
                           $tvar['item']='<b>'.date($darray[4],$elem[0]).'</b>';
                           $tvar['fcolor']='#990000';
                           $tvar['fsize']='3';
                           $tvar['class']='f12';
                          }
                 $tvar['vdtimewidth']='35';
                 $this->Items->ParseTemplate ($tvar);
                 if($this->Items->CodeError) {$this->CodeError=$this->Items->CodeError;return;}
                 $curr++;
           }
           if(($this->Interval<4)||($this->Interval==5))
           {
           $this->Items->AddTemplate($this->Timeitem->GetTemplate());
           if($this->Interval==5) $tvar['item']=date($darray[3],$elem[0]-1);
           else $tvar['item']=date($darray[3],$elem[0]);
           $tvar['fcolor']='#000000';
           $tvar['fsize']='2';
           $tvar['class']='f10';
           if($tvar['item']==0)
                          {
                           $tvar['item']='<b>'.date($darray[4],$elem[0]).'</b>';
                           $tvar['fcolor']='#990000';
                           $tvar['fsize']='3';
                           $tvar['class']='f12';
                          }

           $this->Items->ParseTemplate ($tvar);
           if($this->Items->CodeError) {$this->CodeError=$this->Items->CodeError;return;}
           }
           $tvar['items']=$this->Items->GetTemplate();
           if($this->Interval<3) $tvar['range']=date('j F Y, H:i:s',$begintime).' - '.date('H:i:s',$elem[0]);
           elseif($this->Interval==3) $tvar['range']=date('j F Y',$begintime);
           elseif($this->Interval==4) $tvar['range']=date('j M Y',$begintime).' - '.date('j M Y',$elem[0]-1);
           elseif($this->Interval==5) $tvar['range']=date('F Y',$begintime);
           elseif($this->Interval==6) $tvar['range']=date('Y',$begintime);
           else {$this->CodeError=100;$ENameCoF="class Vdiagram";return;}
           $tvar['class']='f12';

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