<?php

//////////////////////////////////////////////////////////////////////////
// Script:        accbase.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Accountsbase extends Base
{
 var $CodeError;
 var $Conf;
 var $Fun;

 function Accountsbase ($fun,$conf)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Base($fun,$conf,'accounts/accounts.lst');
          }

 function AccountsbaseInit ()
          { global $ENameCoF;
           $this->CodeError=0;
           $this->BaseInit(0);
          }

 function AddAccount( $address,$name )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByAccountAddress( $address );
           if($this->CodeError)
                 {
                  if($this->CodeError!=2) return;
                 }
           else return $id;
           $id = $this->GetIDByAccountName( $name );
           if($this->CodeError)
                 {
                  if($this->CodeError!=2) return;
                 }
           else return $id;
           $id = $this->AddRecord($address.'|'.$name.'|1|');
          }

 function GetIDByAccountAddress( $address )
          { global $ENameCoF;
           $this->CodeError=0;
           if(empty($address)) {$this->CodeError=3;return;}
           $id = $this->GetIDByField( 0 , $address );
           if(!$this->CodeError) return $id+1;
           $id = $this->GetIDByField( 3 , $address );
           if(!$this->CodeError) return $id+1;
           $id = $this->GetIDByField( 3 , $address.'/' );
           if(!$this->CodeError) return $id+1;
          }

 function GetIDByAccountName( $name )
          { global $ENameCoF;
           $this->CodeError=0;
           if(empty($name)) {$this->CodeError=3;return;}
           $id = $this->GetIDByField( 1 , $name );
           if(!$this->CodeError) return $id+1;
          }


 function GetAccountAddressByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="accounts.lst";return;}
           $address = $this->GetFieldByID( 0 , $id-1 );
           if($this->CodeError) return;
           return $address;
          }

 function GetAccountNameByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="accounts.lst";return;}
           $name = $this->GetFieldByID( 1 , $id-1 );
           if($this->CodeError) return;
           return $name;
          }

 function GetColorByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="accounts.lst";return;}
           $color = $this->GetFieldByID( 2 , $id-1 );
           if($this->CodeError) return;
           return $color;
          }

}

?>