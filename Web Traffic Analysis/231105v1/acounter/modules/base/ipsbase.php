<?php

//////////////////////////////////////////////////////////////////////////
// Script:        ipbase.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class IPsbase extends Base
{
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Acc;
 var $CodeError;

 function IPsbase ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $name=date( 'zY' , $ctime );
           $this->Base($fun,$conf,'accounts/'.$account.'/daily/ips/'.$name.'.ipb');
          }

 function IPsbaseInit ( )
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

 function AddIP( $IP ,&$UnForArh)
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByIP ( $IP );
           $UnForArh=0;
           if($this->CodeError)
                     {
                      if($this->CodeError!=2) return;
                     }
           else return $id;
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;
           if($basetime > $this->Ctim) {$this->CodeError=100;$ENameCoF="class IPsbase";return;}
           $offset = $this->Ctim - $basetime;
           $id=$this->AddRecord($offset.'|'.$IP);
           if($this->CodeError) return;
           $UnForArh=1;
           return $id;
          }

 function GetIDByIP( $IP )
          { global $ENameCoF;
           $this->CodeError=0;

           reset($this->BaseFile);
           $elem=each($this->BaseFile);
           while($elem=each($this->BaseFile))
           {
            $fields = split( "\|", $elem[1]);
            if(!isset($fields[1])) {$this->CodeError=100;$ENameCoF="class IPsbase";return;}
            if(!strcmp($fields[1],$IP)) return $elem[0];
           }
           $this->CodeError=2;
           return;
          }

 function GetIPByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="class IPsbase";return;}
           $IP = $this->GetRecordByID( $id );
           $IP=split("\|",$IP);
           $IP=$IP[1];
           if($this->CodeError) return;
           return $IP;
          }

}

?>