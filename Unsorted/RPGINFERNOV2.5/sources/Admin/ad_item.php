<?php

$idx = new ad_item();


class ad_item {
function ad_item() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Items In Itemshop";
$ADMIN->page_detail = "";

switch($IN['code']){
case 'edititem':
$this->edit_item();
break;

case 'viewitem':
$this->view_items();
break;

case 'additem':
$this->add_item();
break;

case 'deleteitem':
$this->delete_item();
break;

case 'deleteex':
$this->con_delete();
break;

case 'addex':
$this->con_add();
break;

case 'editex':
$this->con_edit();
break;


default:
$this->view_items();
break;

}
$ADMIN->output();
}


function view_items(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Image"    , "10%" );
$SKIN->td_header[] = array( "Name" , "20%" );
$SKIN->td_header[] = array( "Description"       , "25%" );
$SKIN->td_header[] = array( "Cost"         , "10%" );
$SKIN->td_header[] = array( "HP"         , "5%" );
$SKIN->td_header[] = array( "MP"         , "5%" );
$SKIN->td_header[] = array( "DEF"         , "5%" );
$SKIN->td_header[] = array( "STR"         , "5%" );
$SKIN->td_header[] = array( "Type"         , "5%" );
$SKIN->td_header[] = array( "Stock"         , "5%" );
$SKIN->td_header[] = array( "Level"         , "5%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Items In Itemshop" );

$i=0;
$DB->query("SELECT * FROM ibf_infernoshop");
while ($Data = $DB->fetch_row() ) {
$i++;


$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/items/{$Data['img']}'></center>" ,
"<center><b>{$Data['name']}</b></center>",
"<center>{$Data['desc']}</center>",
"<center>{$Data['cost']}</center>",
"<center>{$Data['hp']}</center>",
"<center>{$Data['mp']}</center>",
"<center>{$Data['def']}</center>",
"<center>{$Data['str']}</center>",
"<center>{$Data['type']}</center>",
"<center>{$Data['stock']}</center>",
"<center>{$Data['lvlre']}</center>",
"<center><a href='{$ADMIN->base_url}&act=additem&code=edititem&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=additem&code=deleteitem&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}


function delete_item(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'deleteex'  ),
2 => array( 'act'  , 'additem'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernoshop WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Item to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove this Item");	 
$ADMIN->html .= $SKIN->end_table();



}

function con_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernoshop WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoshop");

// unfortunatly, anyone who has this item doesn't any more =(
$DB->query("delete from ibf_infernostock where item='".$IN['id']."'");

// remove those that have it equipped
$DB->query("delete from ibf_infernoequip where eitem='{$IN['id']}'");

$ADMIN->done_screen("Item Removed", "Item Shop Control", "act=additem" );
}

function add_item(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'addex'  ),
2 => array( 'act'  , 'additem'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Item" );

//Grab Items
$handle = opendir($INFO['html_dir'] . "/Inferno/items/");
$icons[] = array('blank.gif', 'Select A Item Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

// Type Grab
$DB->query("select * FROM ibf_infernocat");
while($cat=$DB->fetch_row()){
$type[]=array($cat['cid'],$cat['cname']);
}

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Name</b><br>" ,
$SKIN->form_input("name", "" )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/items/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/items/blank.gif' name='showitem' border='0'>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Type</b>" ,
$SKIN->form_dropdown('type', $type,"","")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Cost</b>" ,
$SKIN->form_input("cost", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvlre", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Current Stock</b>" ,
$SKIN->form_input("stock", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>STR</b><br>STR points given if equipped." ,
$SKIN->form_input("str", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>DEF</b><br>DEF points given if equipped." ,
$SKIN->form_input("def", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP</b><br>HP points given if equipped." ,
$SKIN->form_input("hp", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP</b><br>MP points given if equipped." ,
$SKIN->form_input("mp", "" )
)      );



$ADMIN->html .= $SKIN->end_form('Add Item');
 
$ADMIN->html .= $SKIN->end_table();


}

function con_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("INSERT INTO `ibf_infernoshop` VALUES ('','{$IN['img']}','{$IN['name']}','{$IN['desc']}','{$IN['cost']}','{$IN['hp']}','{$IN['mp']}','{$IN['def']}','{$IN['str']}','{$IN['type']}','','{$IN['stock']}','{$IN['lvlre']}')  ");
$DB->query("OPTIMIZE TABLE ibf_infernoshop");
$ADMIN->done_screen("Item Added", "Item Shop Control", "act=additem" );
}

function con_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
						    'name'     => $IN['name'],
						    'img'  => $IN['img'],
						    '`desc`'     => $IN['desc'],
						    'cost'      => $IN['cost'],
						    'stock'      => $IN['stock'],
						    'lvlre'      => $IN['lvlre'],
						    'hp'      => $IN['hp'],
						    'mp'      => $IN['mp'],
						    'def'      => $IN['def'],
						    'str'      => $IN['str'],
						    'type'      => $IN['type'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_infernoshop` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernoshop");
$ADMIN->done_screen("Item Updated", "Item Shop Control", "act=additem" );
}

function edit_item(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'editex'  ),
2 => array( 'act'  , 'additem'     ),
3 => array( 'id'    , $IN['id']   ),
)  );


$DB->query("SELECT * FROM ibf_infernoshop WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the item inside the db");
}

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Item" );

//Grab Items
$handle = opendir($INFO['html_dir'] . "/Inferno/items/");
$icons[] = array('blank.gif', 'Select A Item Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

// Type Grab
$DB->query("select * FROM ibf_infernocat");
while($cat=$DB->fetch_row()){
$type[]=array($cat['cid'],$cat['cname']);
}

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Name</b><br>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/items/' + document.theAdminForm.img.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Image</b>" ,
$SKIN->form_dropdown('img', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/items/blank.gif' name='showitem' border='0'>
<script>
x=document.theAdminForm.img.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$Data['img']}'){
x[u].selected=true;
document.images['showitem'].src = '{$INFO['html_url']}/Inferno/items/' + document.theAdminForm.img.value
}}
</script>
",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Item Type</b>" ,
$SKIN->form_dropdown('type', $type,"","") ."
<script>
x=document.theAdminForm.type.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$Data['type']}'){
x[u].selected=true;
}}
</script>
"
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", $Data['desc'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Cost</b>" ,
$SKIN->form_input("cost", $Data['cost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvlre", $Data['lvlre'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Current Stock</b>" ,
$SKIN->form_input("stock", $Data['stock'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>STR</b><br>STR points given if equipped." ,
$SKIN->form_input("str", $Data['str'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>DEF</b><br>DEF points given if equipped." ,
$SKIN->form_input("def", $Data['def'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>HP</b><br>HP points given if equipped." ,
$SKIN->form_input("hp", $Data['hp'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP</b><br>MP points given if equipped." ,
$SKIN->form_input("mp", $Data['mp'] )
)      );



$ADMIN->html .= $SKIN->end_form('Update Item');
 
$ADMIN->html .= $SKIN->end_table();


}


// end class
}
?>