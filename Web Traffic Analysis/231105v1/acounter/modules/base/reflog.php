<?php

//////////////////////////////////////////////////////////////////////////
// Script:        reflog.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Refererslog extends Base
{
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Refbase;
 var $Acc;

 function Refererslog ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Conf = $conf;
           $this->Fun = $fun;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $this->Refbase = new Referersbase( $fun, $conf, $account , $ctime);
           $name=date( 'zY' , $ctime );
           $this->Base($fun, $conf, 'accounts/'.$account.'/daily/referers/'.$name.'.rfl');
          }

 function RefererslogInit ( )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Refbase->ReferersbaseInit();
           if($this->Refbase->CodeError) {$this->CodeError=$this->Refbase->CodeError;return;}
           $this->BaseInit(0);
           if($this->CodeError)
                     {
                      if($this->CodeError!=1) return;
                      $this->SaveRecord($this->Ctim);
                      if($this->CodeError) return;
                     }
          }

 function AddRefererToLog( $ctime , $visitor , $referer )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->Refbase->AddReferer( $referer );
           if($this->Refbase->CodeError) {$this->CodeError=$this->Refbase->CodeError;return;}
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;
           if($basetime > $ctime) {$this->CodeError=100;$ENameCoF="class Refererslog";return;}
           $offset = $ctime - $basetime;
           $this->AddRecord($offset.'|'.$visitor.'|'.$id);
          }

}

?>