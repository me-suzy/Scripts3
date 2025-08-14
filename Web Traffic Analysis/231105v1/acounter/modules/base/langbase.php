<?php

//////////////////////////////////////////////////////////////////////////
// Script:        langbase.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Languagesbase extends Base
{
 var $Conf;
 var $Fun;
 var $LBase;
 var $Ctim;
 var $Acc;
 var $CodeError;

 function Languagesbase ( $fun, $conf, $account , $ctime )
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Ctim = $ctime;
           $this->Acc = $account;
           $name=date( 'zY' , $ctime );
           $this->Base($fun, $conf, 'accounts/'.$account.'/daily/language/base.lnb');
          }

 function LanguagesbaseInit ()
          { global $ENameCoF;
           $this->CodeError=0;
           $this->BaseInit(1);
          }

 function AddLanguage( $language )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByLanguage ( $language );
           if($this->CodeError)
                     {
                      if($this->CodeError!=2) return;
                     }
           else return $id;
           $id = $this->AddRecord($language);
           if($this->CodeError) return;
           return $id+1;
          }

 function GetIDByLanguage( $language )
          { global $ENameCoF;
           $this->CodeError=0;
           $id = $this->GetIDByRecord( $language );
           if($this->CodeError) return;
           return $id+1;
          }

 function GetLanguageByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if($id<1) {$this->CodeError=100;$ENameCoF="class Languagesbase";return;}
           $language = $this->GetRecordByID( $id-1 );
           if($this->CodeError) return;
           return $language;
          }

}

?>