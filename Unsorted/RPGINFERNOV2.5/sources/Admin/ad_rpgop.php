<?php

$idx = new ad_rpgop();


class ad_rpgop {
function ad_rpgop() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "RPG Inferno Main Settings";
$ADMIN->page_detail = "Manage the main settings for your RPG system here";
switch($IN['code']){
case 'dooptions':
$this->do_options();
break;
case 'setonline':
$this->online();
break;
case 'doonline':
$this->doonline();
break;

default:
$this->view_options();
break;
}
$ADMIN->output();
}

function view_options(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dooptions'  ),
2 => array( 'act'  , 'rpgop'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$SKIN->td_header[] = array( "Option"         , "50%" );
$SKIN->td_header[] = array( "Setting"        , "50%" );

// ----------------
// lets grab our option db's
$DB->query("SELECT * FROM ibf_clanoptions where id='1'");
$clan=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$bank=$DB->fetch_row();
// --------------##

$ADMIN->html .= $SKIN->start_table( "RPG Inferno Options" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Clans Cost</b>" ,
$SKIN->form_input("cost", $clan['cost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Interest Rate In Bank (10=10%)</b>" ,
$SKIN->form_input("intrest", $bank['intrest'] )
)      );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Money Given Per Reply</b>" ,
$SKIN->form_input("treply", $bank['treply'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Money Given Per Quote</b>" ,
$SKIN->form_input("tquote", $bank['tquote'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Money Given Per New Thread</b>" ,
$SKIN->form_input("tnew", $bank['tnew'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Money Given Per New Poll</b>" ,
$SKIN->form_input("tpoll", $bank['tpoll'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Maximum RPG Avatar Width</b>" ,
$SKIN->form_input("rpaw", $bank['rpaw'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Maximum RPG Avatar Height</b>" ,
$SKIN->form_input("rpah", $bank['rpah'] )
)      );



$ADMIN->html .= $SKIN->end_form('Update Options');
 
$ADMIN->html .= $SKIN->end_table();
}

function do_options(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ccost=$IN['cost'];
$binterest=$IN['intrest'];
// Blank = 0
if($ccost==""){$ccost="0";}
if($binterest==""){$binterest="0";}
// Save data
$DB->query("update ibf_clanoptions set cost='{$ccost}' where id='1'");
$DB->query("update ibf_rpgoptions set intrest='{$binterest}',treply='{$IN['treply']}',tnew='{$IN['tnew']}',tquote='{$IN['tquote']}',tpoll='{$IN['tpoll']}',rpah='{$IN['rpah']}',rpaw='{$IN['rpaw']}'");
$ADMIN->done_screen("RPG Inferno Options Updated", "RPG Inferno Options", "act=rpgop" );
}

function online(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
// grab data
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$online=$DB->fetch_row();

$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doonline'  ),
2 => array( 'act'  , 'rpgop'     ),
)  );
$ADMIN->html .= $SKIN->start_table( "RPG Inferno Sections State" );

$SKIN->td_header[] = array( "Option"         , "50%" );
$SKIN->td_header[] = array( "Setting"        , "30%" );
$SKIN->td_header[] = array( "Setting"        , "20%" );

$state[]=array('Online','Online');
$state[]=array('Offline','Offline');

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Itemshop State</b>" ,
$SKIN->form_dropdown('itemshopon', $state,"",""),
"Currently: <b>{$online['itemshopon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Bank State</b>" ,
$SKIN->form_dropdown('bankon', $state,"",""),
"Currently: <b>{$online['bankon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Transfer State</b>" ,
$SKIN->form_dropdown('transferon', $state,"",""),
"Currently: <b>{$online['transferon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Healing Center State</b>" ,
$SKIN->form_dropdown('healingon', $state,"",""),
"Currently: <b>{$online['healingon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Battle Ground State</b>" ,
$SKIN->form_dropdown('battleon', $state,"",""),
"Currently: <b>{$online['battleon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Clans State</b>" ,
$SKIN->form_dropdown('clanon', $state,"",""),
"Currently: <b>{$online['clanon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Stats State</b>" ,
$SKIN->form_dropdown('rpgstatson', $state,"",""),
"Currently: <b>{$online['rpgstatson']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Lottery State</b>" ,
$SKIN->form_dropdown('lotteryon', $state,"",""),
"Currently: <b>{$online['lotteryon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Store State</b>" ,
$SKIN->form_dropdown('storeon', $state,"",""),
"Currently: <b>{$online['storeon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Help State</b>" ,
$SKIN->form_dropdown('helpeon', $state,"",""),
"Currently: <b>{$online['helpeon']}</b>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>RPG Jobs State</b>" ,
$SKIN->form_dropdown('rpgjobon', $state,"",""),
"Currently: <b>{$online['rpgjobon']}</b>",
)      );
$ADMIN->html .= $SKIN->end_form('Update Section States');
 
$ADMIN->html .= $SKIN->end_table();
}

function doonline(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("update ibf_rpgoptions set itemshopon='{$IN['itemshopon']}',bankon='{$IN['bankon']}',transferon='{$IN['transferon']}',healingon='{$IN['healingon']}',battleon='{$IN['battleon']}',clanon='{$IN['clanon']}',rpgstatson='{$IN['rpgstatson']}',lotteryon='{$IN['lotteryon']}',storeon='{$IN['storeon']}',helpeon='{$IN['helpeon']}',rpgjobon='{$IN['rpgjobon']}' where id='1'");
$ADMIN->done_screen("RPG Inferno Section States Updated", "RPG Inferno Section States", "act=rpgop&code=setonline" );
}


// Class end
}
?>