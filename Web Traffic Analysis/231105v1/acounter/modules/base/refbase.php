<?php

//////////////////////////////////////////////////////////////////////////
// Script:        refbase.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Referersbase extends Base
{
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Acc;

 function Referersbase ( $fun, $conf,  $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $name=date( 'zY' , $ctime );
           $this->Base($fun,$conf,'accounts/'.$account.'/daily/referers/'.$name.'.rfb');
          }

 function ReferersbaseInit ( )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->BaseInit(1);
          }

 function AddReferer( $referer )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByReferer ( $referer );
           if($this->CodeError)
                     {
                      if($this->CodeError!=2) return;
                     }
           else return $id;
           $id = $this->AddRecord($referer);
           if($this->CodeError) return;
           return $id+1;
          }

 function GetIDByReferer( $referer )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByRecord( $referer );
           if($this->CodeError) return;
           return $id+1;
          }

 function GetRefererByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="class Referersbase";return;}
           $referer = $this->GetRecordByID( $id-1 );
           if($this->CodeError) return;
           return $referer;
          }

}

?>