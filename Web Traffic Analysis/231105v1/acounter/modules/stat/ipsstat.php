<?php

//////////////////////////////////////////////////////////////////////////
// Script:        ipsstat.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class IPsStat extends Base
{

 var $Acc;                     // Account
 var $Ctim;                    // Begin time in seconds
 var $Per;                     // Period in seconds
 var $IPbase;                   // Object IPsBase
 var $NumPer;                  // The number of periods
 var $Conf;
 var $Fun;

 function IPsStat ( $fun, $conf, $account , $ctime ,$period,$numP)
          {
           $this->CodeError=0;
           $this->Acc=$account;
           $this->Ctim=$ctime;
           $this->Per=$period;
           $this->NumPer=$numP;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/ips/'.$name.'.ipl');
           $this->IPbase=new IPsbase( $fun, $conf, $account,$ctime);
          }

 function IPsStatInit ()
          {
           $this->CodeError=0;
           $this->BaseInit(0);
           if($this->CodeError) return;
           $this->IPbase->IPsbaseInit();
           if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
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

 function GetIPs(&$StatSum,&$result)
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
           for($idrec=1;$idrec<$this->Size;$idrec++)
                 {
                  $rec=$this->GetRecordByID($idrec);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  $ip=$this->IPbase->GetIPByID($mas[2]);
                  if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
                  if(($timerec>=$timeB)&&($timerec<=$timeE))
                       {
                        if(!isset($result[$ip]))$result[$ip]=0;
                        $result[$ip]++;
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

 function GetUniqueIPs(&$PartSum,&$result)
          {
           $this->CodeError=0;
           $basetime = $this->IPbase->GetRecordByID(0);
           if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
           $ctime=time();
           $PartSum=0;
           reset($result);
           $e=each($result);
           $timeB=$e[0];
           for($idrec=1;$idrec<$this->IPbase->Size;$idrec++)
                 {
                  $rec=$this->IPbase->GetRecordByID($idrec);
                  if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
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
                   for(;$idrec<$this->IPbase->Size;$idrec++)
                         {
                          $rec=$this->IPbase->GetRecordByID($idrec);
                          if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
                          $mas=split("\|",$rec);
                          $timerec=$mas[0]+$basetime;
                          if($timerec<$timeE) {$result[$timeB]++;$PartSum++;}
                          else break;
                         }
                   }
                   $timeB=$timeE;
                  }
          }


 function GetByIP($ipc,&$StatSum)
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
           $maxi=0;
           for($idrec=1;$idrec<$this->Size;$idrec++)
                 {
                  $rec=$this->GetRecordByID($idrec);
                  if($this->CodeError) return;
                  $mas=split("\|",$rec);
                  $timerec=$mas[0]+$basetime;
                  $ip=$this->IPbase->GetIPByID($mas[2]);
                  if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
                  if(!strcmp($ip,$ipc))
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

 function GetIPsAll(&$StatSum)
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
                  $ip=$this->IPbase->GetIPByID($mas[2]);
                  if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
                  if(($timerec>=$timeB)&&($timerec<=$timeE))
                       {
                        if(!isset($result[$ip]))$result[$ip]=0;
                        $result[$ip]++;
                        $StatSum++;
                       }
                 }
           arsort($result);
           return $result;
          }

 function GetIPsPer()
          {
           $this->CodeError=0;
           $res=$this->GetIPs();
           if($this->CodeError) return;
           return $this->Fun->PerArray($res);
          }

 function GetIPsAllPer()
          {
           $this->CodeError=0;
           $res=$this->GetIPsAll();
           if($this->CodeError) return;
           return $this->Fun->PerArray($res);
          }

 function GetUniqueIPsPer()
          {
           $this->CodeError=0;
           $res=$this->GetUniqueIPs();
           if($this->CodeError) return;
           return $this->Fun->PerArray($res);
          }

 function GetByIPPer($ipc)
          {
           $this->CodeError=0;
           $res=$this->GetByIP($ipc);
           if($this->CodeError) return;
           return $this->Fun->PerArray($res);
          }

}

?>