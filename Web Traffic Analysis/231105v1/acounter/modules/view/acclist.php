<?php

//////////////////////////////////////////////////////////////////////////
// Script:        acclist.php						//
//									//
// Source:	http://www.actualscripts.com/				//
//									//
// Copyright:	(c) 2002 Actual Scripts Company. All rights reserved.	//
//									//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.			//
// SEE LICENSE AGREEMENT FOR MORE DETAILS				//
//////////////////////////////////////////////////////////////////////////

class AccountsList
{
 var $Account;
 var $Active;
 var $List;
 var $Conf;
 var $Fun;
 var $Item;
 var $Items;
 var $CodeError;

 function AccountsList ($fun,$conf,$active )
          {
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->Active=$active;
           $this->Account = new Accountsbase($fun,$conf);
          }

 function AccountsListInit ()
          {
           $this->CodeError=0;
           $this->Account->AccountsbaseInit();
          }

 function CreateAccountsList ()
          {
           $this->CodeError=0;
           $this->List= new Template($this->Fun,$this->Conf,'list/list_tpl.php');
           $this->List->TemplateInit();
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
           $vvar['list']='vaccount';
           $vvar['ext']=' onChange="jump_fun(this.form)"';

           $this->Item= new Template($this->Fun,$this->Conf,'list/lit_tpl.php');
           $this->Item->TemplateInit();
           if($this->Item->CodeError) {$this->CodeError=$this->Item->CodeError;return;}

           $this->Items= new Template($this->Fun,$this->Conf,'');
           $max=$this->Account->Size;
           for($c=1;$c<=$max;$c++)
                  {
                   //ignore deleted accounts
                   $name=$this->Account->GetAccountAddressByID($c);
                   if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                   if(!strcmp($name,'deleted')) continue;

                   $name=$this->Account->GetAccountNameByID($c);
                   if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                   if($this->Account->CodeError) {$this->CodeError=$this->Account->CodeError;return;}
                   $tvar['value']=$c;
                   $tvar['name']=$name;
                   if($this->Active==$c) $tvar['active']=' selected';
                   else $tvar['active']='';
                   $this->Items->AddTemplate($this->Item->GetTemplate());
                   $this->Items->ParseTemplate ($tvar);
                   if($this->Items->CodeError) {$this->CodeError=$this->Items->CodeError;return;}
                  }

           $vvar['items']=$this->Items->GetTemplate();
           $this->List->ParseTemplate ($vvar);
           if($this->List->CodeError) {$this->CodeError=$this->List->CodeError;return;}
          }

 function GetAccountsList ()
          {
           $this->CodeError=0;
           return $this->List->GetTemplate();
          }

 function OutAccountsList ()
          {
           $this->CodeError=0;
           $this->List->OutTemplate();
          }

}

?>