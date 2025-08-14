<?php

//////////////////////////////////////////////////////////////////////////
// Script:        langlog.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Languageslog extends Base
{

 var $Langbase;
 var $Conf;
 var $Fun;
 var $Ctim;
 var $Acc;
 var $CodeError;

 function Languageslog ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Conf = $conf;
           $this->Fun = $fun;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $this->Langbase = new Languagesbase( $fun, $conf, $account , $ctime);
           $name=date( 'zY' , $ctime );
           $this->Base( $fun, $conf, 'accounts/'.$account.'/daily/language/'.$name.'.lnl');
          }

 function LanguageslogInit ( )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Langbase->LanguagesbaseInit();
           if($this->Langbase->CodeError) {$this->CodeError=$this->Langbase->CodeError;return;}

           $this->BaseInit(0);
           if($this->CodeError)
                     {
                      if($this->CodeError!=1) return;
                      $this->SaveRecord($this->Ctim);
                      if($this->CodeError) return;
                     }
          }

 function AddLanguageToLog( $ctime , $visitor , $language )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->Langbase->AddLanguage( $language );
           if($this->Langbase->CodeError) {$this->CodeError=$this->Langbase->CodeError;return;}
           $basetime = $this->GetRecordByID(0);
           if($this->CodeError) return;
           if($basetime > $ctime) {$this->CodeError=100;$ENameCoF="class Languageslog";return;}
           $offset = $ctime - $basetime;
           $this->AddRecord($offset.'|'.$visitor.'|'.$id);
          }

}

?>