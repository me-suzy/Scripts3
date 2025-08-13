<?php

$idx = new ad_rpgstore();


class ad_rpgstore {
function ad_rpgstore() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Mange RPG Store";
$ADMIN->page_detail = "";

switch($IN['code']){
case 'add_item':
$this->add_item();
break;

case 'do_add':
$this->do_add();
break;

case 'do_add_con':
$this->do_add_con();
break;

case 'edit':
$this->edit();
break;

case 'doedit':
$this->doedit();
break;

case 'delete':
$this->delete();
break;

case 'dodelete':
$this->dodelete();
break;

default:
$this->view();
break;
}
$ADMIN->output();
}

function view(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Icon"    , "10%" );
$SKIN->td_header[] = array( "Name" , "20%" );
$SKIN->td_header[] = array( "Description"       , "35%" );
$SKIN->td_header[] = array( "Cost"         , "10%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Items In RPG Store" );
$DB->query("SELECT * FROM ibf_infernostore");
while ($Data = $DB->fetch_row() ) {
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/store/{$Data['img']}'></center>" ,
"<center><b>{$Data['name']}</b></center>",
"<center>{$Data['descr']}</center>",
"<center>{$Data['cost']}</center>",
"<center><a href='{$ADMIN->base_url}&act=rpgstore&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpgstore&code=delete&id={$Data['id']}'>Delete</a></center>",
)      );

}
$ADMIN->html .= $SKIN->end_table();
}

function add_item() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_add'  ),
2 => array( 'act'  , 'rpgstore'     ),
)  );

$ADMIN->html .= $SKIN->start_table( "Add Item To RPG Store" );

$handle = opendir("./sources/Store_Items/");
$item[] = array('', 'Select A Item');
while ($items = readdir($handle)) {
if(preg_match("/store_/",$items)) {
if($items != '.' || $items  != '..') {
$item[] = array($items, $items);
}}}


closedir($handle);

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Select a Item To Add To The RPG Store:</b><br>",
  $SKIN->form_dropdown('item_name', $item), $IN['item_name']
 )      );
$ADMIN->html .= $SKIN->end_form('Add This Store Item');
$ADMIN->html .= $SKIN->end_table();
}

function do_add(){
global $IN, $INFO, $ITEM, $DB, $SKIN, $ADMIN;

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_add_con'  ),
2 => array( 'act'  , 'rpgstore'     ),
)  );

//Grab Icons
$handle = opendir($INFO['html_dir'] . "/Inferno/store/");
$icons[] = array('blank.gif', 'Select A Store Item Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$file=$ibforums->vars['base_dir']."sources/Store_Items/".$IN['item_name'];
// check file exists
if(!file_exists($file)) {
	$ADMIN->error("Unable to find the item inside the db ({$file} was unable to be found)");
}
require($file);
$item = new item;

$ADMIN->html .= $SKIN->start_table( "Adding Item To RPG Store" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Name</b>" ,
$SKIN->form_input("name", "{$item->name}" )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/store/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/store/blank.gif' name='showitem' border='0'>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Description</b>" ,
$SKIN->form_input("descr", "{$item->descr}" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Cost</b>" ,
$SKIN->form_input("cost", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Stock</b>" ,
$SKIN->form_input("stock", "" )
)      );
$ADMIN->html .= "<input type='hidden' name='rfile' value='{$IN['item_name']}'>";

$ADMIN->html .= $SKIN->end_form('Add Store Item');
 
$ADMIN->html .= $SKIN->end_table();
}


function do_add_con() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("INSERT INTO `ibf_infernostore` VALUES ('','{$IN['name']}','{$IN['descr']}','{$IN['img']}','{$IN['cost']}','{$IN['rfile']}','{$IN['stock']}')  ");
$DB->query("OPTIMIZE TABLE ibf_infernostore");
$ADMIN->done_screen("Store Item Added", "RPG Store Control", "act=rpgstore" );
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("SELECT * FROM ibf_infernostore WHERE id='{$IN['id']}'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}

//Grab Icons
$handle = opendir($INFO['html_dir'] . "/Inferno/store/");
$icons[] = array('blank.gif', 'Select A Store Item Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'rpgstore'     ),
3 => array( 'id'  , $IN['id']     ),
)  );


$ADMIN->html .= $SKIN->start_table( "Update RPG Store Item: ".$Data['name'] );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Name</b>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/store/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/store/blank.gif' name='showitem' border='0'>
<script>
x=document.theAdminForm.img.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$Data['img']}'){
x[u].selected=true;
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/store/' + document.theAdminForm.img.value
}}
</script>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Description</b>" ,
$SKIN->form_input("descr", $Data['descr'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Cost</b>" ,
$SKIN->form_input("cost", $Data['cost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Stock</b>" ,
$SKIN->form_input("stock", $Data['stock'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Store Item');
$ADMIN->html .= $SKIN->end_table();
}

function doedit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
						    'name'     => $IN['name'],
						    'img'  => $IN['img'],
						    'descr'     => $IN['descr'],
						    'cost'      => $IN['cost'],
						    'stock'      => $IN['stock'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_infernostore` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernostore");
$ADMIN->done_screen("Store Item Updated", "RPG Store Control", "act=rpgstore" );
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'rpgstore'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernostore WHERE id='".$IN['id']."'");
if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}
$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Store Item to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );
$ADMIN->html .= $SKIN->end_form("Remove This Store Item");	 
$ADMIN->html .= $SKIN->end_table();
}

function dodelete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernostore WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernostore");

$ADMIN->done_screen("Store Item Removed", "RPG Store Control", "act=rpgstore" );
}

// end class
}
?>