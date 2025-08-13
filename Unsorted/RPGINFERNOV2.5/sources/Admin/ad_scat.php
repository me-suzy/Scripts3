<?php

$idx = new ad_scat();


class ad_scat {
function ad_scat() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Itemshop Catagories";
$ADMIN->page_detail = "";
switch($IN['code']){
case 'editscat':
$this->edit_scats();
break;
case 'deletescat':
$this->delete_scat();
break;
case 'dodelete':
$this->do_delete();
break;

case 'doedit':
$this->do_edit();
break;

case 'addscat':
$this->add_scat();
break;

case 'doadd':
$this->do_add();
break;

default:
$this->view_scats();
break;

}
$ADMIN->output();
}


function view_scats(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "ID" , "1%" );
$SKIN->td_header[] = array( "Name" , "19%" );
$SKIN->td_header[] = array( "Description" , "40%" );
$SKIN->td_header[] = array( "Edit"         , "15%" );
$SKIN->td_header[] = array( "Delete"         , "15%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Catagories For Itemshop" );

$i=0;
$DB->query("SELECT * FROM ibf_infernocat");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['cid']}</b></center>",
"<center>{$Data['cname']}</center>",
"<center>{$Data['desc']}</center>",
"<center><a href='{$ADMIN->base_url}&act=scat&code=editscat&cid={$Data['cid']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=scat&code=deletescat&cid={$Data['cid']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit_scats(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'scat'     ),
3 => array( 'cid'    , $IN['cid']   ),
)  );

$DB->query("SELECT * FROM ibf_infernocat WHERE cid='".$IN['cid']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the catagory inside the db");
}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Catagory" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", $Data['cname'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", $Data['desc'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Catagory');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_infernocat` SET cname='{$IN['name']}' WHERE cid='{$IN['cid']}'");
$DB->query("UPDATE `ibf_infernocat` SET `desc`='{$IN['desc']}' WHERE cid='{$IN['cid']}'");
$DB->query("OPTIMIZE TABLE ibf_infernocat");
$ADMIN->done_screen("Itemshop Catagory Updated", "Catagory Management", "act=scat" );
}

function delete_scat(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'scat'     ),
3 => array( 'cid'    , $IN['cid']   ),
)      );

if($IN['cid']=="1" or $IN['cid']=="2" or $IN['cid']=="3" ){
	$ADMIN->error("Sorry, you can't delete a default catagory - this is part of the equip system");
}

$DB->query("SELECT * FROM ibf_infernocat WHERE cid='".$IN['cid']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the catagory inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Catagory to Remove</b>" ,
"<b>{$mGrab['cname']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove This Catagory");	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernocat WHERE cid='".$IN['cid']."'");
$DB->query("OPTIMIZE TABLE ibf_infernocat");
$ADMIN->done_screen("Itemshop Catagory Removed", "Catagory Management", "act=scat" );
}

function add_scat(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'scat'     ),
3 => array( 'cid'    , $IN['cid']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Catagory" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Catagory Name</b>" ,
$SKIN->form_input("cname", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Catagory Description</b>" ,
$SKIN->form_input("desc", "" )
)      );

$ADMIN->html .= $SKIN->end_form('Add Catagory');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_infernocat` VALUES ('','{$IN['cname']}','{$IN['desc']}')  ");
$DB->query("OPTIMIZE TABLE ibf_infernocat");
$ADMIN->done_screen("Itemshop Catagory Added", "Catagory Management", "act=scat" );

}

// Class end
}
?>