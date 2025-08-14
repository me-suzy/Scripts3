<?php

//////////////////////////////////////////////////////////////////////////
// Script:        timelist.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class TimeList
{
 var $CTim;
 var $ActiveDay;
 var $ActiveMonth;
 var $ActiveYear;
 var $List;
 var $Item;
 var $Conf;
 var $Fun;
 var $Dayitems;
 var $Monthitems;
 var $Yearitems;
 var $CodeError;

 function TimeList ( $fun, $conf, $active )
          {
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->CTim=$active;
           $this->Conf = $conf;
           $this->ActiveDay=date( 'j' , $active );
           $this->ActiveMonth=date( 'm' , $active );
           $this->ActiveYear=date( 'Y' , $active );
          }

 function TimeListInit ()
          {
           $this->CodeError=0;
          }

 function CreateTimeList ()
          {
           $this->CodeError=0;
           $this->List= new Template($this->Fun,$this->Conf,'time/time_tpl.php');
           $this->List->TemplateInit();
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
           $vvar['time']=$this->CTim;

           $this->Item= new Template($this->Fun,$this->Conf,'time/tit_tpl.php');
           $this->Item->TemplateInit();
           if($this->Item->CodeError) {$this->CodeError=$this->Item->CodeError;return;}

           $this->Dayitems= new Template($this->Fun,$this->Conf,'');
           for($c=1;$c<=31;$c++)
                 {
                  $tvar['value']=$c;
                  $tvar['name']=$c;
                  if($this->ActiveDay==$c) $tvar['active']=' selected';
                  else $tvar['active']='';
                  $this->Dayitems->AddTemplate($this->Item->GetTemplate());
                  $this->Dayitems->ParseTemplate ($tvar);
                  if($this->Dayitems->CodeError) {$this->CodeError=$this->Dayitems->CodeError;return;}
                 }
           $vvar['dayitems']=$this->Dayitems->GetTemplate();

           $this->Monthitems= new Template($this->Fun,$this->Conf,'');
           for($c=1;$c<=12;$c++)
                 {
                  $tvar['value']=$c;
                  $tvar['name']=date( 'F' , mktime(1,1,1,$c,1,1) );
                  if($this->ActiveMonth==$c) $tvar['active']=' selected';
                  else $tvar['active']='';
                  $this->Monthitems->AddTemplate($this->Item->GetTemplate());
                  $this->Monthitems->ParseTemplate ($tvar);
                  if($this->Monthitems->CodeError) {$this->CodeError=$this->Monthitems->CodeError;return;}
                 }
           $vvar['monthitems']=$this->Monthitems->GetTemplate();

           $this->Yearitems= new Template($this->Fun,$this->Conf,'');
           for($c=2002;$c<=2005;$c++)
                 {
                  $tvar['value']=$c;
                  $tvar['name']=$c;
                  if($this->ActiveYear==$c) $tvar['active']=' selected';
                  else $tvar['active']='';
                  $this->Yearitems->AddTemplate($this->Item->GetTemplate());
                  $this->Yearitems->ParseTemplate ($tvar);
                  if($this->Yearitems->CodeError) {$this->CodeError=$this->Yearitems->CodeError;return;}
                 }
           $vvar['yearitems']=$this->Yearitems->GetTemplate();
           $this->List->ParseTemplate ($vvar);
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
          }

 function GetTimeList ()
          {
           $this->CodeError=0;
           return $this->List->GetTemplate();
          }

 function OutTimeList ()
          {
           $this->CodeError=0;
           $this->List->OutTemplate();
          }

}

?>