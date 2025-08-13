<?php

$idx = new ad_heal();


class ad_heal {
function ad_heal() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Battles";
$ADMIN->page_detail = "Delete Current Battles In Battle Ground";

switch($IN['code']){

case 'delete':
$this->delete();
break;
case 'dodelete':
$this->do_delete();
break;

default:
$this->view();
break;
}
$ADMIN->output();
}

function view(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "ID"    , "1%" );
$SKIN->td_header[] = array( "Battle"    , "80%" );
$SKIN->td_header[] = array( "View"    , "10%" );
$SKIN->td_header[] = array( "Delete"         , "10%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Current Battles" );
$DB->query("SELECT * FROM ibf_infernobattle");
while ($Data = $DB->fetch_row() ) {
$ADMIN->html .= $SKIN->add_td_row( array(
"<center>{$Data['id']}</center>",
"<center><b>{$Data['vs']}</b></center>",
"<center><a href='index.php?act=RPG&CODE=ViewBattle&id={$Data['id']}' target='_blank'>View</a></center>",
"<center><a href='{$ADMIN->base_url}&act=bcontrol&code=delete&id={$Data['id']}'>Delete</a></center>",
)      );

}
$ADMIN->html .= $SKIN->end_table();
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'bcontrol'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernobattle WHERE id='{$IN['id']}'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the fight inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );


$ADMIN->html .= $SKIN->end_form("Remove this Battle");	 
$ADMIN->html .= $SKIN->end_table();
}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("SELECT * FROM ibf_infernobattle where id='{$IN['id']}'");
$Data = $DB->fetch_row();
$DB->query("update ibf_members set inbattle='0' where id='{$Data['p1']}'");
$DB->query("update ibf_members set inbattle='0' where id='{$Data['p2']}'");

$DB->query("DELETE FROM ibf_infernobattle WHERE id='{$IN['id']}'");
$DB->query("OPTIMIZE TABLE ibf_infernobattle");



$ADMIN->done_screen("Battle Removed", "Battle Control", "act=bcontrol" );
}



// end class
}
?>