<?php

$idx = new ad_element();


class ad_element {
function ad_element() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Elements";
$ADMIN->page_detail = "";
switch($IN['code']){
case 'editelement':
$this->edit_elements();
break;
case 'deleteelement':
$this->delete_element();
break;
case 'dodelete':
$this->do_delete();
break;

case 'doedit':
$this->do_edit();
break;

case 'addelement':
$this->add_element();
break;

case 'doadd':
$this->do_add();
break;

default:
$this->view_elements();
break;

}
$ADMIN->output();
}


function view_elements(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "ID" , "1%" );
$SKIN->td_header[] = array( "Name" , "69%" );
$SKIN->td_header[] = array( "Edit"         , "15%" );
$SKIN->td_header[] = array( "Delete"         , "15%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Elements For RPG Profiles" );

$i=0;
$DB->query("SELECT * FROM ibf_rpgelements");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['id']}</b></center>",
"<center>{$Data['element']}</center>",
"<center><a href='{$ADMIN->base_url}&act=element&code=editelement&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=element&code=deleteelement&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit_elements(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'element'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$DB->query("SELECT * FROM ibf_rpgelements WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the element inside the db");
}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Element" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("element", $Data['element'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Element');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_rpgelements` SET element='{$IN['element']}' WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_rpgelements");
$ADMIN->done_screen("Element Updated", "Element Management", "act=element" );
}

function delete_element(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'element'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_rpgelements WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Element to Remove</b>" ,
"<b>{$mGrab['element']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove This Element");	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_rpgelements WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_rpgelements");
$ADMIN->done_screen("Element Removed", "Element Management", "act=element" );
}

function add_element(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'element'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Element" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Element Name</b>" ,
$SKIN->form_input("element", "" )
)      );

$ADMIN->html .= $SKIN->end_form('Add Element');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_rpgelements` VALUES ('','{$IN['element']}')  ");
$DB->query("OPTIMIZE TABLE ibf_rpgelements");
$ADMIN->done_screen("Element Added", "Element Management", "act=element" );

}

// Class end
}
?>