<?php

$idx = new ad_lottery();


class ad_lottery {
function ad_lottery() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Lottery";
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
$SKIN->td_header[] = array( "Name" , "1%" );
$SKIN->td_header[] = array( "Description" , "70%" );
$SKIN->td_header[] = array( "State" , "10%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Lotterys" );


$DB->query("SELECT * FROM ibf_lottery");
while ($Data = $DB->fetch_row() ) {



$ADMIN->html .= $SKIN->add_td_row( array(
"<center><b>{$Data['name']}</b></center>",
"<center>{$Data['descr']}</center>",
"<center><font color='green'>{$Data['state']}</font></center>",
"<center><a href='{$ADMIN->base_url}&act=lottery&code=edit&id={$Data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=lottery&code=delete&id={$Data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}

function edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doedit'  ),
2 => array( 'act'  , 'lottery'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

$DB->query("SELECT * FROM ibf_lottery WHERE id='".$IN['id']."'");

if ( ! $Data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the lottery inside the db");
}

//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$state[]=array('Not Drawn','Not Drawn');
$state[]=array('Drawn','Drawn');

$ADMIN->html .= $SKIN->start_table( "Edit Lottery: {$Data['name']}" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("descr", $Data['descr'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ticket Cost</b>" ,
$SKIN->form_input("tcost", $Data['tcost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Date To Be Drawn<br>(Eg: 2nd June <font color='red'>[Not Auto]</font>)</b>" ,
$SKIN->form_input("date", $Data['date'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>State</b>" ,
$SKIN->form_dropdown('state', $state,"",""),
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 1</b>" ,
$SKIN->form_input("n1", $Data['n1'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 2</b>" ,
$SKIN->form_input("n2", $Data['n2'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 3</b>" ,
$SKIN->form_input("n3", $Data['n3'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 4</b>" ,
$SKIN->form_input("n4", $Data['n4'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 5</b>" ,
$SKIN->form_input("n5", $Data['n5'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Prize Money</b>" ,
$SKIN->form_input("prize", $Data['prize'] )
)      );
$ADMIN->html .= $SKIN->end_form('Update Lottery');
 
$ADMIN->html .= $SKIN->end_table();
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
						    'name'     => $IN['name'],
						    'state'  => $IN['state'],
						    'descr'     => $IN['descr'],
						    'tcost'      => $IN['tcost'],
						    'n1'      => $IN['n1'],
						    'n2'      => $IN['n2'],
						    'n3'      => $IN['n3'],
						    'n4'      => $IN['n4'],
						    'n5'      => $IN['n5'],
						    'prize'      => $IN['prize'],
						    'date'      => $IN['date'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_lottery` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_lottery");
$ADMIN->done_screen("Lottery Updated", "Lottery Management", "act=lottery" );
}

function delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'dodelete'  ),
2 => array( 'act'  , 'lottery'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_lottery WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the lottery inside the db");
}

$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Lottery to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove This Lottery");	 
$ADMIN->html .= $SKIN->end_table();

}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("SELECT * FROM ibf_lottery WHERE id='".$IN['id']."'");
$mGrab = $DB->fetch_row();
// remove tickets from members
$DB->query("DELETE FROM ibf_tickets WHERE lname='".$mGrab['name']."'");

$DB->query("DELETE FROM ibf_lottery WHERE id='{$mGrab['id']}'");
$DB->query("OPTIMIZE TABLE ibf_lottery");

$ADMIN->done_screen("Lottery Removed", "Lottery Management", "act=lottery" );
}

function add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'doadd'  ),
2 => array( 'act'  , 'lottery'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$state[]=array('Not Drawn','Not Drawn');
$state[]=array('Drawn','Drawn');

$ADMIN->html .= $SKIN->start_table( "Add Lottery" );

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Name</b>" ,
$SKIN->form_input("name", $Data['name'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("descr", $Data['descr'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ticket Cost</b>" ,
$SKIN->form_input("tcost", $Data['tcost'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Date To Be Drawn<br>(Eg: 2nd June <font color='red'>[Not Auto]</font>)</b>" ,
$SKIN->form_input("date", $Data['date'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>State</b>" ,
$SKIN->form_dropdown('state', $state,"",""),
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 1</b>" ,
$SKIN->form_input("n1", $Data['n1'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 2</b>" ,
$SKIN->form_input("n2", $Data['n2'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 3</b>" ,
$SKIN->form_input("n3", $Data['n3'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 4</b>" ,
$SKIN->form_input("n4", $Data['n4'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Ball Number (between 1-40) 5</b>" ,
$SKIN->form_input("n5", $Data['n5'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Prize Money</b>" ,
$SKIN->form_input("prize", $Data['prize'] )
)      );

$ADMIN->html .= $SKIN->end_form('Add Lottery');
 
$ADMIN->html .= $SKIN->end_table();

}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$DB->query("INSERT INTO `ibf_lottery` VALUES ('','{$IN['prize']}','{$IN['n1']}','{$IN['n2']}','{$IN['n3']}','{$IN['n4']}','{$IN['n5']}','{$IN['tcost']}','{$IN['state']}','{$IN['name']}','{$IN['descr']}','{$IN['date']}')  ");
$DB->query("OPTIMIZE TABLE ibf_lottery");
$ADMIN->done_screen("Lottery Added", "Lottery Management", "act=lottery" );

}

// Class end
}
?>