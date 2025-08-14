<?php

//////////////////////////////////////////////////////////////////////////
// Script:        vislog.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Visitorslog extends Base
{
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Acc;
// var $CodeError;

 function Visitorslog ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/visitors/'.$name.'.vsl');
          }

 function VisitorslogInit ()
          { global $ENameCoF;
           $this->CodeError=0;
           $this->BaseInit(0);
           if($this->CodeError)
                     {
                      if($this->CodeError!=1) return;
                      $this->SaveRecord($this->Ctim);
                      if($this->CodeError) return;
                     }
          }

 function AddVisitorToLog( $ctime , $visitor )
          { global $ENameCoF;
           $this->CodeError=0;
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;
           if($basetime > $ctime) {$this->CodeError=100;$ENameCoF="class Visitorslog";return;}
           $offset = $ctime - $basetime;
           $this->AddRecord($offset.'|'.$visitor);
          }

 function GetRecordsByTime( $timeB , $timeE )
          { global $ENameCoF;
           $this->CodeError=0;
           $result=array();
           $basetime=$this->GetRecordByID(0);
           if($this->CodeError) return;
           for($i=1;$i<$this->Size;$i++)
                 {
                  $rec=$this->GetRecordByID($i);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  if(($timerec>=$timeB)&&($timerec<=$timeE)) $result[]=$rec;
                 }
           return $result;
          }

 function GetNumberUniqueID( $timeB , $timeE )
          { global $ENameCoF;
           $this->CodeError=0;
           $result=array();
           $basetime=$this->GetRecordByID(0);
           if($this->CodeError) return;
           for($i=1;$i<$this->Size;$i++)
                 {
                  $rec=$this->GetRecordByID($i);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  if(($timerec>=$timeB)&&($timerec<=$timeE)) $result[$mas[1]]=1;
                 }
           return count($result);
          }

 function GetIDbyVisitor( $field )
          { global $ENameCoF;
           $this->CodeError=0;
           for($i=1;$i<$this->Size;$i++)
                 {
                  $rec=$this->GetRecordByID($i);
                  if($this->CodeError) return;
                  $fields = split( "\|", $rec);
                  if(!isset($fields[1])) {$this->CodeError=100;$ENameCoF="class Visitorslog";return;}
                  if(!strcmp($fields[1],$field)) return $i;
                 }
           $this->CodeError=2;
           $ENameCoF="class Visitorslog";
          }

}

?>