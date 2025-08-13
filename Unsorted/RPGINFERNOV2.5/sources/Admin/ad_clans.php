<?php

$idx = new ad_clans();


class ad_clans {
function ad_clans() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Clan Control";
$ADMIN->page_detail = "Manage clans that your members run";
switch($IN['code']){
case 'add':
$this->add();
break;

case 'edit':
$this->edit();
break;

case 'doedit':
$this->do_edit();
break;

case 'delete':
$this->delete();
break;

case 'dodelete':
$this->do_delete();
break;

default:
$this->view_clans();
break;
}
$ADMIN->output();
}

function view_clans(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
// --------------
$SKIN->td_header[] = array( "Name"         , "45%" );
$SKIN->td_header[] = array( "Leader"        , "45%" );
$SKIN->td_header[] = array( "Members"         , "10%" );
$SKIN->td_header[] = array( "Edit"        , "1%" );
$SKIN->td_header[] = array( "Delete"         , "1%" );
// --------------
$ADMIN->html .= $SKIN->start_table( "Manage Clans" );

$DB->query("SELECT * FROM ibf_infernoclan");
while ($Data = $DB->fetch_row() ) {
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['name']}</b></center>" ,
"<center><a href='index.php?showuser={$Data['leaderid']}' target='_blank'>{$Data['leader']}</a></center>",
"<center>{$Data['totalm']}</center>",
"<center><a href='{$ADMIN->base_url}&act=clans&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=clans&code=delete&id={$Data['id']}'>Delete</a></center>",
)      );
}
$ADMIN->html .= $SKIN->end_table();
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'clans'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

// --------------
$SKIN->td_header[] = array( "Option"         , "50%" );
$SKIN->td_header[] = array( "Setting"        , "50%" );
// --------------
$DB->query("SELECT * FROM ibf_infernoclan where id='{$IN['id']}'");
$Data=$DB->fetch_row();
$ADMIN->html .= $SKIN->start_table( "Edit Clan {$Data['name']}" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Clan Logo</b><br>" ,
$SKIN->form_input("img", $Data['img'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Clan Name</b><br>" ,
$SKIN->form_input("name", $Data['name'] )
)      );

$ADMIN->html .= $SKIN->end_form("Update Clan");	 
$ADMIN->html .= $SKIN->end_table();
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_infernoclan` SET name='{$IN['name']}',img='{$IN['img']}' WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoclan");
$ADMIN->done_screen("Clan Updated", "Clan Control", "act=clans" );
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'clans'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernoclan WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the clan inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Clan to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove this Clan");	 
$ADMIN->html .= $SKIN->end_table();
}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("DELETE FROM ibf_infernoclan WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoclan");
// ok people, your getting kicked out :P
$DB->query("update ibf_members set inclan='' where inclan='".$IN['id']."'");
$DB->query("update ibf_members set claninv='' where claninv='".$IN['id']."'");

$ADMIN->done_screen("Clan Removed", "Clan Control", "act=clans" );
}

// Class end
}
?>