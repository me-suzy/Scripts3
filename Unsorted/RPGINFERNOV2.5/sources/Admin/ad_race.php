<?php

$idx = new ad_race();


class ad_race {
function ad_race() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Races";
$ADMIN->page_detail = "";
switch($IN['code']){
case 'editrace':
$this->edit_races();
break;
case 'deleterace':
$this->delete_race();
break;
case 'dodelete':
$this->do_delete();
break;

case 'doedit':
$this->do_edit();
break;

case 'addrace':
$this->add_race();
break;

case 'doadd':
$this->do_add();
break;

default:
$this->view_races();
break;

}
$ADMIN->output();
}


function view_races(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "ID" , "1%" );
$SKIN->td_header[] = array( "Name" , "69%" );
$SKIN->td_header[] = array( "Edit"         , "15%" );
$SKIN->td_header[] = array( "Delete"         , "15%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Races For RPG Profiles" );

$i=0;
$DB->query("SELECT * FROM ibf_races");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['id']}</b></center>",
"<center>{$Data['race']}</center>",
"<center><a href='{$ADMIN->base_url}&act=race&code=editrace&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=race&code=deleterace&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit_races(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'race'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$DB->query("SELECT * FROM ibf_races WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the race inside the db");
}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Race" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("race", $Data['race'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Race');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_races` SET race='{$IN['race']}' WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_races");
$ADMIN->done_screen("Race Updated", "Race Management", "act=race" );
}

function delete_race(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'race'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_races WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Race to Remove</b>" ,
"<b>{$mGrab['race']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove This Race");	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_races WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_races");
$ADMIN->done_screen("Race Removed", "Race Management", "act=race" );
}

function add_race(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'race'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Race" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Race Name</b>" ,
$SKIN->form_input("race", "" )
)      );

$ADMIN->html .= $SKIN->end_form('Add Race');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_races` VALUES ('','{$IN['race']}')  ");
$DB->query("OPTIMIZE TABLE ibf_races");
$ADMIN->done_screen("Race Added", "Race Management", "act=race" );

}

// Class end
}
?>