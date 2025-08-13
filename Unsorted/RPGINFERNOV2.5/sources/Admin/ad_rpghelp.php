<?php

$idx = new ad_rpghelp();


class ad_rpghelp {
function ad_rpghelp() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage RPG Help System";
$ADMIN->page_detail = "";
switch($IN['code']){
case 'edit':
$this->edit();
break;
case 'delete':
$this->delete();
break;
case 'dodelete':
$this->do_delete();
break;

case 'doedit':
$this->do_edit();
break;

case 'add':
$this->add();
break;

case 'doadd':
$this->do_add();
break;

default:
$this->view();
break;

}
$ADMIN->output();
}


function view(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "ID" , "1%" );
$SKIN->td_header[] = array( "Name" , "19%" );
$SKIN->td_header[] = array( "Short Description" , "40%" );
$SKIN->td_header[] = array( "Edit"         , "15%" );
$SKIN->td_header[] = array( "Delete"         , "15%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage RPG Help Files" );

$i=0;
$DB->query("SELECT * FROM ibf_infernohelp");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['id']}</b></center>",
"<center>{$Data['name']}</center>",
"<center>{$Data['descr']}</center>",
"<center><a href='{$ADMIN->base_url}&act=rpghelp&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpghelp&code=deletesssssss&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'rpghelp'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$DB->query("SELECT * FROM ibf_infernohelp WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the help file inside the db");
}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit RPG Help File" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Short Description</b>" ,
$SKIN->form_input("descr", $Data['descr'] )
)      );
$Data['content']=str_replace("<br>","\n",$Data['content']);
$ADMIN->html .= $SKIN->add_td_row( array( "File Content",
$SKIN->form_textarea("content", $Data['content'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Help File');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_infernohelp` SET name='{$IN['name']}',descr='{$IN['descr']}',content='{$IN['content']}' WHERE id='{$IN['id']}'");
$DB->query("OPTIMIZE TABLE ibf_infernohelp");
$ADMIN->done_screen("Help File Updated", "RPG Help Management", "act=rpghelp" );
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'rpghelp'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernohelp WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the catagory inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Help File to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove This RPG Help File");	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernohelp WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernohelp");
$ADMIN->done_screen("Help File Removed", "RPG Help Management", "act=rpghelp" );
}

function add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'rpghelp'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add RPG Help File" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Short Description</b>" ,
$SKIN->form_input("descr", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "File Content",
$SKIN->form_textarea("content", "" )
)      );
$ADMIN->html .= $SKIN->end_form('Add Help File');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_infernohelp` VALUES ('','{$IN['name']}','{$IN['descr']}','{$IN['content']}')  ");
$DB->query("OPTIMIZE TABLE ibf_infernohelp");
$ADMIN->done_screen("Help File Added", "RPG Help Management", "act=rpghelp" );

}

// Class end
}
?>