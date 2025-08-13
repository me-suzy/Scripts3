<?php

$idx = new ad_summons();


class ad_summons {
function ad_summons() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Summons";
$ADMIN->page_detail = "";

switch($IN['code']){
case 'edit':
$this->edit();
break;

case 'view':
$this->view();
break;

case 'add':
$this->add();
break;

case 'delete':
$this->delete();
break;

case 'do_delete':
$this->do_delete();
break;

case 'do_add':
$this->do_add();
break;

case 'do_edit':
$this->do_edit();
break;


default:
$this->view();
break;

}
$ADMIN->output();
}


function view(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Image"    , "10%" );
$SKIN->td_header[] = array( "Name" , "20%" );
$SKIN->td_header[] = array( "Required Level"         , "15%" );
$SKIN->td_header[] = array( "MP Cost"         , "10%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Summons" );


$DB->query("SELECT * FROM ibf_infernosummon");
while ($data = $DB->fetch_row() ) {

$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/summons/{$data['img']}'></center>" ,
"<center><b>{$data['name']}</b></center>",
"<center>{$data['lvl']}</center>",
"<center>{$data['mp']}</center>",
"<center><a href='{$ADMIN->base_url}&act=summons&code=edit&id={$data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=summons&code=delete&id={$data['id']}'>Delete</a></center>",
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
1 => array( 'code'  , 'do_delete'  ),
2 => array( 'act'  , 'summons'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernosummon WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the summon inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Summon to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove this Summon");	 
$ADMIN->html .= $SKIN->end_table();



}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernosummon WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernosummon");

// unfortunatly, anyone who has this summon doesn't any more =(
$DB->query("update ibf_members set summon='' where summon='".$IN['id']."'");
$ADMIN->done_screen("Summon Removed", "RPG Summon Control", "act=summons" );
}

function add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_add'  ),
2 => array( 'act'  , 'summons'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Summon" );

//Get the summons
$handle = opendir($INFO['html_dir'] . "/Inferno/summons/");
$icons[] = array('blank.gif', 'Select A Summon Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}


$ADMIN->html .= $SKIN->add_td_row( array( "<b>Summona Name</b><br>" ,
$SKIN->form_input("name", "" )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showjob'].src = '{$INFO['html_url']}/Inferno/summons/' + document.theAdminForm.icon.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Summon Image</b>" ,
$SKIN->form_dropdown('icon', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/summons/blank.gif' name='showjob' border='0'>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP Used When Summoned</b>" ,
$SKIN->form_input("mp", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvl", "" )
)      );


$ADMIN->html .= $SKIN->end_form('Add Summon');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$time=time();
$DB->query("INSERT INTO `ibf_infernosummon` VALUES ('','{$IN['icon']}','{$IN['name']}','{$IN['mp']}','{$IN['lvl']}')");
$DB->query("OPTIMIZE TABLE ibf_infernosummon");
$ADMIN->done_screen("Summon Added", "RPG Summon Control", "act=summons" );
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
                                        'name' => $IN['name'],
						    'img'  => $IN['icon'],
						    'lvl'  => $IN['lvl'],
						    'mp'      => $IN['mp'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_infernosummon` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernosummon");
$ADMIN->done_screen("Summon Updated", "RPG Summon Control", "act=summons" );
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_edit'  ),
2 => array( 'act'  , 'summons'     ),
3 => array( 'id'    , $IN['id']   ),
)  );


$DB->query("SELECT * FROM ibf_infernosummon WHERE id='".$IN['id']."'");

if ( ! $data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the summon inside the db");
}

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Summon" );

//Get the summons
$handle = opendir($INFO['html_dir'] . "/Inferno/summons/");
$icons[] = array('blank.gif', 'Select A Summon Image');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Summon Name</b><br>" ,
$SKIN->form_input("name", $data['name'] )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showjob'].src = '{$INFO['html_url']}/Inferno/summons/' + document.theAdminForm.icon.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Summon Image</b>" ,
$SKIN->form_dropdown('icon', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/summons/blank.gif' name='showsummon' border='0'>
<script>
x=document.theAdminForm.icon.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$data['img']}'){
x[u].selected=true;
document.images['showsummon'].src = '{$INFO['html_url']}/Inferno/summons/' + document.theAdminForm.icon.value
}}
</script>
",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>MP Used When Summoned</b>" ,
$SKIN->form_input("mp", $data['mp'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvl", $data['lvl'] )
)      );

$ADMIN->html .= $SKIN->end_form('Update Summon');
 
$ADMIN->html .= $SKIN->end_table();


}


// end class
}
?>