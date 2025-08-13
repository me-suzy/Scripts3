<?php
class skin_Scan {

function ScanTop($scan) {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$scan['name']}'s RPG Statistics & Profile</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='25%'>Character</td>
<td class='titlemedium' align='center'>Statistics</td>
</tr>
<td class='row4' align='center' width='20%' valign='top'>
EOF;
}

function MiniProf($scan,$RPav){
global $ibforums;
return <<<EOF
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
<td class='titlemedium' align='center' colspan='2'><b>{$scan['rpgname']}</b></td>
</tr>
<tr>
<td class='row2'><b>Race:</b></td>
<td class='row2'>{$scan['rpgrace']}</td>
</tr>
<tr>
<td class='row2' align='center' colspan='2'>{$RPav}</td>
</tr>
<tr>
<td class='row2'><b>Job:</b></td>
<td class='row2'>{$scan['job']}</td>
</tr>
<tr>
<td class='row2'><b>Victory's:</b></td>
<td class='row2'>{$scan['vics']}</td>
</tr>
<tr>
<td class='row2'><b>Deaths:</b></td>
<td class='row2'>{$scan['loss']}</td>
</tr>
<tr>
<td class='row2'><b>Level:</b></td>
<td class='row2'>{$scan['level']}</td>
</tr>
<tr>
<td class='row2'><b>Exp:</b></td>
<td class='row2' width='100' style='background-color:#8A8811;' align='left'><img src='html/Inferno/bars/bar_yellow.gif' width='{$scan['exp']}%' alt='{$scan['exp']}% Experience' height='13' style='position:absolute;z-index:2'></div><div style='position:relative;z-index:4;color:000000;' align='center'><b><font color='#000000'>{$scan['exp']}</font></b></td></div>
</tr>
<tr>
<td class='row2' nowrap><b>Elemental Type:</b></td>
<td class='row2'>{$scan['rpgelement']}</td>
</tr>
<tr>
<td class='row2' nowrap><b>Special Move:</b></td>
<td class='row2'>{$scan['smove']}</td>
</tr>
<tr>
<td class='row2' nowrap><b>RPG Gender:</b></td>
<td class='row2'>{$scan['rpgsex']}</td>
</tr>
<tr>
<td class='row2' nowrap><b>In Clan:</b></td>
<td class='row2'>{$scan['inclan']}</td>
</tr>

</table>
<!-- End Table -->
</td>
EOF;
}

function ScanMain1($scan){
global $ibforums;
return <<<EOF
<td class='row4' align='center' valign='top'>
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
<td class='titlemedium' align='center' colspan='3' width='1%'>{$scan['name']}'s Inventory</td></tr>
<tr>
<td class='row2' align='center' width='10%'><b>Icon</b></td>
<td class='row2' align='center' wdith='80%'><b>Name</b></td>
<td class='row2' align='center' width='10%'><b>Scan</b></td>
</tr>
EOF;
}

function HealTop($scan){
global $ibforums;
return <<<EOF
</table></div><br>
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
<td class='titlemedium' align='center' colspan='4' width='1%'>{$scan['name']}'s Healing Potions</td></tr>
<tr>
<td class='row2' align='center' width='10%'><b>Icon</b></td>
<td class='row2' align='center' wdith='80%'><b>Name</b></td>
<td class='row2' align='center' width='5%'><b>+HP</b></td>
<td class='row2' align='center' width='5%'><b>+MP</b></td>
</tr>
EOF;
}

function EquipTop($scan){
global $ibforums;
return <<<EOF
</table></div><br>
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
<td class='titlemedium' align='center' colspan='4' width='1%'>{$scan['name']}'s Equiped Items</td></tr>
<tr>
<td class='row2' align='center' width='10%'><b>Icon</b></td>
<td class='row2' align='center' wdith='80%'><b>Name</b></td>
<td class='row2' align='center' width='10%'><b>Scan</b></td>
</tr>
EOF;
}

function InvRowH($item){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='10%'><img src='html/Inferno/heal/{$item['img']}' alt='{$item['name']}' border='0'></td>
<td class='row2' align='center' width='80%'>{$item['name']}</td>
<td class='row2' align='center' width='5%'>{$item['hp']}</td>
<td class='row2' align='center' width='5%'>{$item['mp']}</td>
</tr>
EOF;
}

function InvRow($item){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='10%'><img src='html/Inferno/items/{$item['img']}' alt='{$item['name']}' border='0'></td>
<td class='row2' align='center' width='80%'>{$item['name']}</td>
<td class='row2' align='center' width='10%'><a href='?act=RPG&CODE=scan&id={$item['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$item ['name']}' border='0'></a></td>
</tr>
EOF;
}

function InvRowE($item){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='10%'><img src='html/Inferno/items/{$item['img']}' alt='{$item['name']}' border='0'></td>
<td class='row2' align='center' width='80%'>{$item['name']}</td>
<td class='row2' align='center' width='10%'><a href='?act=RPG&CODE=scan&id={$item['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$item ['name']}' border='0'></a></td>
</tr>
EOF;
}

function InvRowU($item){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='10%'><img src='{$item['img']}' alt='{$item['name']}' border='0'></td>
<td class='row2' align='center' width='80%' colspan='2'>{$item['name']}</td>
</tr>
EOF;
}

function InvEnd($scan){
global $ibforums;
return <<<EOF
</table></div><br>
<!-- Show Fancy Stats -->
<div class='tableborder' align='center'><table border='0' cellpadding='1' cellspacing='1' width='100%'><tr><td class='row2' align='center'><b><font size=1>HP:</font></td><div><td class='row2' width='100' style='background-color:#AD0023;' align='left'><img src='html/Inferno/bars/bar_red.gif' width='{$scan['hpd']}' height='13' style='position:absolute;z-index:2'></div><div style='position:relative;z-index:4;color:000000;' align='center'><b>{$scan['hp']}/{$scan['hpm']}</b></td></div>
<div><td class='row2' align='center'><b><font size=1>MP:</font></td><td class='row2' width='100' style='background-color:#004467;' align='left'><img src='html/Inferno/bars/bar_blue.gif' width='{$scan['mpd']}' height='13' style='position:absolute;z-index:2'></div><div style='position:relative;z-index:4;color:000000;' align='center'><b>{$scan['mp']}/{$scan['mpm']}</b></td></div>
<div><td class='row2' align='center'><b><font size=1>STR:</font></td><td class='row2' width='100' style='background-color:#7425BE;' align='left'><img src='html/Inferno/bars/bar_purple.gif' height='13' style='position:absolute;z-index:2' width='{$scan['strd']}%'></div><div style='position:relative;z-index:4;color:000000;' align='center'><b>{$scan['str']}</b></td></div>
<div><td class='row2' align='center'><b><font size=1>DEF:</font></td><td class='row2' width='100' style='background-color:#226722;' align='left'><img src='html/Inferno/bars/bar_green.gif' height='13' style='position:absolute;z-index:2' width='{$scan['defd']}%'></div><div style='position:relative;z-index:4;color:000000;' align='center'><b>{$scan['def']}</b></td></div></tr></table></div>
<!-- Stat End -->
EOF;
}

function ScanMain2(){
global $ibforums;
return <<<EOF
</td></tr>
EOF;
}
function ScanBottom($scan) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='2' class='row4'>{$scan['name']} has {$scan['money']} {$ibforums->lang['money']} & {$scan['bankmoney']} {$ibforums->lang['money']} is in there Bank Account</td>
</tr>
</table>

</div>
EOF;
}

function Copyright() {
global $ibforums;
return <<<EOF
<br/>
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row4' align='center' width='100%'>Running RPG Inferno Version 2 By <a href='http://gzevo.net/forum'>Zero Tolerance</a></td>
</tr>
</table>
</div>
EOF;
}
// End class
}
?>