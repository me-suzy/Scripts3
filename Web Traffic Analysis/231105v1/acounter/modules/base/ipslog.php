<?php

//////////////////////////////////////////////////////////////////////////
// Script:        ipslog.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class IPslog extends Base
{

 var $IPbase;
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Acc;
// var $CodeError;

 function IPslog ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $this->IPbase = new IPsbase( $fun, $conf, $account , $ctime);
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/ips/'.$name.'.ipl');
          }

 function IPslogInit ( )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->IPbase->IPsbaseInit();
           if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}

           $this->BaseInit(0);
           if($this->CodeError)
                     {
                      if($this->CodeError!=1) return;
                      $this->SaveRecord($this->Ctim);
                      if($this->CodeError) return;
                     }
          }

 function AddIPToLog( $ctime , $visitor , $ip , &$UnForArh)
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->IPbase->AddIP( $ip , $UnForArh);
           if($this->IPbase->CodeError) {$this->CodeError=$this->IPbase->CodeError;return;}
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;
           if($basetime > $ctime) {$this->CodeError=100;$ENameCoF="class IPslog";return;}
           $offset = $ctime - $basetime;
           $this->AddRecord($offset.'|'.$visitor.'|'.$id);
          }

}

?>