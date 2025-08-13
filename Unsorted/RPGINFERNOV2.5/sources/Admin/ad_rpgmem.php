<?php

$idx = new ad_rpgmem();


class ad_rpgmem {
function ad_rpgmem() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "RPG Profile Control";
$ADMIN->page_detail = "Edit members RPG Profile here";

switch($IN['code']){
case 'do_search':
$this->options();
break;
case 'edit_stats':
$this->view_mem();
break;
case 'edit_inv':
$this->view_inv();
break;
case 'removeitem':
$this->remove_item();
break;
case 'do_edit_stats':
$this->Save_stats();
break;
default:
$this->search();
break;

}

$ADMIN->output();
}

function options(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

// do we exist o.o ?
$DB->query("select * from ibf_members where name='{$IN['name']}'");
if(!$mem = $DB->fetch_row()){
	$ADMIN->error("Sorry we couldn't find a member by that name");
}

$SKIN->td_header[] = array( ""         , "50%" );
$SKIN->td_header[] = array( ""         , "50%" );
$ADMIN->html .= $SKIN->start_table( "Choose Edit" );
$ADMIN->html .= $SKIN->add_td_row( array( "<a href='{$ADMIN->base_url}&act=rpgmem&code=edit_stats&name={$IN['name']}'>Edit Members RPG Profile</a>" ,
"<a href='{$ADMIN->base_url}&act=rpgmem&code=edit_inv&name={$IN['name']}'>Edit Members Inventory</a>"
)      );
$ADMIN->html .= $SKIN->end_table();
}

function search(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( ""         , "70%" );
$SKIN->td_header[] = array( ""         , "30%" );
// ----------------

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_search'  ),
2 => array( 'act'  , 'rpgmem'     ),
3 => array( 'id'    , $IN['name']   ),
)      );

$ADMIN->html .= $SKIN->start_table( "Search Engine" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Input Member Name</b>" ,
$SKIN->form_input("name", "" )
)      );

$ADMIN->html .= $SKIN->end_form('Search Member');
 
$ADMIN->html .= $SKIN->end_table();

}

function view_inv(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

// do we exist o.o ?
$DB->query("select * from ibf_members where name='{$IN['name']}'");
if(!$mem = $DB->fetch_row()){
	$ADMIN->error("Sorry we couldn't find a member by that name");
}
$SKIN->td_header[] = array( "Icon"         , "1%" );
$SKIN->td_header[] = array( "Item"         , "50%" );
$SKIN->td_header[] = array( "Remove"         , "50%" );
$ADMIN->html .= $SKIN->start_table( "{$IN['name']}'s Inventory" );
// grab members items
$DB->query("select x.*,i.*
from ibf_infernostock x
left join ibf_infernoshop i on (x.item=i.id)
where x.owner='{$mem['id']}'");
while($Data=$DB->fetch_row()){
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/items/{$Data['img']}'></center>" ,
"<center><b>{$Data['name']}</b></center>",
"<center><a href='{$ADMIN->base_url}&act=rpgmem&code=removeitem&Iid={$Data['id']}&name={$mem['name']}'>Remove From Inventory</a></center>",
)      );
}
$ADMIN->html .= $SKIN->end_table();
}

function remove_item(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
// do we exist o.o ?
$DB->query("select * from ibf_members where name='{$IN['name']}'");
if(!$mem = $DB->fetch_row()){
	$ADMIN->error("Sorry we couldn't find a member by that name");
}

$DB->query("delete from ibf_infernostock where owner='{$mem['id']}' and item='{$IN['Iid']}'");


$this->view_inv();
}


function view_mem(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

// do we exist o.o ?
$DB->query("select * from ibf_members where name='{$IN['name']}'");
if(!$mem = $DB->fetch_row()){
	$ADMIN->error("Sorry we couldn't find a member by that name");
}

$SKIN->td_header[] = array( ""         , "70%" );
$SKIN->td_header[] = array( ""         , "30%" );
// ----------------

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_edit_stats'  ),
2 => array( 'act'  , 'rpgmem'     ),
3 => array( 'id'    , $mem['id']   ),
)      );

//+-------------------------------+ Begin RPG Table

$ADMIN->html .= $SKIN->start_table( "RPG Member Control" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Member Money</b>" ,
$SKIN->form_input("money","{$mem['money']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Member Money In Bank</b>" ,
$SKIN->form_input("bankmoney","{$mem['bankmoney']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Character Name</b>" ,
$SKIN->form_input("rpgname","{$mem['rpgname']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Race</b>" ,
$SKIN->form_input("rpgrace","{$mem['rpgrace']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Special Move</b>" ,
$SKIN->form_input("smove","{$mem['smove']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Elemental Type</b>" ,
$SKIN->form_input("rpgelement","{$mem['rpgelement']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Alignment</b>" ,
$SKIN->form_input("align","{$mem['align']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Gender</b>" ,
$SKIN->form_input("rpgsex","{$mem['rpgsex']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Avatar</b>" ,
$SKIN->form_input("rpgav","{$mem['rpgav']}")
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP</b>" ,
$SKIN->form_input("hp","{$mem['hp']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP Max</b>" ,
$SKIN->form_input("hpm","{$mem['hpm']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP</b>" ,
$SKIN->form_input("mp","{$mem['mp']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP Max</b>" ,
$SKIN->form_input("mpm","{$mem['mpm']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Rage</b>" ,
$SKIN->form_input("rage","{$mem['rage']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Str</b>" ,
$SKIN->form_input("str","{$mem['str']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Def</b>" ,
$SKIN->form_input("def","{$mem['def']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Exp</b>" ,
$SKIN->form_input("exp","{$mem['exp']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level</b>" ,
$SKIN->form_input("level","{$mem['level']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>No. Of Victory's</b>" ,
$SKIN->form_input("vics","{$mem['vics']}")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>No. Of Death's</b>" ,
$SKIN->form_input("loss","{$mem['loss']}")
)      );

$ADMIN->html .= $SKIN->end_form('Update Members RPG Profile');
	     
$ADMIN->html .= $SKIN->end_table();

//+-------------------------------+ End RPG Table
}

function Save_stats(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
// save settings :P
		$db_string = $DB->compile_db_update_string( array (
'money'	   => $IN['money'],
'rpgname'	   => $IN['rpgname'],
'rpgrace'	   => $IN['rpgrace'],
'rpgav'	   => $IN['rpgav'],
'hp'	   => $IN['hp'],
'hpm'	   => $IN['hpm'],
'mp'	   => $IN['mp'],
'mpm'	   => $IN['mpm'],
'def'	   => $IN['def'],
'str'	   => $IN['str'],
'exp'	   => $IN['exp'],
'rage'	   => $IN['rage'],
'level'	   => $IN['level'],
'vics'	   => $IN['vics'],
'loss'	   => $IN['loss'],
'smove'	   => $IN['smove'],
'rpgsex'	   => $IN['rpgsex'],
'align'	   => $IN['align'],
'bankmoney'	   => $IN['bankmoney'],
'rpgelement'	   => $IN['rpgelement'],
												  )       );
		$DB->query("UPDATE ibf_members SET $db_string WHERE id='{$IN['id']}'");
$ADMIN->done_screen("RPG Profile Updated", "RPG Profile Control", "act=rpgmem" );
}

// class end
}
?>