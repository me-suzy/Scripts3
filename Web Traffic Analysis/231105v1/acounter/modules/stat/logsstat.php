<?php

//////////////////////////////////////////////////////////////////////////
// Script:        logsstat.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class LogsStat
{
 var $Acc;                     // Account
 var $Ctim;                    // Begin time in seconds
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Visitor;
 var $IP;
 var $IPbase;
 var $Lang;
 var $Langbase;
 var $Ref;
 var $Refbase;
 var $IntervalTime;

 function LogsStat ( $fun, $conf, $account , $ctime, $intt)
          {
           $this->CodeError=0;
           $this->Acc=$account;
           $this->Ctim=$ctime;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->IntervalTime=$intt;
           $name=date( 'zY' , $ctime );
           $this->Visitor= new Base( $fun, $conf, 'accounts/'.$account.'/daily/visitors/'.$name.'.vsl');
           $this->IP = new Base( $fun, $conf, 'accounts/'.$account.'/daily/ips/'.$name.'.ipl');
           $this->IPbase = new IPsbase( $fun, $conf, $account , $ctime);
           $this->Lang = new Base( $fun, $conf, 'accounts/'.$account.'/daily/language/'.$name.'.lnl');
           $this->Langbase = new Languagesbase( $fun, $conf, $account , $ctime);
           $this->Ref = new Base($fun, $conf, 'accounts/'.$account.'/daily/referers/'.$name.'.rfl');
           $this->Refbase = new Referersbase( $fun, $conf, $account , $ctime);
          }

 function LogsStatInit ()
          {
           $this->CodeError=0;
           $this->Visitor->BaseInit(0);
           if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
           $this->IP->BaseInit(0);
           if($this->IP->CodeError) {$this->CodeError=$this->IP->CodeError;return;}
           $this->IPbase->BaseInit(0);
           if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
           $this->Lang->BaseInit(0);
           if($this->Lang->CodeError) {$this->CodeError=$this->Lang->CodeError;return;}
           $this->Langbase->BaseInit(0);
           if($this->Langbase->CodeError) {$this->CodeError=$this->Langbase->CodeError;return;}
           $this->Ref->BaseInit(0);
           if($this->Ref->CodeError) {$this->CodeError=$this->Ref->CodeError;return;}
           $this->Refbase->BaseInit(0);
           if($this->Refbase->CodeError) {$this->CodeError=$this->Refbase->CodeError;return;}
          }

 function GetLog (&$statarray)
          {
           $this->CodeError=0;
           $statarray=array();
           $basetime = $this->Visitor->GetRecordByID(0);
           if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}

           for($t=1;$t<$this->Visitor->Size;$t++)
                   {
                    $rec=$this->Visitor->GetRecordByID( $t );
                    if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                    $mas=split("\|",$rec);
                    if($this->Ctim<=($basetime+$mas[0])) break;
                   }
           if($t==$this->Visitor->Size) return;

           for($i=$t;$i<$this->Visitor->Size;$i++)
                   {
                    $rec=$this->Visitor->GetRecordByID( $i );
                    if($this->Visitor->CodeError) {$this->CodeError=$this->Visitor->CodeError;return;}
                    $mas=split("\|",$rec);
                    if(($this->Ctim+$this->IntervalTime)<($basetime+$mas[0])) break;

                    $rec1=$this->IP->GetRecordByID( $i );
                    if($this->IP->CodeError) {$this->CodeError=$this->IP->CodeError;return;}
                    $mas1=split("\|",$rec1);
                    $name1=$this->IPbase->GetIPByID($mas1[2]);
                    if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}

                    $rec2=$this->Lang->GetRecordByID( $i );
                    if($this->Lang->CodeError) {$this->CodeError=$this->Lang->CodeError;return;}
                    $mas2=split("\|",$rec2);
                    $name2=$this->Langbase->GetLanguageByID($mas2[2]);
                    if($this->Langbase->CodeError) {$this->CodeError=$this->Langbase->CodeError;return;}
                    $tmas=split("\|",$name2);
                    $name2=$tmas[0];

                    $rec3=$this->Ref->GetRecordByID( $i );
                    if($this->Ref->CodeError) {$this->CodeError=$this->Ref->CodeError;return;}
                    $mas3=split("\|",$rec3);
                    $name3=$this->Refbase->GetRefererByID($mas3[2]);
                    if($this->Refbase->CodeError) {$this->CodeError=$this->Refbase->CodeError;return;}

                    $statarray[$i]['time']=date('H:i:s',$basetime+$mas[0]);
                    $statarray[$i]['ip']='&nbsp;&nbsp;'.$name1;
                    $statarray[$i]['lang']=$name2;
                    $statarray[$i]['ref']=$name3;
                   }
           if(count($statarray)==0) $statarray[0]=0;
          }

}

?>