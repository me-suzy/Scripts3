<?php
// @@@ Inferno RPG Store - Version 2 @@@
// @@@ Change User Name
class item {

var $name="Change Username";
var $descr="Change your current username";

function UseItem($item){
global $DB, $ibforums, $std, $print;
// item
$DB->query("SELECT * FROM ibf_infernostore where id='{$item['id']}'");
$itemx=$DB->fetch_row();
// get current data
$DB->query("SELECT * FROM ibf_members where id='{$ibforums->member['id']}'");
$member=$DB->fetch_row();
if($member['money'] < $itemx['cost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'need_morem' ) );
}
// item
$DB->query("SELECT name FROM ibf_members where LOWER(name)='{$ibforums->input['username']}' LIMIT 1");
if($DB->get_num_rows() == 1) {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'username_taken' ) );
}
$DB->query("update ibf_members set money=money-'{$itemx['cost']}' where id='{$ibforums->member['id']}'");
$DB->query("update ibf_members set name='{$ibforums->input['username']}' where id='{$ibforums->member['id']}'");
$DB->query("update ibf_contacts set contact_name='{$ibforums->input['username']}' where contact_id='{$ibforums->member['id']}'");
$DB->query("update ibf_forums set last_poster_name='{$ibforums->input['username']}' where last_poster_id='{$ibforums->member['id']}'");
$DB->query("update ibf_moderator_logs set member_name='{$ibforums->input['username']}' where member_id='{$ibforums->member['id']}'");
$DB->query("update ibf_moderators set member_name='{$ibforums->input['username']}' where member_id='{$ibforums->member['id']}'");
$DB->query("update ibf_posts set author_name='{$ibforums->input['username']}' where author_id='{$ibforums->member['id']}'");
$DB->query("update ibf_sessions set member_name='{$ibforums->input['username']}' where member_id='{$ibforums->member['id']}'");
$DB->query("update ibf_topics set starter_name='{$ibforums->input['username']}' where starter_id='{$ibforums->member['id']}'");
$DB->query("update ibf_topics set last_poster_name='{$ibforums->input['username']}' where last_poster_id='{$ibforums->member['id']}'");
// end item
$print->redirect_screen("Username Changed", "act=RPG&CODE=Store",0);
}

function display(){
global $DB, $ibforums, $std, $print;
return <<<EOF
<div class="tableborder">
<form action="?act=RPG&CODE=do_StoreBuy" method="post">
<input type='hidden' name='id' value='{$ibforums->input['id']}'>
<div class=maintitle><b>{$ibforums->vars['board_name']} RPG Store</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Use Item</td>
</tr>
<tr>
<td class='row2' width='30%'>Insert New Username (You will use this to loggin from now on): 
<input type='text' name='username'>
</td>
</tr>
<tr>
<td class='row2' width='30%' align='center'>
<input type='submit' name='submit' value='Use Item'>
</td>
</tr>
</table></div></form>
EOF;
}

}
?>