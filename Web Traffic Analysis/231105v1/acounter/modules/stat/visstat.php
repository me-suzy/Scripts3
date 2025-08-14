<?php

//////////////////////////////////////////////////////////////////////////
// Script:        visstat.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class VisitorsStat
{

 var $Acc;                     // Account
 var $Ctim;                    // Begin time in seconds
 var $Per;                     // Period in seconds
 var $NumPer;                  // The number of periods
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Visitor;
 var $Unique;
 var $Begintime;


 function VisitorsStat ( $fun, $conf, $account , $ctime ,$period,$numP)
          {
           $this->CodeError=0;
           $this->Acc=$account;
           $this->Ctim=$ctime;
           $this->Per=$period;
           $this->NumPer=$numP;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $name=date( 'zY' , $ctime );
           $this->Visitor= new Base( $fun, $conf, 'accounts/'.$account.'/daily/visitors/'.$name.'.vsl');
           $this->Unique= new Base( $fun, $conf, 'accounts/'.$account.'/daily/visitors/'.$name.'.uql');
          }

 function VisitorsStatInit ()
          {
           $this->CodeError=0;
           $this->Visitor->BaseInit(0);
           if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
           $this->Unique->BaseInit(0);
           if($this->Unique->CodeError) {$this->CodeError=$this->Unique->CodeError;return;}

           $Total = new Base($this->Fun, $this->Conf, 'accounts/'.$this->Acc.'/total.cnt');
           $Total->BaseInit(0);
           if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
           $this->Begintime=$Total->GetRecordByID(0);
           if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
          }

 function VerifyRangeOfTime($ctime,$timeB,$timeE,$period,$numP,&$numPB,&$numPE)
          {
           $this->CodeError=0;
           // Verify of end time
           if($timeE>$ctime)
                $numPE=(int)(($ctime-$timeB)/$period);
           else $numPE=$numP;
           // Verify of begin time
           if($timeB<$this->Begintime)
                $numPB=(int)(($this->Begintime-$timeB)/$period);
           else $numPB=0;
          }

 function GetLoadings(&$PartSum,&$result)
          {
           $this->CodeError=0;
           $basetime = $this->Visitor->GetRecordByID(0);
           if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}

           $ctime=time();
           $PartSum=0;
           reset($result);
           $e=each($result);
           $timeB=$e[0];
           for($idrec=1;$idrec<$this->Visitor->Size;$idrec++)
                 {
                  $rec=$this->Visitor->GetRecordByID($idrec);
                  if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  if($timerec>=$timeB) break;
                 }
           while($e=each($result))
                  {
                   $timeE=$e[0];
                   if($timeE<$this->Begintime) {$result[$timeB]='|';}
                   elseif($timeB>$ctime) {$result[$timeB]='|';}
                   else
                   {
                   for(;$idrec<$this->Visitor->Size;$idrec++)
                         {
                          $rec=$this->Visitor->GetRecordByID($idrec);
                          if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                          $mas=split("\|",$rec);
                          $timerec=$mas[0]+$basetime;
                          if($timerec<$timeE) {$result[$timeB]++;$PartSum++;}
                          else break;
                         }
                   }
                   $timeB=$timeE;
                  }
          }

 function GetUniqueLoadings(&$PartSum,&$result)
          {
           $this->CodeError=0;
           $basetime = $this->Unique->GetRecordByID(0);
           if($this->Unique->CodeError) {$this->CodeError=$this->Unique->CodeError;return;}

           $ctime=time();
           $PartSum=0;
           reset($result);
           $e=each($result);
           $timeB=$e[0];
           for($idrec=1;$idrec<$this->Unique->Size;$idrec++)
                 {
                  $rec=$this->Unique->GetRecordByID($idrec);
                  if($this->Unique->CodeError) {$this->CodeError=$this->Unique->CodeError;return;}
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  if($timerec>=$timeB) break;
                 }
           while($e=each($result))
                  {
                   $timeE=$e[0];
                   if($timeE<$this->Begintime) {$result[$timeB]='|';}
                   elseif($timeB>$ctime) {$result[$timeB]='|';}
                   else
                   {
                   for(;$idrec<$this->Unique->Size;$idrec++)
                         {
                          $rec=$this->Unique->GetRecordByID($idrec);
                          if($this->Unique->CodeError) {$this->CodeError=$this->Unique->CodeError;return;}
                          $mas=split("\|",$rec);
                          $timerec=$mas[0]+$basetime;
                          if($timerec<$timeE) {$result[$timeB]++;$PartSum++;}
                          else break;
                         }
                   }
                   $timeB=$timeE;
                  }
          }

 function GetReturns(&$StatSum,&$result)
          {
           $this->CodeError=0;
           $basetime = $this->Visitor->GetRecordByID(0);
           if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}

           $timeB=$this->Ctim;
           $period=$this->Per;
           $numP=$this->NumPer;
           $timeE=$timeB+$period*$numP-1;

           for($i=0;$i<count($this->Conf->ReturnIntervals);$i++) $result[$this->Conf->NameReturnIntervals[$i]]=0;

           $StatSum=0;
           for($idrec=1;$idrec<$this->Visitor->Size;$idrec++)
                 {
                  $rec=$this->Visitor->GetRecordByID($idrec);
                  if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  if(($timerec<$timeB)||($timerec>$timeE)) continue;
                  $rec=$this->Unique->GetRecordByID($mas[1]);
                  if($this->Unique->CodeError) {$this->CodeError=$this->Unique->CodeError;return;}
                  $mas=split("\|",$rec);
                  $timeu=$basetime+$mas[0];
                  if($timerec==$timeu) continue;
//                  if(($timerec>=$timeB)&&($timerec<=$timeE))
//                       {
                              $inter=$timerec-$timeu;
                              if($inter<=$this->Conf->ReturnIntervals[0])$i=0;
                               else
                                   for($i=1;$i<count($this->Conf->ReturnIntervals);$i++)
                                         if(($inter>$this->Conf->ReturnIntervals[$i-1])&&($inter<=$this->Conf->ReturnIntervals[$i]))
                                              break;
                              $keyR=$this->Conf->NameReturnIntervals[$i];
                              if(!isset($result[$keyR]))$result[$keyR]=0;
                              $result[$keyR]++;
                              $StatSum++;
//                       }
                 }
           $begday=getdate(time());
           $begday=mktime(0,0,0,$begday['mon'],$begday['mday'],$begday['year']);
           $per=time()-$begday;
           $sum=0;
           for($i=0;$i<count($this->Conf->ReturnIntervals);$i++)
                 {
                  if($per<$sum) break;
                  $sum=$this->Conf->ReturnIntervals[$i];
                 }
           for(;$i<count($this->Conf->ReturnIntervals);$i++)
                 {
                  $keyR=$this->Conf->NameReturnIntervals[$i];
                  $result[$keyR]='|';
                 }
          }

}

?>