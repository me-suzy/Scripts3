<?php

$idx = new ad_rpglog();


class ad_rpglog {
function ad_rpglog() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "RPG Inferno Logs";
$ADMIN->page_detail = "";
switch($IN['code']){

case 'delete':
$this->delete();
break;
case 'deleteall':
$this->deletetype();
break;

case 'view':
$this->view();
break;
default:
$this->viewall();
break;

}
$ADMIN->output();
}


function viewall(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Choose Log Type" , "50%" );
$SKIN->td_header[] = array( "Delete These Logs" , "50%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Log Type" );



$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Itemshop'>Itemshop Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Itemshop'>Delete Itemshop Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Bank'>Bank Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Bank'>Delete Bank Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Transfer'>Transfer Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Transfer'>Delete Transfer Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Healing Center'>Healing Center Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Healing Center'>Delete Healing Center Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Battle Ground'>Battle Ground Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Battle Ground'>Delete Battle Ground Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Clan'>Clan Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Clan'>Delete Clan Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=Lottery'>Lottery Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=Lottery'>Delete Lottery Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=RPG Store'>RPG Store Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=RPG Store'>Delete RPG Store Logs</a></center>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array(
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=view&type=RPG Job'>RPG Jobs Logs</a></center>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=deleteall&type=RPG Job'>Delete RPG Jobs Logs</a></center>",
)      );


$ADMIN->html .= $SKIN->end_table();
}

function view(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Log" , "70%" );
$SKIN->td_header[] = array( "Time" , "20%" );
$SKIN->td_header[] = array( "Delete" , "10%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "{$IN['type']} Logs" );
$DB->query("select * FROM ibf_infernologs WHERE type='".$IN['type']."' order by id desc");
while($log=$DB->fetch_row()){
$log['time'] = $std->get_date( $log['time'], 'JOINED' );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>{$log['log']}</b>",
"<b>{$log['time']}</b>",
"<center><a href='{$ADMIN->base_url}&act=rpglog&code=delete&id={$log['id']}'>Delete</a></center>",
)      );

}
$ADMIN->html .= $SKIN->end_table();

}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernologs WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernologs");
$this->viewall();
}
function deletetype(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernologs WHERE type='".$IN['type']."'");
$DB->query("OPTIMIZE TABLE ibf_infernologs");
$this->viewall();
}


// Class end
}
?>