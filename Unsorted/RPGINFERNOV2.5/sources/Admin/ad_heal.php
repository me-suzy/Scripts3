<?php

$idx = new ad_heal();


class ad_heal {
function ad_heal() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Healing In Healing Center";
$ADMIN->page_detail = "";

switch($IN['code']){
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

case 'add':
$this->add();
break;
case 'doadd':
$this->do_add();
break;

default:
$this->view_heal();
break;
}
$ADMIN->output();
}

function view_heal(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Image"    , "10%" );
$SKIN->td_header[] = array( "Name" , "20%" );
$SKIN->td_header[] = array( "Description"       , "35%" );
$SKIN->td_header[] = array( "Cost"         , "10%" );
$SKIN->td_header[] = array( "HP"         , "5%" );
$SKIN->td_header[] = array( "MP"         , "5%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Healing In Healing Center" );
$DB->query("SELECT * FROM ibf_infernoheal");
while ($Data = $DB->fetch_row() ) {
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/heal/{$Data['img']}'></center>" ,
"<center><b>{$Data['name']}</b></center>",
"<center>{$Data['desc']}</center>",
"<center>{$Data['cost']}</center>",
"<center>{$Data['hp']}</center>",
"<center>{$Data['mp']}</center>",
"<center><a href='{$ADMIN->base_url}&act=heal&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=heal&code=delete&id={$Data['id']}'>Delete</a></center>",
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
2 => array( 'act'  , 'heal'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernoheal WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the healing inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Healing to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove this Healing");	 
$ADMIN->html .= $SKIN->end_table();
}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernoheal WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoheal");

// unfortunatly, anyone who has this healing doesn't any more =(
$DB->query("update ibf_members set heal1='' where heal1='".$IN['id']."'");
$DB->query("update ibf_members set heal2='' where heal2='".$IN['id']."'");
$DB->query("update ibf_members set heal3='' where heal3='".$IN['id']."'");

$ADMIN->done_screen("Healing Removed", "Healing Center Control", "act=heal" );
}

function add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'heal'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------
$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Healing" );

//Grab Items
$handle = opendir($INFO['html_dir'] . "/Inferno/heal/");
$icons[] = array('blank.gif', 'Select A Heal Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Heal Name</b><br>" ,
$SKIN->form_input("name", "" )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/heal/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Heal Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/heal/blank.gif' name='showitem' border='0'>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Cost</b>" ,
$SKIN->form_input("cost", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP</b><br>HP points given when used." ,
$SKIN->form_input("hp", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP</b><br>MP points given when used." ,
$SKIN->form_input("mp", "" )
)      );
$ADMIN->html .= $SKIN->end_form('Add Healing');
$ADMIN->html .= $SKIN->end_table();
}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_infernoheal` VALUES ('','{$IN['img']}','{$IN['name']}','{$IN['desc']}','{$IN['cost']}','{$IN['hp']}','{$IN['mp']}','')  ");
$DB->query("OPTIMIZE TABLE ibf_infernoheal");
$ADMIN->done_screen("Healing Added", "Healing Center Control", "act=heal" );
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'heal'     ),
3 => array( 'id'    , $IN['id']   ),
)  );
$DB->query("SELECT * FROM ibf_infernoheal WHERE id='".$IN['id']."'");
if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the healing inside the db");
}	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Healing" );

//Grab Items
$handle = opendir($INFO['html_dir'] . "/Inferno/heal/");
$icons[] = array('blank.gif', 'Select A Heal Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Heal Name</b><br>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/heal/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Heal Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/heal/blank.gif' name='showitem' border='0'>
<script>
x=document.theAdminForm.img.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$Data['img']}'){
x[u].selected=true;
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/heal/' + document.theAdminForm.img.value
}}
</script>
",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", $Data['desc'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Cost</b>" ,
$SKIN->form_input("cost", $Data['cost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP</b><br>HP points given when used." ,
$SKIN->form_input("hp", $Data['hp'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP</b><br>MP points given when used." ,
$SKIN->form_input("mp", $Data['mp'] )
)      );

$ADMIN->html .= $SKIN->end_form('Update Healing');
$ADMIN->html .= $SKIN->end_table();
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
						    'name'     => $IN['name'],
						    'img'  => $IN['img'],
						    '`desc`'     => $IN['desc'],
						    'cost'      => $IN['cost'],
						    'hp'      => $IN['hp'],
						    'mp'      => $IN['mp'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_infernoheal` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoheal");
$ADMIN->done_screen("Healing Updated", "Healing Center Control", "act=heal" );
}

// end class
}
?>