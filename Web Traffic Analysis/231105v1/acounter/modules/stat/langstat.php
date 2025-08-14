<?php

//////////////////////////////////////////////////////////////////////////
// Script:        langstat.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class LanguagesStat extends Base
{

 var $Acc;                     // Account
 var $Ctim;                    // Begin time in seconds
 var $Per;                     // Period in seconds
 var $Lbase;                   // Object LanguagesBase
 var $NumPer;                  // The number of periods
 var $Conf;
 var $Fun;
 var $LName;

 function LanguagesStat ( $fun, $conf, $account , $ctime ,$period,$numP)
          {
           $this->CodeError=0;
           $this->Acc=$account;
           $this->Ctim=$ctime;
           $this->Per=$period;
           $this->NumPer=$numP;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/language/'.$name.'.lnl');
           $this->Lbase=new Languagesbase( $fun, $conf, $account,$ctime);
           $this->LName = new Base($fun, $conf, 'data/langbase.dat');
          }

 function LanguagesStatInit ()
          {
           $this->CodeError=0;
           $this->BaseInit(0);
           if($this->CodeError) return;
           $this->Lbase->LanguagesbaseInit();
           if($this->Lbase->CodeError) {$this->CodeError=$this->Lbase->CodeError;return;}
           $this->LName->BaseInit(0);
           if($this->LName->CodeError) {$this->CodeError=$this->LName->CodeError;return;}
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

 function GetLanguages(&$StatSum,&$result)
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
                  $lan=$this->Lbase->GetLanguageByID($mas[2]);
                  if($this->Lbase->CodeError) {$this->CodeError=$this->Lbase->CodeError;return;}
                  if(strcmp($lan,'undefined'))
                      {
                       $tlan = split( "-", $lan);
                       $lan = $this->LName->GetRecordByField(0,$tlan[0]);
                       if($this->LName->CodeError)
                           {
                            if($this->LName->CodeError!=2) {$this->CodeError=$this->LName->CodeError;return;}
                            $lan='undefined';
                           }
                       else
                           {
                            $lan = split( "\|", $lan);
                            $lan=$lan[1];
                           }
                      }
                  if(($timerec>=$timeB)&&($timerec<=$timeE))
                       {
                        if(!isset($result[$lan]))$result[$lan]=0;
                        $result[$lan]++;
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

 function GetByLanguage($lang,&$StatSum)
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
                  $lan=$this->Lbase->GetLanguageByID($mas[2]);
                  if($this->Lbase->CodeError) {$this->CodeError=$this->Lbase->CodeError;return;}
                  if(!strcmp($lan,$lang))
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