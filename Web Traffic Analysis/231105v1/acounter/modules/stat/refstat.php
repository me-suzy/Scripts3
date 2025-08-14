<?php

//////////////////////////////////////////////////////////////////////////
// Script:        refstat.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class ReferersStat extends Base
{

 var $Acc;                     // Account
 var $Ctim;                    // Begin time in seconds
 var $Per;                     // Period in seconds
 var $Rbase;                   // Object ReferersBase
 var $NumPer;                  // The number of periods
 var $Conf;
 var $Fun;

 function ReferersStat ( $fun, $conf, $account , $ctime ,$period,$numP)
          {
           $this->CodeError=0;
           $this->Acc=$account;
           $this->Ctim=$ctime;
           $this->Per=$period;
           $this->NumPer=$numP;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/referers/'.$name.'.rfl');
           $this->Rbase=new Referersbase( $fun, $conf, $account,$ctime);
          }

 function ReferersStatInit ()
          {
           $this->CodeError=0;
           $this->BaseInit(0);
           if($this->CodeError) return;
           $this->Rbase->ReferersbaseInit();
           if($this->Rbase->CodeError) {$this->CodeError=$this->Rbase->CodeError;return;}
          }

 function VerifyRangeOfTime($ctime,$timeB,$timeE,$period,$numP,&$numPB,&$numPE)
          {
           $this->CodeError=0;
           // Verify of end time
           if($timeE>$ctime)
                $numPE=(int)(($ctime-$timeB)/$period);
           else $numPE=$numP;
           // Verify of begin time
           $Total = new Base($this->Fun, $this->Conf, 'accounts/'.$this->Acc.'/total.cnt');
           $Total->BaseInit(0);
           if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
           $begintime=$Total->GetRecordByID(0);
           if($Total->CodeError) {$this->CodeError=$Total->CodeError;return;}
           if($timeB<$begintime)
                $numPB=(int)(($begintime-$timeB)/$period);
           else $numPB=0;
          }

 function GetReferers(&$StatSum,&$result)
          {
           $this->CodeError=0;
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;

           $timeB=$this->Ctim;
           $period=$this->Per;
           $numP=$this->NumPer;
           $timeE=$timeB+$period*$numP-1;

           $this->VerifyRangeOfTime(time(),$timeB,$timeE,$period,$numP,$numPB,$numPE);
           if($this->CodeError) return;

           $StatSum=0;
           $Lines=0;
           for($idrec=1;$idrec<$this->Size;$idrec++)
                 {
                  $rec=$this->GetRecordByID($idrec);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  $ref=$this->Rbase->GetRefererByID($mas[2]);
                  if($this->Rbase->CodeError) {$this->CodeError=$this->Rbase->CodeError;return;}
                  if(($timerec>=$timeB)&&($timerec<=$timeE))
                       {
                        if(!isset($result[$ref])){$result[$ref]=0;}
                        $result[$ref]++;
                        $StatSum++;
                       }
                 }
           if(!count($result))
           for($i=0;$i<$numP;$i++)
                {
                 if(($i<$numPB)||($i>$numPE))$result[$i]='|';
                 if(!isset($result[$i]))$result[$i]=0;
                }
          }

 function GetByReferer($refer,&$StatSum)
          {
           $this->CodeError=0;
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;

           $timeB=$this->Ctim;
           $period=$this->Per;
           $numP=$this->NumPer;
           $timeE=$timeB+$period*$numP-1;

           $this->VerifyRangeOfTime(time(),$timeB,$timeE,$period,$numP,$numPB,$numPE);
           if($this->CodeError) return;

           $StatSum=0;
           $result=array();
           for($idrec=1;$idrec<$this->Size;$idrec++)
                 {
                  $rec=$this->GetRecordByID($idrec);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  $ref=$this->Rbase->GetRefererByID($mas[2]);
                  if($this->Rbase->CodeError) {$this->CodeError=$this->Rbase->CodeError;return;}
                  if(!strcmp($ref,$refer))
                  if(($timerec>=$timeB)&&($timerec<=$timeE))
                       {
                        $i=(int)(($timerec-$timeB)/$period);
                        if(!isset($result[$i]))$result[$i]=0;
                        $result[$i]++;
                        $StatSum++;
                       }
                 }
           for($i=0;$i<$numP;$i++)
                {
                 if(($i<$numPB)||($i>$numPE))$result[$i]='|';
                 if(!isset($result[$i]))$result[$i]=0;
                }
           return $result;
          }



}

?>