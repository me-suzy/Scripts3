<?php

class skin_RPG {

function EquipTop() {
global $ibforums;
return <<<EOF
<div class=tableborder>
<div class=maintitle><b>Equip Items</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' colspan='5'>Choose A Catagory To Equip From</td>
</tr>
EOF;
}

function EquipTTop() {
global $ibforums;
return <<<EOF
<div class=tableborder>
<div class=maintitle><b>Equip Items</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' colspan='5'>Choose Item To Equip</td>
</tr>
EOF;
}

function EquipTRow($r) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center'><a href='?act=RPG&CODE=doEquip&id={$r['id']}'>Equip {$r['name']}</a></td>
</tr>
EOF;
}

function EquipCatRow($r) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' colspan='5'><a href='?act=RPG&CODE=EquipB&type={$r['cid']}'>Equip {$r['cname']}</a></td>
</tr>
EOF;
}
function EquipMiddleRow() {
global $ibforums;
return <<<EOF
<tr>
<td class='titlemedium' align='center' colspan='5'>Currently Equipped Items</td>
</tr>
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center' width='15%'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
<td class='titlemedium' align='center' width='10%'>Unequip</td>
</tr>
EOF;
}

function EquipRow($r) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/items/{$r['img']}' alt='{$r['name']}'></td>
<td class='row2' align='center' width='15%'><b>{$r['name']}</b></td>
<td class='row2' align='center'>{$r['desc']}</td>
<td class='row2' align='center' width='1%'><a href='?act=RPG&CODE=scan&id={$r['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$r['name']}'></a></td>
<td class='row2' align='center'><a href='?act=RPG&CODE=doUnEquip&id={$r['id']}'>Unequip</a></td>
</tr>
EOF;
}

function EquipEnd() {
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function OurJobTop() {
global $ibforums;
return <<<EOF
<div class=tableborder>
<div class=maintitle><b>Your Job</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center'>Paid Every</td>
<td class='titlemedium' align='center' width='1%'>Salary</td>
<td class='titlemedium' align='center'>Leave Job</td>
</tr>
EOF;
}


function OurJob($job) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/jobs/{$job['icon']}' alt='{$job['name']}'></td>
<td class='row2' align='center'>{$job['name']}</td>
<td class='row2' align='center'>{$job['desc']}</td>
<td class='row2' align='center'>{$job['sinterval']}</td>
<td class='row2' align='center' width='1%'>{$job['salary']}</td>
<td class='row2' align='center'><a href='?act=RPG&CODE=LeaveJob&id={$job['id']}'>Leave Job</a></td>
</tr>
EOF;
}

function ViewJobTop() {
global $ibforums;
return <<<EOF
<div class=tableborder>
<div class=maintitle><b>Available Jobs</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center'>Paid Every</td>
<td class='titlemedium' align='center'>Required Level</td>
<td class='titlemedium' align='center' width='1%'>Salary</td>
<td class='titlemedium' align='center'>Pick Job</td>
</tr>
EOF;
}


function ViewJob($data) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/jobs/{$data['icon']}' alt='{$data['name']}'></td>
<td class='row2' align='center'>{$data['name']}</td>
<td class='row2' align='center'>{$data['desc']}</td>
<td class='row2' align='center'>{$data['sinterval']}</td>
<td class='row2' align='center'>{$data['lvl']}</td>
<td class='row2' align='center' width='1%'>{$data['salary']}</td>
<td class='row2' align='center'><a href=?act=RPG&CODE=PickJob&id={$data['id']}>Pick Job</a></td>
</tr>
EOF;
}

function ViewJobBottom($data) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='8' class='row4'><a href=?act=RPG&CODE=CollectSalary&id={$data['id']}>Collect your salary</a></td>
</tr>
</table>

</div>
<br />
EOF;
}

function OurJobBottom() {
global $ibforums;
return <<<EOF
<tr>
<td colspan='7' class='row4'>&nbsp;</td>
</tr>
</table>

</div>
<br>
EOF;
}

function HelpTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>RPG Help Topics</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='20%'>Name</td>
<td class='titlemedium' align='center' width='80%'>Short Description</td>
</tr>
EOF;
}

function HelpRow($r){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='20%'><a href='?act=RPG&CODE=ViewHelp&id={$r['id']}'><b>{$r['name']}</b></a></td>
<td class='row2' align='center' width='80%'>{$r['descr']}</td>
</tr>
EOF;
}

function HelpBottom(){
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function HelpContent($r){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>RPG Help Topics</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='100%'>{$r['name']}</td>
</tr>
<tr>
<td class='row2' width='100%'>{$r['content']}</td>
</tr>
</table></div>
EOF;
}

function StoreTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$ibforums->vars['board_name']} RPG Store</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center' width='10%'>Name</td>
<td class='titlemedium' align='center' width='60%'>Description</td>
<td class='titlemedium' align='center' width='10%'>Stock</td>
<td class='titlemedium' align='center' width='10%'>Buy</td>
</tr>
EOF;
}

function StoreRow($r){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/store/{$r['img']}' alt='{$r['name']}'></td>
<td class='row2' align='center' width='10%'><b>{$r['name']}</b></td>
<td class='row2' align='center' width='60%'>{$r['descr']}</td>
<td class='row2' align='center' width='10%'>{$r['stock']}</td>
<td class='row2' align='center' width='10%'>{$r['cost']}<br />
<a href='?act=RPG&CODE=StoreBuy&id={$r['id']}'>Buy</a></td>
</tr>
EOF;
}

function StoreBottom(){
return <<<EOF
</tr></table></div>
EOF;
}

function BuyTicket($r){
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Buy Lottery Ticket</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<form action="?act=RPG&CODE=do_BuyTick" method="post">
<input type='hidden' name='id' value='{$r['id']}'>
<td class='titlemedium' align='center' width='100%' colspan='2'>Input Data</td>
</tr>
<tr>
<td class='row2' width='30%'>Input Number 1 (1-40):</td>
<td class='pformright'><input type='text' name='n1'></td>
</tr>
<tr>
<td class='row2' width='30%'>Input Number 2 (1-40):</td>
<td class='pformright'><input type='text' name='n2'></td>
</tr>
<tr>
<td class='row2' width='30%'>Input Number 3 (1-40):</td>
<td class='pformright'><input type='text' name='n3'></td>
</tr>
<tr>
<td class='row2' width='30%'>Input Number 4 (1-40):</td>
<td class='pformright'><input type='text' name='n4'></td>
</tr>
<tr>
<td class='row2' width='30%'>Input Number 5 (1-40):</td>
<td class='pformright'><input type='text' name='n5'></td>
</tr>
<tr>
<td class='row2' colspan='2' align='center'><input type='submit' value='Buy Ticket'></td>
</tr>
<tr>
<td class='row2' colspan='2'>Note: Any fields left blank, or numbers are not between limits shown, expect to loose.</td>
</tr>
</table></div></form>
EOF;
}

function LT() {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Lottery</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='5%'>Buy</td>
<td class='titlemedium' align='center' width='10%'>State</td>
<td class='titlemedium' align='center' width='15%'>Check</td>
<td class='titlemedium' align='center' width='1%'>Prize</td>
<td class='titlemedium' align='center' width='20%'>Date Drawn</td>
</tr>
EOF;
}

function LR($r) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center'><b>{$r['name']}</b></td>
<td class='row2'><center>{$r['descr']}</center>
</td>
<td class='row2' width='5%' align='center'><a href='?act=RPG&CODE=BuyTick&id={$r['id']}'>Buy</a>
<br>{$r['tcost']}</td>
<td class='row2' align='center' width='10%'><font color='red'>{$r['state']}</font></td>
<td class='row2' align='center' width='15%'><a href='?act=RPG&CODE=Check&id={$r['id']}'>Check Numbers</a></td>
<td class='row2' align='center' width='1%'><font color='green'>{$r['prize']}</font></td>
<td class='row2' align='center' width='20%'>{$r['date']}</td>
</tr>
EOF;
}

function LB(){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' colspan='8'>
You Must Score 80% Or Above To Win (4 matching balls or more)
</td>
</tr>
</table></div><br>
EOF;
}

function BLogT(){
global $ibforums;
return <<<EOF
<br>
<div class="tableborder">
<div class=maintitle align=center><b>Battle Log</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
EOF;
}

function BLogR($r){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='left'>{$r['attacker']} Attacked {$r['opponent']} with a total damage of {$r['dmg']} leaving {$r['opponent']} with {$r['hpl']} HP and {$r['mpl']} MP</td>
</tr>
EOF;
}

function BLogB(){
global $ibforums;
return <<<EOF
</table></div><br>
EOF;
}


function PIT() {
global $ibforums;
return <<<EOF
<br>
<div class="tableborder">
<div class=maintitle><b>10 Most Popular Items</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Rank</td>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>Cost</td>
<td class='titlemedium' align='center' width='1%'>Sold</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
</tr>
EOF;
}

function PIR($Data) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center'><b>{$Data['rankc']}</b></td>
<td class='row2' align='center' width='1%'><img src='html/Inferno/items/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'>{$Data['desc']}</td>
<td class='row2' align='center' width='1%'>{$Data['cost']}<br><a href=?act=RPG&CODE=buyitem&id={$Data['id']}>{$ibforums->lang['buy']}</a></td>
<td class='row2' align='center'>{$Data['sold']}</td>
<td class='row2' align='center' width='1%'><a href='?act=RPG&CODE=scan&id={$Data['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$Data['name']}' border='0'></a></td>
</tr>
EOF;
}

function PIB() {
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function RMT(){
global $ibforums;
return <<<EOF
<td width="50%">
<div class="tableborder">
<div class=maintitle><b>Top 10 Richest Members</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Rank</td>
<td class='titlemedium' align='center' width='60%'>Member</td>
<td class='titlemedium' align='center' width='40%'>{$ibforums->lang['money']}</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
</tr>
EOF;
}

function RMR($r){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><b>{$r['rankc']}</b></td>
<td class='row2' align='center' width='60%'><a href='?showuser={$r['id']}'>{$r['name']}</a></td>
<td class='row2' align='center' width='40%'>{$r['money']}</td>
<td class='row2' align='center' width='1%'><a href='?act=Scan&id={$r['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$r['name']}' border='0'></a></td>
</tr>
EOF;
}

function RMB(){
return <<<EOF
</table></div>
</td></tr></table>
EOF;
}

function NewestBattle($r){
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Newest Battle</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row2' align='center' width='10%'><a href='?act=RPG&CODE=ViewBattle&id={$r['newbattle']}'>
View Newest Battle
</a></td>
</tr>
</table></div>
EOF;
}

function TKT(){
return <<<EOF
<br>
<table width="100%"><tr><td width="50%">
<div class="tableborder">
<div class=maintitle><b>Top 10 Battle Winners</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Rank</td>
<td class='titlemedium' align='center' width='60%'>Member</td>
<td class='titlemedium' align='center' width='40%'>Number Of Wins</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
</tr>
EOF;
}

function TKR($r){
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><b>{$r['rankc']}</b></td>
<td class='row2' align='center' width='60%'><a href='?showuser={$r['id']}'>{$r['name']}</a></td>
<td class='row2' align='center' width='40%'>{$r['vics']}</td>
<td class='row2' align='center' width='1%'><a href='?act=Scan&id={$r['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$r['name']}' border='0'></a></td></tr>
EOF;
}

function TKB(){
return <<<EOF
</table></div></td>
EOF;
}

function vabt(){
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Viewing All Battles</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='10%'>ID</td>
<td class='titlemedium' align='center' width='90%'>Battle</td>
</tr>
EOF;
}


function vabr($battle,$players){
return <<<EOF
<tr>
<td class='row2' align='center' width='10%'>{$battle['id']}</td>
<td class='row2' align='center' width='90%'><a href='?act=RPG&CODE=ViewBattle&id={$battle['id']}'>
{$players}
</a></td>
</tr>
EOF;
}

function vabb(){
return <<<EOF
<tr>
<td class='row2' align='center' width='100%' colspan='2'><a href='?act=RPG&CODE=Battle'>Make Battle/Go To Your Current Battle</a></td>
</tr>
</table></div>
EOF;
}


function MakeBattle($scenes){
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Make Battle [Not In Battle]</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<form action="?act=RPG&CODE=MakeBattle" method="post">
<td class='titlemedium' align='center' width='100%' colspan='2'>Make A Battle</td>
</tr>
<tr>
<td class='row2' width='30%'>Battle Scene:</td>
<td class='pformright'><select name='scene'>{$scenes}</select></td>
</tr>
<tr>
<td class='row2' width='30%'>Opponent Member Name:</td>
<td class='pformright'><input type='text' name='p2'></td>
</tr>
<tr>
<td class='row2' colspan='2' align='center'><input type='submit' value='Make Battle'></td>
</tr>
</table></div></form>
EOF;
}

function Battle($Member,$Opponent,$battle){
return <<<EOF
<script>
function Reload(){
location.reload()
}
setTimeout("Reload()",20000)
function Go(To){
Sprite.innerHTML=Sprite.innerHTML+"<iframe src='"+To+"' width='0' height='0'></iframe>"
setTimeout("Reload()",7000)
}
function Attack(Obj){
if(document.check=="ok"){
Sprite.innerHTML="<img src='html/Inferno/sprite/"+Obj+"'>"
} else {
}}
function Summon(Obj){
if(document.check=="ok"){
Sprite.innerHTML="<img src='html/Inferno/summons/"+Obj+"'>"
} else {
}}
function Disable(){
Links=document.body.getElementsByTagName('a')
for(t=0;t<Links.length;t++){
if(Links[t].config=="attack"){
Links[t].disabled=true
Links[t].onclick='';
}}}
</script>
<div class="tableborder">
<div class=maintitle><b>Battle Ground</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='100%' colspan='2'>It is {$battle['wturn']}'s Turn</td>
</tr>
<tr>
<td class='row2' background='html/Inferno/scene/{$battle['background']}'>
<!-- Begin Outer Table For Battle Show -->
<table width='100%'><tr>
<!-- P1 Cell -->
<td align='left' valign='top' bgcolor='transparent' width='30%'>
<table><tr>
<td class='titlemedium'>{$Member['rpgname']}</td>
</tr>
<tr>
<td class='row2'>
<table><tr>
<td><b>HP:</b> {$Member['hp']}/{$Member['hpm']}<br>
<b>MP:</b> {$Member['mp']}/{$Member['mpm']}<br>
<b>DEF:</b> {$Member['def']}<br>
<b>STR:</b> {$Member['str']}
<br><b>Level: </b>{$Member['level']}
</td>
<td>{$Member['rpgav']}</td></tr><tr>
<td><b>EXP:</b> {$Member['exp']}% </td><td><img src='html/Inferno/bars/bar_blue.gif' alt='{$Member['exp']}% Experience' height='13' width='{$Member['exp']}'>
</td></tr><tr>
<td><b>Rage:</b> {$Member['rage']}% </td><td><img src='html/Inferno/bars/bar_red.gif' height='13' width='{$Member['rage']}'> {$Member['ragelink']}</td></tr><tr>
<td>{$Member['attack']}</td>
<td>{$Member['spell']}</td></tr><tr>
<td colspan='2'><b><a href='?act=RPG&CODE=Surrender&id={$battle['id']}'>Surrender!</a></b></td></tr><tr>
<td colspan='2'><b>Race:</b> {$Member['rpgrace']}</td></tr><tr>
<td colspan='2'><b>Special Move:</b> {$Member['smove']}</td></tr><tr>
<td><b>Deaths:</b> {$Member['loss']}</td></tr>
<td><b>Victory's:</b> {$Member['vics']}</td></tr></table>



</td>
</tr></table>
<!-- P1 Cell End--><br><br><br><br>
</td>

<!-- +------------------------------+ -->
<td align='center'><div id='Sprite'></div>
<!-- +------------------------------+ -->

<!-- P2 Cell -->
<td align='right' valign='top' bgcolor='transparent' width='30%'>
<table><tr>
<td class='titlemedium' align='right'>{$Opponent['rpgname']}</td>
</tr>
<tr>
<td class='row2'>
<table><tr>
<td>{$Opponent['rpgav']}</td>
<td><b>HP:</b> {$Opponent['hp']}/{$Opponent['hpm']}<br>
<b>MP:</b> {$Opponent['mp']}/{$Opponent['mpm']}<br>
<b>DEF:</b> {$Opponent['def']}<br>
<b>STR:</b> {$Opponent['str']}
<br><b>Level: </b>{$Opponent['level']}
</td></tr><tr>
<td><b>EXP:</b> {$Opponent['exp']}%</td><td> <img src='html/Inferno/bars/bar_blue.gif' alt='{$Opponent['exp']}% Experience' height='13' width='{$Opponent['exp']}'>
</td></tr><tr>
<td><b>Rage:</b> {$Opponent['rage']}% </td><td><img src='html/Inferno/bars/bar_red.gif' height='13' width='{$Opponent['rage']}'> {$Opponent['ragelink']}</td></tr><tr>
<td>{$Opponent['attack']}</td>
<td>{$Opponent['spell']}</td></tr><tr>
<td colspan='2'><b><a href='?act=RPG&CODE=Surrender&id={$battle['id']}'>Surrender!</a></b></td></tr><tr>
<td colspan='2'><b>Race:</b> {$Opponent['rpgrace']}</td></tr><tr>
<td colspan='2'><b>Special Move:</b> {$Opponent['smove']}</td></tr><tr>
<td><b>Deaths:</b> {$Opponent['loss']}</td></tr>
<td><b>Victory's:</b> {$Opponent['vics']}</td></tr></table>



</td>
</tr></table>
<!-- P2 Cell End--><br><br><br><br>
</td>

</tr></table>
<! -- End Outer Table -->
</td></tr></table></div>
EOF;
}

function vbr($battle,$Member,$Opponent){
return <<<EOF

<div class="tableborder">
<div class=maintitle><b>Battle Ground</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='100%' colspan='2'>[View Battle Mode]</td>
</tr>
<tr>
<td class='row2' background='html/Inferno/scene/{$battle['background']}'>
<!-- Begin Outer Table For Battle Show -->
<table width='100%'><tr>
<!-- P1 Cell -->
<td align='left' valign='top' bgcolor='transparent' width='30%'>
<table><tr>
<td class='titlemedium'>{$Member['rpgname']}</td>
</tr>
<tr>
<td class='row2'>
<table><tr>
<td><b>HP:</b> {$Member['hp']}/{$Member['hpm']}<br>
<b>MP:</b> {$Member['mp']}/{$Member['mpm']}<br>
<b>DEF:</b> {$Member['def']}<br>
<b>STR:</b> {$Member['str']}
<br><b>Level: </b>{$Member['level']}
</td>
<td>{$Member['rpgav']}</td></tr><tr>
<td><b>EXP:</b> {$Member['exp']}% </td><td><img src='html/Inferno/bars/bar_blue.gif' alt='{$Member['exp']}% Experience' height='13' width='{$Member['exp']}'>
</td></tr><tr>
<td><b>Rage:</b> {$Member['rage']}% </td><td><img src='html/Inferno/bars/bar_red.gif' height='13' width='{$Member['rage']}'> {$Member['ragelink']}</td></tr><tr>
<td>{$Member['attack']}</td>
<td>{$Member['spell']}</td></tr><tr>
<td colspan='2'><b>Race:</b> {$Member['rpgrace']}</td></tr><tr>
<td colspan='2'><b>Special Move:</b> {$Member['smove']}</td></tr><tr>
<td><b>Deaths:</b> {$Member['loss']}</td></tr>
<td><b>Victory's:</b> {$Member['vics']}</td></tr></table>



</td>
</tr></table>
<!-- P1 Cell End--><br><br><br><br>
</td>

<!-- +------------------------------+ -->

<!-- +------------------------------+ -->

<!-- P2 Cell -->
<td align='right' valign='top' bgcolor='transparent' width='30%'>
<table><tr>
<td class='titlemedium' align='right'>{$Opponent['rpgname']}</td>
</tr>
<tr>
<td class='row2'>
<table><tr>
<td>{$Opponent['rpgav']}</td>
<td><b>HP:</b> {$Opponent['hp']}/{$Opponent['hpm']}<br>
<b>MP:</b> {$Opponent['mp']}/{$Opponent['mpm']}<br>
<b>DEF:</b> {$Opponent['def']}<br>
<b>STR:</b> {$Opponent['str']}
<br><b>Level: </b>{$Opponent['level']}
</td></tr><tr>
<td><b>EXP:</b> {$Opponent['exp']}%</td><td> <img src='html/Inferno/bars/bar_blue.gif' alt='{$Opponent['exp']}% Experience' height='13' width='{$Opponent['exp']}'>
</td></tr><tr>
<td><b>Rage:</b> {$Opponent['rage']}% </td><td><img src='html/Inferno/bars/bar_red.gif' height='13' width='{$Opponent['rage']}'> {$Opponent['ragelink']}</td></tr><tr>
<td>{$Opponent['attack']}</td>
<td>{$Opponent['spell']}</td></tr><tr>
<td colspan='2'><b>Race:</b> {$Opponent['rpgrace']}</td></tr><tr>
<td colspan='2'><b>Special Move:</b> {$Opponent['smove']}</td></tr><tr>
<td><b>Deaths:</b> {$Opponent['loss']}</td></tr>
<td><b>Victory's:</b> {$Opponent['vics']}</td></tr></table>



</td>
</tr></table>
<!-- P2 Cell End--><br><br><br><br>
</td>

</tr></table>
<! -- End Outer Table -->
</td></tr></table></div>
EOF;
}

function VUserShopTop($data){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$data['name']}</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row2' align='center' colspan='8'>{$data['logo']}</td>
</tr>
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>+HP</td>
<td class='titlemedium' align='center' width='1%'>+MP</td>
<td class='titlemedium' align='center' width='1%'>+STR</td>
<td class='titlemedium' align='center' width='1%'>+DEF</td>
<td class='titlemedium' align='center' width='1%'>Cost</td>
</tr>
EOF;
}

function VUserShopRow($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='{$data['img']}' alt='{$data['name']}'></td>
<td class='row2' align='center'><b>{$data['name']}</b></td>
<td class='row2' align='center'>{$data['desc']}</td>
<td class='row2' align='center' width='1%'>{$data['hp']}</td>
<td class='row2' align='center' width='1%'>{$data['mp']}</td>
<td class='row2' align='center' width='1%'>{$data['str']}</td>
<td class='row2' align='center' width='1%'>{$data['def']}</td>
<td class='row2' align='center' width='1%'>{$data['cost']}<br><a href='{$ibforums->base_url}act=RPG&CODE=BuyUserItem&id={$data['id']}'>Buy</a></td>
</tr>
EOF;
}

function VUserShopBottom(){
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function ShopSettings($data){
global $ibforums;
return <<<EOF
<form action="{$ibforums->base_url}act=RPG&CODE=doshopsettings" method="post">
<div class="tableborder">
<div class=maintitle><b>Shop Settings</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' width='10%' align='center'>Shop Name</td>
<td class='titlemedium' width='50%' align='center'>Shop Description</td>
<td class='titlemedium' width='40%' align='center'>Shop Logo</td>
</tr>
<tr>
<td class='pformleft' width='50%' align='center'><input type="text" name="name" value="{$data['name']}"></td>
<td class='pformright' width='50%' align='center'><input type="text" name="desc" value="{$data['desc']}"></td>
<td class='pformright' width='50%' align='center'><input type="text" name="logo" value="{$data['logo']}"></td>
</tr>
<tr>
<td class='row2' width='100%' colspan='3' align='center'><input type='submit' name='submit' value='Update Settings'></td>
</tr>
</table></div>
</form>
EOF;
}

function AddItem($data){
global $ibforums;
return <<<EOF
<form action="{$ibforums->base_url}act=RPG&CODE=doitemadd" method="post">
<div class="tableborder">
<div class=maintitle><b>Add Item Into Your Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row2' width='50%'>Icon:</td>
<td class='pformright' width='50%'><input type="text" name="img" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>Name:</td>
<td class='pformright' width='50%'><input type="text" name="name" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>Description:</td>
<td class='pformright' width='50%'><input type="text" name="desc" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>Cost:</td>
<td class='pformright' width='50%'><input type="text" name="cost" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>HP:</td>
<td class='pformright' width='50%'><input type="text" name="hp" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>MP:</td>
<td class='pformright' width='50%'><input type="text" name="mp" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>STR:</td>
<td class='pformright' width='50%'><input type="text" name="str" value=""></td>
</tr>
<tr>
<td class='row2' width='50%'>DEF:</td>
<td class='pformright' width='50%'><input type="text" name="def" value=""></td>
</tr>
<tr>
<td class='row2' align='center' width='100%' colspan='2'><input type='submit' name='submit' value='Add Item'></td>
</tr></table></div>
</form>
EOF;
}

function EdItem($data){
global $ibforums;
return <<<EOF
<form action="{$ibforums->base_url}act=RPG&CODE=doitemed" method="post">
<input type='hidden' name='id' value='{$data['id']}'>
<div class="tableborder">
<div class=maintitle><b>Manage Items In Your Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row2' width='50%'>Icon: <img src='{$data['img']}'></td>
<td class='pformright' width='50%'><input type="text" name="img" value="{$data['img']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>Name: {$data['name']}</td>
<td class='pformright' width='50%'><input type="text" name="name" value="{$data['name']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>Description: {$data['desc']}</td>
<td class='pformright' width='50%'><input type="text" name="desc" value="{$data['desc']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>Cost: {$data['cost']}</td>
<td class='pformright' width='50%'><input type="text" name="cost" value="{$data['cost']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>HP: {$data['hp']}</td>
<td class='pformright' width='50%'><input type="text" name="hp" value="{$data['hp']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>MP: {$data['mp']}</td>
<td class='pformright' width='50%'><input type="text" name="mp" value="{$data['mp']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>STR: {$data['str']}</td>
<td class='pformright' width='50%'><input type="text" name="str" value="{$data['str']}"></td>
</tr>
<tr>
<td class='row2' width='50%'>DEF: {$data['def']}</td>
<td class='pformright' width='50%'><input type="text" name="def" value="{$data['def']}"></td>
</tr>
<tr>
<td class='row2' align='center' width='100%' colspan='2'><input type='submit' name='submit' value='Update Item'></td>
</tr></table></div>
</form>
EOF;
}

function ManageShopTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Manage Items In Your Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center' width='59%'>Name</td>
<td class='titlemedium' align='center' width='20%'>Edit</td>
<td class='titlemedium' align='center' width='20%'>Delete</td>
</tr>
EOF;
}

function ManageShopRow($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='{$data['img']}' alt='{$data['name']}'></td>
<td class='row2' align='center' width='59%'><b>{$data['name']}</b></td>
<td class='row2' align='center' width='20%'><a href='{$ibforums->base_url}act=RPG&CODE=ItemEd&id={$data['id']}'>Edit</a></td>
<td class='row2' align='center' width='20%'><a href='{$ibforums->base_url}act=RPG&CODE=ItemDel&id={$data['id']}'>Delete</a></td>
</tr>
EOF;
}

function ManageShopBottom(){
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function ShopBuy($options){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Buying A Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<form action="{$ibforums->base_url}act=RPG&CODE=doushopb" method="post">
<tr>
<td class='titlemedium' align='center' width='100%' colspan='2'>Input Shop Name</td>
</tr>
<tr>
<td class='row2' width='40%'>
Please Input A Shop Name
</td>
<td class='pformright'>
<input type="text" name="sname" value="">
</td>
<tr>
<td class='row2' colspan='2' align='center'><input type='submit' name='submit' value='Buy Shop For {$options['cost']} {$ibforums->lang['money']}'></td>
</tr>
</table></div>
EOF;
}

function UserShopTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Select A Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='30%'>Owner</td>
<td class='titlemedium' align='center' width='30%'>Shop Name</td>
<td class='titlemedium' align='center' width='40%'>Description</td>
</tr>
EOF;
}

function UserShopRow($shops){
global $ibforums;
return <<<EOF
<tr>
<td class='row2'><a href='{$ibforums->base_url}showuser={$shops['oid']}'>{$shops['oname']}</a></td>
<td class='row2'><b><a href='{$ibforums->base_url}act=RPG&CODE=ViewUShop&id={$shops['oid']}'>{$shops['name']}</a></b></td>
<td class='row2'>{$shops['desc']}</td>
</tr>
EOF;
}

function UserShopBottom(){
global $ibforums;
return <<<EOF
<tr>
<td class='row2'colspan='3' align='center'>
<a href='{$ibforums->base_url}act=RPG&CODE=BuyShop'>Buy A Shop</a> | 
<a href='{$ibforums->base_url}act=RPG&CODE=ManageShop'>Manage Items In Your Shop</a> | 
<a href='{$ibforums->base_url}act=RPG&CODE=AddShop&id={$ibforums->member['id']}'>Add Items To Your Shop</a> | 
<a href='{$ibforums->base_url}act=RPG&CODE=ShopSettings&id={$ibforums->member['id']}'>Shop Settings</a>
</td>
</tr>
</table></div>
EOF;
}


function ClanControlTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$clan} Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='100%' colspan='2'>Clan Members</td>
</tr>
EOF;
}

function ClanControlRow($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' width='50%'><a href='{$ibforums->base_url}showuser={$data['id']}'>{$data['name']}</a></td>
<td class='row2' width='50%' align='center'><a href='{$ibforums->base_url}act=RPG&CODE=doclanc&mid={$data['id']}'>Remove</a></td>
</tr>
EOF;
}

function ClanControlBottom(){
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function ClanViewTop($clan,$data){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$clan} Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='100%' colspan='4'>Clan Logo</td>
</tr>
<tr>
<td class='row2' colspan='4' align='center'>{$data['img']}</td>
</tr>
<tr>
<td class='titlemedium' align='center' width='50%'>Leader Name</td>
<td class='titlemedium' align='center' width='20%'>Leader Scan</td>
<td class='titlemedium' align='center' width='15%'>Leader Victories</td>
<td class='titlemedium' align='center' width='15%'>Leader Deaths</td>
</tr>
<tr>
<td class='row2' width='50%'><b><a href='{$ibforums->base_url}showuser={$data['leaderid']}'>{$data['leader']}</a></b></td>
<td class='row2' width='20%' align='center'><a href='?act=Scan&id={$data['leaderid']}'><img src='sources/Inferno/scan.gif' alt='Scan {$data['leader']}' border='0'></a></td>
<td class='row2' width='15%' align='center'>{$data['vics']}</td>
<td class='row2' width='15%' align='center'>{$data['loss']}</td>
</tr>
<tr>
<td class='titlemedium' align='center' width='50%'>Member Name</td>
<td class='titlemedium' align='center' width='20%'>Member Scan</td>
<td class='titlemedium' align='center' width='15%'>Member Victories</td>
<td class='titlemedium' align='center' width='15%'>Member Deaths</td>
</tr>
EOF;
}

function ClanViewRow($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' width='50%'><a href='{$ibforums->base_url}showuser={$data['id']}'>{$data['name']}</a></tr>
<td class='row2' width='20%' align='center'><a href='?act=Scan&id={$data['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$data['name']}' border='0'></a></tr>
<td class='row2' width='15%' align='center'>{$data['vics']}</td>
<td class='row2' width='15%' align='center'>{$data['loss']}</td>
</tr>
EOF;
}

function ClanViewBottom(){
global $ibforums;
return <<<EOF
</table></div>
EOF;
}

function InviteClan(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Invite A Member To Your Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<form action="{$ibforums->base_url}act=RPG&CODE=doclani" method="post">
<tr>
<td class='row2'><b>
Member Name
</b></td>
<td class='pformright'><b>
<input type="text" name="name" value="">
</b></td>
</tr>
<tr>
<td class='row2' colspan='2' align='center'><input type='submit' name='submit' value='Invite Member'></td>
</tr>
</form>
</table>
</div>
EOF;
}

function ManageClan($data){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Manage Your Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<form action="{$ibforums->base_url}act=RPG&CODE=doclanm" method="post">
<tr>
<td class='row2'><b>
Clan Name
</b></td>
<td class='pformright'><b>
<input type="text" name="name" value="{$data['name']}">
</b></td>
</tr>
<tr>
<td class='row2'><b>
Logo Url
</b></td>
<td class='pformright'><b>
<input type="text" name="img" value="{$data['img']}">
</b></td>
</tr>

<tr>
<td class='row2' colspan='2' align='center'><input type='submit' name='submit' value='Update Clan'></td>
</tr>
</form>
</table>
</div>
EOF;
}

function JoinClanTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Join A Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
EOF;
}

function JoinClanRowT($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2'>The Following Clan: {$data['name']} Lead By {$data['leader']} has invited you to join there clan.<br>
Do You Accept? <a href='{$ibforums->base_url}act=RPG&CODE=JoinY'>Yes</a> | <a href='{$ibforums->base_url}act=RPG&CODE=JoinN'>No</a>
</td>
</tr>
EOF;
}

function JoinClanRowF(){
global $ibforums;
return <<<EOF
<tr>
<td class='row2'>Sorry, you have not been invited to a clan by a clan leader
</td>
</tr>
EOF;
}

function JoinClanBottom(){
global $ibforums;
return <<<EOF
</table>
</div>
EOF;
}

function BuyClan($data){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Buy A Clan</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row2' align='center'><b>Buying a clan will cost you {$data['cost']} {$ibforums->lang['money']}. Enter Your Clan Name Below<br>The Rest You Can Edit Once You Have Bought The Clan</b></td>
</tr>
<form action="{$ibforums->base_url}act=RPG&CODE=doclanb" method="post">
<tr>
<td class="pformleft">Clan Name: <input type="text" name="cname" value=""></td>
</tr>
<tr>
<td class='pformleft' colspan='2' align='center'><input type="submit" name="submit" value="Buy Clan"></td>
</tr>
</table>
</div>
</form>
EOF;
}

function ClanTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Clans</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='40%'>Clan Name</td>
<td class='titlemedium' align='center' width='40%'>Leader</td>
<td class='titlemedium' align='center'>Total Clan Members</td>
</tr>
EOF;
}

function ClanRow($clan){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center'><a href='{$ibforums->base_url}act=RPG&CODE=ViewClan&clan={$clan['name']}'>{$clan['name']}</a></td>
<td class='row2' align='center'><a href='{$ibforums->base_url}showuser={$clan['leaderid']}'>{$clan['leader']}</a></td>
<td class='row2' align='center'>{$clan['totalm']}</td>
</tr>
EOF;
}

function ClanBottom(){
global $ibforums;
return <<<EOF
<tr>
<td colspan='3' class='row4' align='center'><a href='{$ibforums->base_url}act=RPG&CODE=BuyClan'>Buy A Clan</a> | <a href='{$ibforums->base_url}act=RPG&CODE=JoinClan'>Join A Clan</a> | <a href='{$ibforums->base_url}act=RPG&CODE=LeaveClan'>Leave Current Clan</a> | <a href='{$ibforums->base_url}act=RPG&CODE=ManageClan'>Manage Your Clan</a> | <a href='{$ibforums->base_url}act=RPG&CODE=InviteClan'>Invite Member To Your Clan</a> | <a href='{$ibforums->base_url}act=RPG&CODE=ClanControl'>Control Members In Your Clan</a></td>
</tr>
</table>
</div>
<br>
EOF;
}

function CatTop(){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$ibforums->vars['board_name']} Shops</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='50%'>Shop Name</td>
<td class='titlemedium' align='center'>Description</td>
</tr>
EOF;
}

function CatRow($data){
global $ibforums;
return <<<EOF
<tr>
<td class='row2' width='50%'><a href='{$ibforums->base_url}act=RPG&shop={$data['cid']}'>{$data['cname']}</a></td>
<td class='row2' align='center'>{$data['desc']}</td>
</tr>
EOF;
}

function CatBottom(){
global $ibforums;
return <<<EOF
<tr>
<td colspan='2' class='row4'>&nbsp;</td>
</tr>
</table>
</div>
<br>
EOF;
}

function Bank($member,$dd){
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$member['name']}'s Bank Account</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='65%'>Deposit</td>
<td class='titlemedium' align='center'>Stats</td>
</tr>
<!-- Deposit Form -->
<form action="{$ibforums->base_url}act=RPG&CODE=deposit" method="post">
<tr>
<td class='row2'><input type='text' name='deposit'> <input type='submit' value='Deposit {$ibforums->lang['money']}'></td>
<td class='row2'><b>Interest Recieved:</b> Daily<br>
<b>Interest Rate: </b>{$dd['intrest']}%
</td>
</tr>
</form>
<tr>
<td class='titlemedium' align='center' width='65%'>Withdraw</td>
<td class='titlemedium' align='center'>Member Stats</td>
</tr>
<form action="{$ibforums->base_url}act=RPG&CODE=withdraw" method="post">
<tr>
<td class='row2'><input type='text' name='withdraw'> <input type='submit' value='Withdraw {$ibforums->lang['money']}'></td>
<td class='row2'>
<b>Current {$ibforums->lang['money']} On Hand:</b> {$member['money']}<br>
<b>Current {$ibforums->lang['money']} In Bank:</b> {$member['bankmoney']}<br>
</td>
</tr>
</table>
</form>
</div>
EOF;
}

function ScanTop() {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$ibforums->vars['board_name']} Itemshop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>To Player Stats</td>
<td class='titlemedium' align='center' width='1%'>Cost</td>
</tr>
EOF;
}

function InvenTop() {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Your Inventory</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>Sell</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
</tr>
EOF;
}

function HealInvTop() {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Your Inventory</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>+HP</td>
<td class='titlemedium' align='center' width='1%'>+MP</td>
<td class='titlemedium' align='center' width='1%'>Sell</td>
<td class='titlemedium' align='center' width='1%'>Use</td>
</tr>
EOF;
}

function ShopTop($id) {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$ibforums->vars['board_name']} {$id['name']} Shop</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>Cost</td>
<td class='titlemedium' align='center' width='1%'>Sold</td>
<td class='titlemedium' align='center' width='1%'>Stock</td>
<td class='titlemedium' align='center' width='5%'>Required Level</td>
<td class='titlemedium' align='center' width='1%'>Scan</td>
</tr>
EOF;
}

function HealTop() {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>{$ibforums->vars['board_name']} Healing Center</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='titlemedium' align='center' width='1%'>Icon</td>
<td class='titlemedium' align='center'>Name</td>
<td class='titlemedium' align='center'>Description</td>
<td class='titlemedium' align='center' width='1%'>+HP</td>
<td class='titlemedium' align='center' width='1%'>+MP</td>
<td class='titlemedium' align='center' width='1%'>Cost</td>
<td class='titlemedium' align='center' width='1%'>Sold</td>
</tr>
EOF;
}

function HealBottom($Member) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='7' class='row4'>Total {$ibforums->lang['money']}: {$Member['money']}</td>
</tr>
</table>
</div>
<br>
EOF;
}

function ShopBottom($Member) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='9' class='row4'>Total {$ibforums->lang['money']}: {$Member['money']}</td>
</tr>
</table>
</div>
<br>
EOF;
}

function InvenBottom($Member) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='6' class='row4'>Total {$ibforums->lang['money']}: {$Member['money']}</td>
</tr>
</table>

</div>
<br>
EOF;
}

function HealInvBottom($Member) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='8' class='row4'>Total {$ibforums->lang['money']}: {$Member['money']}</td>
</tr>
</table>

</div>
<br>
EOF;
}

function ScanBottom($Member) {
global $ibforums;
return <<<EOF
<tr>
<td colspan='4' class='row4'>Total {$ibforums->lang['money']}: {$Member['money']}</td>
</tr>
</table>

</div>
EOF;
}

function Row($Data) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/items/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'>{$Data['desc']}</td>
<td class='row2' align='center' width='1%'>{$Data['cost']}<br><a href=?act=RPG&CODE=buyitem&id={$Data['id']}>{$ibforums->lang['buy']}</a></td>
<td class='row2' align='center'>{$Data['sold']}</td>
<td class='row2' align='center'>{$Data['stock']}</td>
<td class='row2' align='center'>{$Data['lvlre']}</td>
<td class='row2' align='center' width='1%'><a href='?act=RPG&CODE=scan&id={$Data['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$Data['name']}' border='0'></a></td>
</tr>
EOF;
}

function HealRow($Data) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/heal/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'>{$Data['desc']}</td>
<td class='row2' align='center'>{$Data['hp']}</td>
<td class='row2' align='center'>{$Data['mp']}</td>
<td class='row2' align='center' width='1%'>{$Data['cost']}<br><a href=?act=RPG&CODE=buyheal&id={$Data['id']}>{$ibforums->lang['buy']}</a></td>
<td class='row2' align='center'>{$Data['sold']}</td>
</tr>
EOF;
}

function InvenRow($Data,$Sell) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/items/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'>{$Data['desc']}</td>
<td class='row2' align='center' width='1%'>{$Sell}<br><a href=?act=RPG&CODE=sellitem&id={$Data['id']}>{$ibforums->lang['sell']}</a></td>
<td class='row2' align='center' width='1%'><a href='?act=RPG&CODE=scan&id={$Data['id']}'><img src='sources/Inferno/scan.gif' alt='Scan {$Data['name']}' border='0'></a></td>
</tr>
EOF;
}

function HealInvRow($Data,$Sell) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/heal/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'>{$Data['desc']}</td>
<td class='row2' align='center'>{$Data['hp']}</td>
<td class='row2' align='center'>{$Data['mp']}</td>
<td class='row2' align='center' width='1%'>{$Sell}<br><a href=?act=RPG&CODE=sellheal&id={$Data['id']}>{$ibforums->lang['sell']}</a></td>
<td class='row2' align='center' width='1%'><a href=?act=RPG&CODE=healme&id={$Data['id']}>Use</a></td>
</tr>
EOF;
}

function InvenRowU($Data,$Sell) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center' colspan='2'>{$Data['desc']}</td>
<td class='row2' align='center' width='1%'>{$Sell}<br><a href=?act=RPG&CODE=sellitem&type=u&id={$Data['id']}>{$ibforums->lang['sell']}</a></td>
</tr>
EOF;
}

function ScanRow($Data) {
global $ibforums;
return <<<EOF
<tr>
<td class='row2' align='center' width='1%'><img src='html/Inferno/items/{$Data['img']}' alt='{$Data['name']}'></td>
<td class='row2' align='center'>{$Data['name']}</td>
<td class='row2' align='center'><b>HP:</b> {$Data['hp']} | <b>MP:</b> {$Data['mp']} | <b>STR:</b> {$Data['str']} | <b>DEF:</b> {$Data['def']}</td>
<td class='row2' align='center' width='1%'>{$Data['cost']}<br><a href=?act=RPG&CODE=buyitem&id={$Data['id']}>{$ibforums->lang['buy']}</a></td>
</tr>
EOF;
}

function Copyright() {
global $ibforums;
return <<<EOF
<br/>
<div class="tableborder">
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td class='row4' align='center' width='100%'>Running RPG Inferno Version 2 By <a href='http://gzevo.net/forum'>Zero Tolerance</a></td></tr>
</table>
</div>
EOF;
}

function TransTop($to) {
global $ibforums;
return <<<EOF
<div class="tableborder">
<div class=maintitle><b>Transfer {$ibforums->lang['money']}</b></div>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<form action="{$ibforums->base_url}act=RPG&CODE=dosendm" method="post">
<tr>
<td class='titlemedium' align='center' width='1%'>To</td>
<td class='titlemedium' align='center'>Amount</td>
</tr>
<tr>
<td class="pformleft"><input type="text" name='to' value="{$to}"></td>
<td class="pformleft"><input type="text" name="amount" value=""></td>
</tr>
<tr>
<td class='pformleft' colspan='2' align='center'><input type="submit" name="submit" value="Donate Money"></td>
</tr>
</table></div>
EOF;
}



// End
}
?>