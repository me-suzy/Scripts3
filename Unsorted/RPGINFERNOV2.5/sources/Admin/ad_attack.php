<?php

$idx = new ad_attack();


class ad_attack {
function ad_attack() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Battle Moves";
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
$SKIN->td_header[] = array( "Name" , "69%" );
$SKIN->td_header[] = array( "Type" , "1%" );
$SKIN->td_header[] = array( "Edit"         , "15%" );
$SKIN->td_header[] = array( "Delete"         , "15%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Battle Moves" );

$i=0;
$DB->query("SELECT * FROM ibf_infernoreturn");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['id']}</b></center>",
"<center>{$Data['name']}</center>",
"<center>{$Data['type']}</center>",
"<center><a href='{$ADMIN->base_url}&act=attack&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=attack&code=delete&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'attack'     ),
3 => array( 'id'    , $IN['id']   ),
)  );


$DB->query("SELECT * FROM ibf_infernoreturn WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the moves inside the db");
}
$type[]=array("Spell","Spell");
$type[]=array("Attack","Attack");

$handle = opendir($INFO['html_dir'] . "/Inferno/sprite/");
$icons[] = array('blank.gif', 'Select A Sprite Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Battle Move" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Type</b>" ,
$SKIN->form_dropdown('type',$type ,"","")
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/sprite/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Sprite Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/sprite/blank.gif' name='showitem' border='0'>

<script>
x=document.theAdminForm.img.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$Data['img']}'){
x[u].selected=true;
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/sprite/' + document.theAdminForm.img.value
}}
</script>",
)      );
$ADMIN->html .= $SKIN->end_form('Update Battle Move');
 
$ADMIN->html .= $SKIN->end_table();
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("UPDATE `ibf_infernoreturn` SET name='{$IN['name']}',type='{$IN['type']}',img='{$IN['img']}' WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoreturn");
$ADMIN->done_screen("Battle Move Updated", "Battle Move Management", "act=attack" );
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'attack'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernoreturn WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the move inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Battle Move to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );
$ADMIN->html .= $SKIN->end_form('Delete This Battle Move');	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernoreturn WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoreturn");
$ADMIN->done_screen("Battle Move Removed", "Battle Move Management", "act=attack" );
}

function add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'attack'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$type[]=array("Spell","Spell");
$type[]=array("Attack","Attack");

$handle = opendir($INFO['html_dir'] . "/Inferno/sprite/");
$icons[] = array('blank.gif', 'Select A Sprite Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Battle Move" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", "" )
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Type</b>" ,
$SKIN->form_dropdown('type', $type,"","")
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/sprite/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Sprite Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/sprite/blank.gif' name='showitem' border='0'>",
)      );
$ADMIN->html .= $SKIN->end_form('Add Battle Move');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_infernoreturn` VALUES ('','{$IN['name']}','{$IN['type']}','{$IN['img']}')  ");
$DB->query("OPTIMIZE TABLE ibf_infernoreturn");
$ADMIN->done_screen("Battle Move Added", "Battle Move Management", "act=attack" );

}

// Class end
}
?>