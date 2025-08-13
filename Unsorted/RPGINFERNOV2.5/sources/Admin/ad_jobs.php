<?php

$idx = new ad_jobs();


class ad_jobs {
function ad_jobs() {
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$ADMIN->page_title = "Manage Jobs";
$ADMIN->page_detail = "";

switch($IN['code']){
case 'editjob':
$this->edit_job();
break;

case 'viewjob':
$this->view_job();
break;

case 'addjob':
$this->add_job();
break;

case 'deletejob':
$this->delete_job();
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
$this->view_job();
break;

}
$ADMIN->output();
}


function view_job(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$SKIN->td_header[] = array( "Icon"    , "10%" );
$SKIN->td_header[] = array( "Name" , "20%" );
$SKIN->td_header[] = array( "Description"       , "25%" );
$SKIN->td_header[] = array( "Salary"         , "10%" );
$SKIN->td_header[] = array( "Required Level"         , "15%" );
$SKIN->td_header[] = array( "Paid every"         , "10%" );
$SKIN->td_header[] = array( "Edit"         , "5%" );
$SKIN->td_header[] = array( "Delete"         , "5%" );

// ----------------
$ADMIN->html .= $SKIN->start_table( "Manage Jobs" );

$interval[]=array('3600','Every Hour');
$interval[]=array('86400','Every Day');
$interval[]=array('172800','Every Two Days');
$interval[]=array('604800','Every Week');
$interval[]=array('1209600','Every Two Weeks');
$interval[]=array('2419200','Every Month');
$DB->query("SELECT * FROM ibf_infernojobs");
while ($data = $DB->fetch_row() ) {




for($i=0;$i<6;$i++){
if($data['sinterval']==$interval[$i][0]){
$data['sinterval']=$interval[$i][1];
}}

$ADMIN->html .= $SKIN->add_td_row( array(
"<center><img src='{$INFO['html_url']}/Inferno/jobs/{$data['icon']}'></center>" ,
"<center><b>{$data['name']}</b></center>",
"<center>{$data['desc']}</center>",
"<center>{$data['salary']}</center>",
"<center>{$data['lvl']}</center>",
"<center>{$data['sinterval']}</center>",
"<center><a href='{$ADMIN->base_url}&act=jobs&code=editjob&id={$data['id']}'>Edit</a></center>",
"<center><a href='{$ADMIN->base_url}&act=jobs&code=deletejob&id={$data['id']}'>Delete</a></center>",
)      );

}

$ADMIN->html .= $SKIN->end_table();
}


function delete_job(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
// -------------
$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_delete'  ),
2 => array( 'act'  , 'jobs'     ),
3 => array( 'id'    , $IN['id']   ),
)      );

$DB->query("SELECT * FROM ibf_infernojobs WHERE id='".$IN['id']."'");

if ( ! $mGrab = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the job inside the db");
}


$ADMIN->html .= $SKIN->start_table( "Removal Confirmation" );
$ADMIN->html .= $SKIN->add_td_row( array(
"<b>Job to Remove</b>" ,
"<b>{$mGrab['name']}</b>",
)      );

$ADMIN->html .= $SKIN->end_form("Remove this Job");	 
$ADMIN->html .= $SKIN->end_table();



}

function do_delete(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$DB->query("DELETE FROM ibf_infernojobs WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernojobs");

// unfortunatly, anyone who has this job doesn't any more =(
$DB->query("update ibf_members set job='' where job='".$IN['id']."'");
$ADMIN->done_screen("Job Removed", "Job Control", "act=jobs" );
}

function add_job(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_add'  ),
2 => array( 'act'  , 'jobs'     ),
3 => array( 'id'    , $IN['id']   ),
)  );

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Add Job" );

//Get the job icons
$handle = opendir($INFO['html_dir'] . "/Inferno/jobs/");
$icons[] = array('blank.gif', 'Select A Job Icon');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$interval[]=array('','Select A Time Interval');
$interval[]=array('3600','Every Hour');
$interval[]=array('86400','Every Day');
$interval[]=array('172800','Every Two Days');
$interval[]=array('604800','Every Week');
$interval[]=array('1209600','Every Two Weeks');
$interval[]=array('2419200','Every Month');


$ADMIN->html .= $SKIN->add_td_row( array( "<b>Job Name</b><br>" ,
$SKIN->form_input("name", "" )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showjob'].src = '{$INFO['html_url']}/Inferno/jobs/' + document.theAdminForm.icon.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Job Icon</b>" ,
$SKIN->form_dropdown('icon', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/jobs/blank.gif' name='showjob' border='0'>",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Salary</b>" ,
$SKIN->form_input("salary", "" )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Paid Every :</b>" ,
$SKIN->form_dropdown('sinterval', $interval,"","")
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvl", "" )
)      );


$ADMIN->html .= $SKIN->end_form('Add Job');
 
$ADMIN->html .= $SKIN->end_table();


}

function do_add(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
$time=time();
$DB->query("INSERT INTO `ibf_infernojobs` VALUES ('','{$IN['name']}','{$IN['icon']}','{$IN['desc']}','{$IN['salary']}','{$IN['sinterval']}','{$time}','{$IN['lvl']}')");
$DB->query("OPTIMIZE TABLE ibf_infernoshop");
$ADMIN->done_screen("Job Added", "Job Control", "act=jobs" );
}

function do_edit(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

$db_string = array( 
						    'id'     => $IN['id'],
                                                                                                    'name' => $IN['name'],
						    'icon'  => $IN['icon'],
						    'lvl'  => $IN['lvl'],
						    '`desc`'     => $IN['desc'],
						    'salary'      => $IN['salary'],
                                                                                                    'sinterval' => $IN['sinterval'],
		);
$rstring = $DB->compile_db_update_string( $db_string );

$DB->query("UPDATE `ibf_infernojobs` SET $rstring WHERE id='".$IN['id']."'");
$DB->query("OPTIMIZE TABLE ibf_infernojobs");
$ADMIN->done_screen("Job Updated", "Job Control", "act=jobs" );
}

function edit_job(){
global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;


$ADMIN->html .= $SKIN->start_form( array(
1 => array( 'code'  , 'do_edit'  ),
2 => array( 'act'  , 'jobs'     ),
3 => array( 'id'    , $IN['id']   ),
)  );


$DB->query("SELECT * FROM ibf_infernojobs WHERE id='".$IN['id']."'");

if ( ! $data = $DB->fetch_row() ){
	$ADMIN->error("Unable to find the job inside the db");
}

	
//+-------------------------------

$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
$SKIN->td_header[] = array( "&nbsp;"  , "60%" );

//+-------------------------------

$ADMIN->html .= $SKIN->start_table( "Edit Job" );

//Get the job icons
$handle = opendir($INFO['html_dir'] . "/Inferno/jobs/");
$icons[] = array('blank.gif', 'Select A Job Icon');
while ($icon = readdir($handle)) {
if(preg_match("/(.jpg|.gif|.png|.bmp)/",$icon)) {
if($icon != '.' || $icon  != '..') {
$icons[] = array($icon, $icon);
}}}

$interval[]=array('','Select A Time Interval');
$interval[]=array('3600','Every Hour');
$interval[]=array('86400','Every Day');
$interval[]=array('172800','Every Two Days');
$interval[]=array('604800','Every Week');
$interval[]=array('1209600','Every Two Weeks');
$interval[]=array('2419200','Every Month');

$ADMIN->html .= $SKIN->add_td_row( array( "<b>Job Name</b><br>" ,
$SKIN->form_input("name", $data['name'] )
)      );
$ADMIN->html .= "<script>
function show_icon() {
document.images['showjob'].src = '{$INFO['html_url']}/Inferno/jobs/' + document.theAdminForm.icon.value
}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Job Icon</b>" ,
$SKIN->form_dropdown('icon', $icons,"","onChange='show_icon()'") . "&nbsp;<img src='{$INFO['html_url']}/Inferno/jobs/blank.gif' name='showjob' border='0'>
<script>
x=document.theAdminForm.icon.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$data['icon']}'){
x[u].selected=true;
document.images['showjob'].src = '{$INFO['html_url']}/Inferno/jobs/' + document.theAdminForm.icon.value
}}
</script>
",
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Description</b>" ,
$SKIN->form_input("desc", $data['desc'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Salary</b>" ,
$SKIN->form_input("salary", $data['salary'] )
)      );
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Paid Every :</b>" ,
$SKIN->form_dropdown('sinterval',$interval ,"","") 
)      );
$ADMIN->html .="<script>
x=document.theAdminForm.sinterval.options
for(u=0;u<x.length;u++){
if(x[u].value=='{$data['sinterval']}'){
x[u].selected=true;
}}
</script>";
$ADMIN->html .= $SKIN->add_td_row( array( "<b>Level Requirement</b>" ,
$SKIN->form_input("lvl", $data['lvl'] )
)      );

$ADMIN->html .= $SKIN->end_form('Update Job');
 
$ADMIN->html .= $SKIN->end_table();


}


// end class
}
?>