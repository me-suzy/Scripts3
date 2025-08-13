<?php
// @@@ Inferno RPG Store - Version 2 @@@
// @@@ +50 To def
class item {

var $name="Increase DEF";
var $descr="Increase's Current DEF By 50";

function UseItem($item){
global $DB, $ibforums, $std, $print;
// item
$DB->query("SELECT * FROM ibf_infernostore where id='{$item['id']}'");
$itemx=$DB->fetch_row();
// get current def
$DB->query("SELECT * FROM ibf_members where id='{$ibforums->member['id']}'");
$member=$DB->fetch_row();
if($member['money'] < $itemx['cost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'need_morem' ) );
}
$member['def']=($member['def']+50);
$DB->query("update ibf_members set def='{$member['def']}',money=money-'{$itemx['cost']}' where id='{$ibforums->member['id']}'");
$DB->query("update ibf_infernostore set stock=stock-'1' where id='{$item['id']}'");
$print->redirect_screen("You now have ".$member['def']." DEF", "act=RPG&CODE=Store",0);
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
<td class='row2' width='30%'>
<input type='submit' name='submit' value='Use Item'>
</td>
</tr></table></div></form>
EOF;
}

}
?>