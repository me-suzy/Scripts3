<?php
// RPG Inferno Version 2
// Created By Zero Tolerance
// http://gzevo.net

// Some Global Crap
$idx = new RPG;

class RPG {

var $output     = "";
var $page_title = "";
var $nav        = array();
var $html       = "";
function RPG(){
global $ibforums, $std, $print;
if ($ibforums->input['CODE'] == "" or $ibforums->input['CODE'] == null){$ibforums->input['CODE'] = 'Buy';}

// Lang - Then Skin - Then Thing :P

$ibforums->lang = $std->load_words($ibforums->lang, 'lang_rpg', $ibforums->lang_id );

$this->html = $std->load_template('skin_RPG');

$this->base_url = $ibforums->base_url;

// Guest?
$this->member  = $ibforums->member;

if(empty($this->member['id'])){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
}

// Action
switch($ibforums->input['CODE']) {
case 'shop':
$this->BuyItems();
break;
case 'sendm':
$this->send_money();
break;
case 'dosendm':
$this->consend_money();
break;
case 'sellitem':
$this->con_sell();
break;
case 'buyitem':
$this->con_buy();
break;
case 'scan':
$this->ScanItem();
break;
case 'Bank':
$this->Bank();
break;
case 'deposit':
$this->deposit();
break;
case 'withdraw':
$this->withdraw();
break;
case 'Clans':
$this->Clan();
break;
case 'JoinClan':
$this->JoinClan();
break;
case 'BuyClan':
$this->BuyClan();
break;
case 'doclanb':
$this->do_BuyClan();
break;
case 'doclanm':
$this->do_ManageClan();
break;
case 'doclani':
$this->do_InviteClan();
break;
case 'doclanc':
$this->do_ClanControl();
break;
case 'LeaveClan':
$this->LeaveClan();
break;
case 'ManageClan':
$this->ManageClan();
break;
case 'InviteClan':
$this->InviteClan();
break;
case 'JoinY':
$this->JoinY();
break;
case 'JoinN':
$this->JoinN();
break;
case 'ViewClan':
$this->ViewClan();
break;
case 'ClanControl':
$this->ClanControl();
break;
case 'Heal':
$this->HealingCenter();
break;
case 'buyheal':
$this->do_healbuy();
break;
case 'sellheal':
$this->do_healsell();
break;
case 'healme':
$this->healchar();
break;
//+------------## Battle Ground Functions
case 'Battle':
$this->BattleGround();
break;
case 'MakeBattle':
$this->MakeBattle();
break;
case 'Attack':
$this->Attack();
break;
case 'VAB':
$this->ViewAllBattles();
break;
case 'ViewBattle':
$this->ViewBattle();
break;
//+------------## Battle Ground Functions
//============ Anyone call for version 2? ==========
case 'Stats':
$this->StatsAll();
break;
case 'Lottery':
$this->Lottery();
break;
case 'BuyTick':
$this->BuyTick();
break;
case 'do_BuyTick':
$this->do_BuyTick();
break;
case 'Check':
$this->Check_Numbers();
break;
case 'Verify':
$this->Verify();
break;
case 'Surrender':
$this->Surrender();
break;
//@@@ Store @@@
case 'Store':
$this->Store();
break;
case 'StoreBuy':
$this->StoreBuy();
break;
case 'do_StoreBuy':
$this->StoreBuydo();
break;
//@@@ Store @@@
//++++++++ so many questions, argh, better make a help system :P
case 'Help':
$this->Help();
break;
case 'ViewHelp':
$this->ViewHelp();
break;
//++++++++ it ends here, mr anderson <== wtf? 0.o
//============ Anyone call for version 2? ==========
//+------------## Job Functions by CTM
case 'ViewJobs':
$this->ViewJobs();
break;
case 'PickJob':
$this->PickJob();
break;
case 'LeaveJob':
$this->LeaveJob();
break;
case 'CollectSalary':
$this->CollectSalary();
break;
//+------------## Job Functions by CTM
// ever seen a pink banana? ............same here
case 'Equip':
$this->EquipCharacter();
break;
case 'EquipB':
$this->EquipType();
break;
case 'doEquip':
$this->doEquip();
break;
case 'doUnEquip':
$this->doUnEquip();
break;
// it was fun looking at this line wasn't it!
default:
$this->BuyItems();
break;
}



// print data
$print->add_output("$this->output");
$print->do_output( array( 'TITLE' => $ibforums->vars['board_name']." - ".$this->page_title, 'JS' => 0, 'NAV' => $this->nav ) );
}




function BuyItems(){
global $DB, $ibforums,$std;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['itemshopon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// Member Money
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();

// our inventory, yay!
$this->output .= $this->html->InvenTop();
$DB->query("SELECT i.*,s.*
FROM ibf_infernostock s
LEFT JOIN ibf_infernoshop i ON (s.item=i.id)
where s.owner='{$ibforums->member['id']}'");
while($Item=$DB->fetch_row()){
$Sell=floor($Item['cost']/3);
$this->output .= $this->html->InvenRow($Item,$Sell);
}

$this->output .= $this->html->InvenBottom($Member);

if($ibforums->input['shop']){
$id=$ibforums->input['shop'];
$DB->query("SELECT * FROM ibf_infernocat where cid='{$id}'");
$shop = $DB->fetch_row();
// Grab Items And Data
$this->output .= $this->html->ShopTop($shop);
$i=0;
$DB->query("SELECT * FROM ibf_infernoshop where type='{$id}'");
while ($Data = $DB->fetch_row() ) {
$i++;
$this->output .= $this->html->Row($Data);
}
$this->output .= $this->html->ShopBottom($Member);
}
// lets display some cats
$DB->query("SELECT * FROM ibf_infernocat");
$this->output .= $this->html->CatTop();
while ($catr = $DB->fetch_row() ) {
$this->output .= $this->html->CatRow($catr);
}
$this->output .= $this->html->CatBottom();

$this->output .= $this->html->Copyright();
$this->page_title = "Itemshop";
$this->nav        = array( 
"Itemshop",
 );
}

function ScanItem(){
global $DB, $ibforums;
// Member Money
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
// Grab Items And Data
$this->output .= $this->html->ScanTop();
$DB->query("SELECT * FROM ibf_infernoshop where id='".$ibforums->input['id']."'");
$Data = $DB->fetch_row();

$this->output .= $this->html->ScanRow($Data);

$this->output .= $this->html->ScanBottom($Member);
$this->output .= $this->html->Copyright();

$this->page_title = "Scanning Item";
$this->nav        = array( 
"<a href='?act=RPG&CODE=shop'>Itemshop</a>",
"Scanning Item", );
}

function con_buy(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoshop where id='".$ibforums->input['id']."'");
$Data = $DB->fetch_row();
if($Member['level'] < $Data['lvlre']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'require_lvl' ) );
}
if($Data['stock']=="0"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'sold_out' ) );
}
if($Member['money'] < $Data['cost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'need_morem' ) );
} else {
// Ok we can buy the item - you win :P but where???? X.x'
$Member['money'] -= $Data['cost'];

$DB->query("SELECT * FROM ibf_infernostock where owner='".$ibforums->member['id']."' and item='{$Data['id']}'");
if($DB->get_num_rows() > 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'allr_owni' ) );
}

$DB->query("UPDATE ibf_members SET money='{$Member['money']}' WHERE id='{$ibforums->member['id']}'");


$DB->query("insert into ibf_infernostock VALUES('','{$ibforums->member['id']}','{$Data['id']}')");


// Update Sold Stat+Stock Stat
$DB->query("UPDATE ibf_infernoshop SET sold=sold+'1',stock=stock-'1' WHERE id='{$ibforums->input['id']}'");

$log="{$Member['name']} bought item {$Data['name']}";
$type="Itemshop";
$this->AddLog($log,$type);

$print->redirect_screen($ibforums->lang['item_bought'], "act=RPG&CODE=shop",0);
}

}

function con_sell(){
global $DB, $ibforums, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoshop where id='{$ibforums->input['id']}'");
$Data = $DB->fetch_row();
$Sell=floor($Data['cost']/3);
$Member['money'] += $Sell;

$DB->query("UPDATE ibf_members SET money='{$Member['money']}' WHERE id='{$ibforums->member['id']}'");
$DB->query("delete from ibf_infernostock WHERE item='{$ibforums->input['id']}' and owner='{$ibforums->member['id']}'");

// un-equip if equipped :P
$DB->query("delete from ibf_infernoequip where eitem='{$ibforums->input['id']}' and eowner='{$ibforums->member['id']}'");

// someone made a new method for items giving stats - its cool and i should of though of it >_< *kicks self*
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$member['hp']-=$Data['hp'];
$member['hpm']-=$Data['hp'];
$member['mp']-=$Data['mp'];
$member['mpm']-=$Data['mp'];
$member['str']-=$Data['str'];
$member['def']-=$Data['def'];
$DB->query("update ibf_members set hp='{$member['hp']}',hpm='{$member['hpm']}',mp='{$member['mp']}',mpm='{$member['mpm']}',str='{$member['str']}',def='{$member['def']}' where id='".$ibforums->member['id']."'");

$log="{$Member['name']} sold item {$Data['name']}";
$type="Itemshop";
$this->AddLog($log,$type);

$print->redirect_screen($ibforums->lang['item_sold'], "act=RPG&CODE=shop",0);
}

function send_money(){
global $DB, $ibforums, $print, $std;
// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['transferon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// You
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();

if($ibforums->input['name']){
$to=$ibforums->input['name'];
}else{
$to="";
}

$this->output .= $this->html->TransTop($to);

$this->output .= $this->html->Copyright();
$this->page_title = "Transfer {$ibforums->lang['money']}";
$this->nav        = array( 
"<a href='?act=RPG&CODE=shop'>Itemshop</a>",
"Transfer {$ibforums->lang['money']}", );
}

function consend_money(){
global $DB, $ibforums, $std, $print;

// You
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$to=$ibforums->input['to'];
$amount=$ibforums->input['amount'];

if($amount < 1){
$std->Error( array ('LEVEL' => 1, 'MSG' => 'depnothing' ) );
}

if(strToLower($to) == strToLower($member['name'])){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'donatetoself' ) );
}

if($amount > $member['money']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'notenough' ) );
}

$DB->query("SELECT * FROM ibf_members where name='{$to}'");
if(!$gto=$DB->fetch_row()){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'member_notfound' ) );
}

$amount = str_replace("&nbsp;","",$amount);
$amount = str_replace(" ","",$amount);

// Ok - everything went fine, lets ditch them the money and take it from you :P
$member['money'] -= $amount;
if($member['money'] < 0 ){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'notenough' ) );
}
$gto['money'] += $amount;

$DB->query("UPDATE ibf_members SET money='{$member['money']}' WHERE id='{$ibforums->member['id']}'");
$DB->query("UPDATE ibf_members SET money='{$gto['money']}' WHERE name='{$to}'");

$DB->query("select * from ibf_members WHERE name='{$to}'");
$mxc=$DB->fetch_row();

$Title="{$ibforums->lang['money']} Donated";
$To=$mxc['id'];
$Message="[Auto Message - DO NOT REPLY] You have been sent you some {$ibforums->lang['money']} by {$ibforums->member['name']}. Enjoy :)";
$this->Send_PM($Title,$Message,$To);

$log="{$member['name']} transfered {$amount} to {$mxc['name']}";
$type="Transfer";
$this->AddLog($log,$type);

$print->redirect_screen($amount." {$ibforums->lang['money']} has been sent", "act=RPG&CODE=shop",0);
}

function Bank(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['bankon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// quick! give them interest before they see anything!
// Interest - Not very intresting tho
$rate=86400;
$DB->query("SELECT * FROM ibf_rpgoptions");
$options=$DB->fetch_row();
$intrest=$options['intrest'];
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$bankmoney=$member['bankmoney'];
$lastvisit=$member['lastvisit'];
$nowtime=time();
$checktime=$nowtime-$lastvisit;
if($checktime > $rate){
$addintrest=$bankmoney/100*$intrest;
$addintrest=(int)$addintrest;
$addintrest=$addintrest+$bankmoney;
$DB->query("UPDATE ibf_members SET bankmoney='{$addintrest}', lastvisit='{$nowtime}' WHERE id='{$ibforums->member['id']}'");
$print->redirect_screen("Your Interest has been Collected", "act=RPG&CODE=Bank",0);
}

// Display Bank Layout
$DB->query("SELECT * FROM ibf_rpgoptions");
$dd=$DB->fetch_row();
$this->output .= $this->html->Bank($member,$dd);
$this->output .= $this->html->Copyright();
$this->page_title = "Bank";
$this->nav        = array( 
"<a href='?act=RPG&CODE=shop'>Itemshop</a>",
"Bank", );
}

function deposit(){
global $DB, $ibforums, $std, $print;
$deposit=$ibforums->input['deposit'];
// You
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$money=$member['money'];
$bmoney=$member['bankmoney'];

// Possible Errors
if($deposit > $money){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'toomuch_dep' ) );
}
if($deposit < 1){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'depnothing' ) );
}
if($deposit == ""){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'blank' ) );
}
$deposit = str_replace("&nbsp;","",$deposit);
$deposit = str_replace("<","<!--",$deposit);
$deposit = str_replace(">","-->",$deposit);
$deposit = stripslashes($deposit);

// where ok - lets deposit
$money=$money-$deposit;
if($money < 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'toomuch_dep' ) );
}
$bmoney=$bmoney+$deposit;
$DB->query("update ibf_members set money='{$money}', bankmoney='{$bmoney}' where id='{$ibforums->member['id']}'");
// Hehe - you have to leave it alone for a day for the intrest to go, otherwise this happens, reset time, AHAHAHA a ha ha... not so funny :P
$time=time();
$DB->query("update ibf_members set lastvisit='$time' where id='{$ibforums->member['id']}'");
$log="{$member['name']} deposited {$deposit} into the bank";
$type="Bank";
$this->AddLog($log,$type);
$print->redirect_screen("Your {$ibforums->lang['money']} has been Deposited", "act=RPG&CODE=Bank",0);
}

function withdraw(){
global $DB, $ibforums, $std, $print;
$withdraw=$ibforums->input['withdraw'];
// You
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$money=$member['money'];
$bmoney=$member['bankmoney'];

// Possible Errors
if($withdraw > $bmoney){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'toomuch_draw' ) );
}
if($withdraw < 1){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'depnothing' ) );
}
if($withdraw == ""){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'blank' ) );
}

$withdraw = str_replace("&nbsp;","",$withdraw);
$withdraw = str_replace("<","<!--",$withdraw);
$withdraw = str_replace(">","-->",$withdraw);
$withdraw = stripslashes($withdraw);

// where ok - lets withdraw
$money=$money+$withdraw;
$bmoney=$bmoney-$withdraw;
if($bmoney < 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'toomuch_dep' ) );
}
$DB->query("update ibf_members set money='{$money}', bankmoney='{$bmoney}' where id='{$ibforums->member['id']}'");
// Hehe - you have to leave it alone for a day for the intrest to go, otherwise this happens, rest time, AHAHAHA a ha ha... not so funny :P
$time=time();
$DB->query("update ibf_members set lastvisit='$time' where id='{$ibforums->member['id']}'");

$log="{$member['name']} withdrew {$withdraw} from the bank";
$type="Bank";
$this->AddLog($log,$type);

$print->redirect_screen("Your {$ibforums->lang['money']} has been Withdraw", "act=RPG&CODE=Bank",0);
}

function Clan(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['clanon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// just a normal day when you slip on a banana 0.o
$DB->query("SELECT * FROM ibf_infernoclan");

$this->output .= $this->html->ClanTop();

while($clan=$DB->fetch_row()){
$this->output .= $this->html->ClanRow($clan);
}

$this->output .= $this->html->ClanBottom();

$this->output .= $this->html->Copyright();
$this->page_title = "Clans";
$this->nav        = array( 
"Clans", );
}

function BuyClan(){
global $DB, $ibforums, $std, $print;

$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernoclan");
while($data=$DB->fetch_row()){
if($member['inclan']==$data['name']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'clanexist' ) );
}}

$DB->query("SELECT * FROM ibf_clanoptions");
$data=$DB->fetch_row();
$this->output .= $this->html->BuyClan($data);

$this->output .= $this->html->Copyright();
$this->page_title = "Buy A Clan";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Buy A Clan");
}

function do_BuyClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_clanoptions");
$data=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

// we can afford this right?
if($member['money'] < $data['cost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'notenoughm' ) );
}

// we entered a name right?
if($ibforums->input['cname']==""){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'blank' ) );
}

// clan name taken?
$DB->query("SELECT * FROM ibf_infernoclan where name='{$ibforums->input['cname']}'");
if($DB->get_num_rows() > 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'clantaken' ) );
}

// ok we'll let you slide in and have a clan - but where gonna take ur money, hahahah
$money=$member['money']-$data['cost'];
$DB->query("update ibf_members set money='$money', inclan='{$ibforums->input['cname']}' where id='".$ibforums->member['id']."'");

// ok lets give u a clan
$DB->query("INSERT INTO `ibf_infernoclan` VALUES ('','','{$ibforums->input['cname']}','{$ibforums->member['name']}','{$ibforums->member['id']}','1')  ");

$log="{$member['name']} created clan {$ibforums->input['cname']}";
$type="Clan";
$this->AddLog($log,$type);
$print->redirect_screen("Clan {$ibforums->input['cname']} Created", "act=RPG&CODE=Clans",0);
}

function JoinClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernoclan");
while($data=$DB->fetch_row()){
if($member['inclan']==$data['name']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'clanexist' ) );
}}
$this->output .= $this->html->JoinClanTop();
$DB->query("SELECT * FROM ibf_infernoclan");
while($data=$DB->fetch_row()){
if($member['claninv']==$data['name']){
$this->output .= $this->html->JoinClanRowT($data);
}}
if($member['claninv']==""){
$this->output .= $this->html->JoinClanRowF();
}
$this->output .= $this->html->JoinClanBottom();

$this->output .= $this->html->Copyright();
$this->page_title = "Join A Clan";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Join A Clan");
}

function JoinY(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$DB->query("update ibf_members set inclan='{$member['claninv']}', claninv='' where id='{$ibforums->member['id']}'");
// memebr count increase
$DB->query("SELECT * FROM ibf_infernoclan where name='{$member['claninv']}'");
$clan=$DB->fetch_row();
$memc=$clan['totalm']+1;
$DB->query("update ibf_infernoclan set totalm='$memc' where name='{$member['claninv']}'");
$log="{$member['name']} joined clan {$member['claninv']}";
$type="Clan";
$this->AddLog($log,$type);
$print->redirect_screen("You Joined clan {$member['inclan']}", "act=RPG&CODE=Clans",0);
}

function JoinN(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$DB->query("update ibf_members set claninv='' where id='{$ibforums->member['id']}'");

$print->redirect_screen("Invitation Removed", "act=RPG&CODE=Clans",0);
}

function LeaveClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
// leader?
$DB->query("SELECT * FROM ibf_infernoclan");
while($data=$DB->fetch_row()){
if($member['name']==$data['leader']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'leaderleave' ) );
}}

// we are history!
// memebr count decrease
$DB->query("SELECT * FROM ibf_infernoclan where name='{$member['inclan']}'");
$clan=$DB->fetch_row();
$memc=$clan['totalm']-1;
$DB->query("update ibf_infernoclan set totalm='$memc' where name='{$member['inclan']}'");

$DB->query("update ibf_members set inclan='', claninv='' where id='{$ibforums->member['id']}'");

$log="{$member['name']} left clan {$member['inclan']}";
$type="Clan";
$this->AddLog($log,$type);

$print->redirect_screen("You left the clan", "act=RPG&CODE=Clans",0);
}

function ManageClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
// own a clan?
$DB->query("SELECT * FROM ibf_infernoclan where name='{$member['inclan']}'");
$data=$DB->fetch_row();
if($member['id']==$data['leaderid']){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'noown' ) );
}

$this->output .= $this->html->ManageClan($data);

$this->output .= $this->html->Copyright();
$this->page_title = "Manage Your Clan";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Manage Your Clan");
}

function do_ManageClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoclan where leaderid='".$ibforums->member['id']."'");
$clan=$DB->fetch_row();
$name=$ibforums->input['name'];
$img=$ibforums->input['img'];
if($name==""){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'blank' ) );
}
// clan name taken?
$DB->query("SELECT * FROM ibf_infernoclan where name='{$name}' and leaderid!='{$member['id']}'");
if($DB->get_num_rows() > 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'clantaken' ) );
}
$DB->query("update ibf_members set inclan='{$name}' where inclan='{$clan['name']}'");
$DB->query("update ibf_infernoclan set name='{$name}', img='{$img}' where leaderid='{$ibforums->member['id']}'");

$log="{$member['name']} updated his/her clan {$name}";
$type="Clan";
$this->AddLog($log,$type);

$print->redirect_screen("Clan {$name} Updated", "act=RPG&CODE=Clans",0);
}

function InviteClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
// own a clan?
$DB->query("SELECT * FROM ibf_infernoclan where name='{$member['inclan']}'");
$data=$DB->fetch_row();
if($member['id']==$data['leaderid']){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'noown' ) );
}

$this->output .= $this->html->InviteClan();

$this->output .= $this->html->Copyright();
$this->page_title = "Invite Member To Your Clan";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Invite Member To Your Clan");
}

function do_InviteClan(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoclan where name='{$member['inclan']}'");
$data=$DB->fetch_row();
$smem=$ibforums->input['name'];
$DB->query("SELECT * FROM ibf_members where name='{$smem}'");
if(!$tmem=$DB->fetch_row()){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'nofound' ) );
}
if($tmem['claninv']==""){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invall' ) );
}

// ok, invite the invitee
$DB->query("update ibf_members set claninv='{$data['name']}' where name='{$tmem['name']}'");
$DB->query("SELECT * FROM ibf_members where name='{$tmem['name']}'");
$memberx=$DB->fetch_row();
$Title="Clan Invitation";
$To=$memberx['id'];
$Message="[Auto Message - DO NOT REPLY] You have been invited to clan: {$data['name']}. Please visit clan page to accept invitation or discard it :)";
$this->Send_PM($Title,$Message,$To);

$log="{$member['name']} invited the member {$memberx['name']} to his/her clan";
$type="Clan";
$this->AddLog($log,$type);

$print->redirect_screen("{$tmem['name']} Invited", "act=RPG&CODE=Clans",0);


}

function ViewClan(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['clanon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

$clan=$ibforums->input['clan'];

$DB->query("SELECT * FROM ibf_infernoclan where name='{$clan}'");
$data=$DB->fetch_row();
if($data['img']==""){
$data['img']="<b>No Logo</b>";
}else{
$data['img']="<img src='{$data['img']}' alt='{$data['name']} Clan'>";
}

$DB->query("SELECT * FROM ibf_members where id='{$data['leaderid']}'");
$lclan=$DB->fetch_row();
$data['vics']=$lclan['vics'];
$data['loss']=$lclan['loss'];

$this->output .= $this->html->ClanViewTop($clan,$data);

$DB->query("SELECT * FROM ibf_members where inclan='{$clan}'");
while($mclan=$DB->fetch_row()){
if($mclan['name']==$data['leader']){}else{
$this->output .= $this->html->ClanViewRow($mclan);
}}

$this->output .= $this->html->ClanViewBottom($data);

$this->output .= $this->html->Copyright();
$this->page_title = "Viewing {$clan} Clan";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Viewing {$clan} Clan");
}

function ClanControl(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernoclan where leaderid='{$ibforums->member['id']}'");
if(!$clan=$DB->fetch_row()){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'noown' ) );
}
$this->output .= $this->html->ClanControlTop();

$DB->query("SELECT * FROM ibf_members where inclan='{$clan['name']}'");
while($mclan=$DB->fetch_row()){
if($mclan['name']==$clan['leader']){}else if($mclan['inclan']==$clan['name']){
$this->output .= $this->html->ClanControlRow($mclan);
}else{}}
$this->output .= $this->html->ClanControlBottom();

$this->output .= $this->html->Copyright();
$this->page_title = "Clan Control";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Clans'>Clans</a>", 
"Clan Control");
}

function do_clancontrol(){
global $DB, $ibforums, $std, $print;
$mid=$ibforums->input['mid'];
$DB->query("update ibf_members set inclan='', claninv='' where id='{$mid}'");
$DB->query("SELECT * FROM ibf_infernoclan where leaderid='{$ibforums->member['id']}'");
$clan=$DB->fetch_row();
$mcount=$clan['totalm']-1;
$DB->query("update ibf_infernoclan set totalm='{$mcount}' where leaderid='{$ibforums->member['id']}'");

$log="{$member['name']} removed a member from there clan (Member Id: {$mid})";
$type="Clan";
$this->AddLog($log,$type);

$print->redirect_screen("Member removed", "act=RPG&CODE=Clans",0);
}

function HealingCenter(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['healingon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// 1 Healing Center, Coming Up!
$this->output .= $this->html->HealInvTop();
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
if($Member['heal1']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$Member['heal1']}'");
$Heal=$DB->fetch_row();
$Sell=floor($Heal['cost']/3);
$this->output .= $this->html->HealInvRow($Heal,$Sell);
}
if($Member['heal2']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$Member['heal2']}'");
$Heal=$DB->fetch_row();
$Sell=floor($Heal['cost']/3);
$this->output .= $this->html->HealInvRow($Heal,$Sell);
}
if($Member['heal3']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$Member['heal3']}'");
$Heal=$DB->fetch_row();
$Sell=floor($Heal['cost']/3);
$this->output .= $this->html->HealInvRow($Heal,$Sell);
}
$this->output .= $this->html->HealInvBottom($Member);

// What do we have in stock people :P
$this->output .= $this->html->HealTop();

$DB->query("SELECT * FROM ibf_infernoheal");
while ($Data = $DB->fetch_row() ) {
$this->output .= $this->html->HealRow($Data);
}
$this->output .= $this->html->HealBottom($Member);


$this->output .= $this->html->Copyright();
$this->page_title = "Healing Center";
$this->nav        = array( 
"<a href='?act=RPG&CODE=shop'>Itemshop</a>",
"Healing Center", );
}

function do_healbuy(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoheal where id='".$ibforums->input['id']."'");
$Data = $DB->fetch_row();
if($Member['money'] < $Data['cost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'need_morem' ) );
} else {
if($Member['inbattle'] > 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'nobuy_durbattle' ) );
} else {
// Ok we can buy the heal - you win :P but where???? X.x'
$Member['money'] -= $Data['cost'];
if($Member['heal1']==""){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal1='{$Data['id']}' WHERE id='{$ibforums->member['id']}'");
} else if($Member['heal2']==""){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal2='{$Data['id']}' WHERE id='{$ibforums->member['id']}'");
} else if($Member['heal3']==""){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal3='{$Data['id']}' WHERE id='{$ibforums->member['id']}'");
} else {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'fullheals' ) );
}

$log="{$Member['name']} bought healing healing {$Data['name']}";
$type="Healing Center";
$this->AddLog($log,$type);

// Update Sold Stat
$DB->query("UPDATE ibf_infernoheal SET sold=sold+'1' WHERE id='{$ibforums->input['id']}'");
$print->redirect_screen($ibforums->lang['heal_bought'], "act=RPG&CODE=Heal",0);
}}}

function do_healsell(){
global $DB, $ibforums, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_infernoheal where id='".$ibforums->input['id']."'");
$Data = $DB->fetch_row();
$Sell=floor($Data['cost']/3);
$Member['money'] += $Sell;


if($Member['heal1']==$Data['id']){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal1='' WHERE id='{$ibforums->member['id']}'");
} else if($Member['heal2']==$Data['id']){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal2='' WHERE id='{$ibforums->member['id']}'");
} else if($Member['heal3']==$Data['id']){
$DB->query("UPDATE ibf_members SET money='{$Member['money']}', heal3='' WHERE id='{$ibforums->member['id']}'");
} else {
}

$log="{$Member['name']} sold there healing {$Data['name']}";
$type="Healing Center";
$this->AddLog($log,$type);

$print->redirect_screen($ibforums->lang['heal_sold'], "act=RPG&CODE=Heal",0);
}

function BattleGround(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['battleon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
if($Member['inbattle'] < 1){
// No Battle Present - Lets Make one weeeeeee
// Backgrounds people - backgrounds :P
$DB->query("SELECT * FROM ibf_infernoscene");
$scenes="";
while($scene=$DB->fetch_row()){
$scenes.="<option value='{$scene['img']}'>{$scene['name']}</option>";
}
$this->output .= $this->html->MakeBattle($scenes);
$this->page_title = "Make Battle";
$this->nav        = array( 
"Make Battle",
 );
} else {
// Battle Present - Ok Lets Get Your Battle
$DB->query("SELECT * FROM ibf_infernobattle where id='{$Member['inbattle']}'");
$battle=$DB->fetch_row();
// Player 1 - if your p2, we need to get p1 :P
$DB->query("SELECT * FROM ibf_members where id='{$battle['p1']}'");
$Player=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$Opponent=$DB->fetch_row();


// turn?
if($battle['turn']=="p1"){
$battle['wturn']=$Player['name'];
}else{
$battle['wturn']=$Opponent['name'];
}
// Lets deal with links - Who views, and random attack/spell
$count=0;
$DB->query("SELECT * FROM ibf_infernoreturn");
while($moves=$DB->fetch_row()){
$count++;
}
$move1=rand(1,$count);


$DB->query("SELECT * FROM ibf_infernoreturn where id='{$move1}'");
$m=$DB->fetch_row();
$a="<a hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}' href='#' onclick='document.check=\"ok\";Attack(\"{$m['img']}\");Go(this.hrefx);Disable();' config='attack'>{$m['name']}</a>";
$move2=rand(1,$count);
$DB->query("SELECT * FROM ibf_infernoreturn where id='{$move2}'");
$m2=$DB->fetch_row();
$b="<a hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}' href='#' onclick='document.check=\"ok\";Attack(\"{$m2['img']}\");Go(this.hrefx);Disable();' config='attack'>{$m2['name']}</a>";
// they ask, what is special move for? limit break ofcourse
$DB->query("SELECT * FROM ibf_members where id='{$ibforums->member['id']}'");
$mview=$DB->fetch_row();
$life=$mview['hpm']/100;
$life=(int)$life*10;
if($mview['hp'] < $life ){
// Limit break time
$b=$b." <font color='red'><b>(<a href='#' hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}&type=limit' onclick='document.check=\"ok\";Attack(\"{$m2['img']}\");Go(this.hrefx);Disable();' config='attack'>{$mview['smove']}</a>)</b></font>";
}
// summon - ooooooo
if($Member['summon']==""){}else{
// grab summon data
$DB->query("SELECT * FROM ibf_infernosummon where id='{$Member['summon']}'");
$summon=$DB->fetch_row();
if($Member['mp'] > $summon['mp']){
$b=$b."<br /><b><a href='#' hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}&type=summon' onclick='document.check=\"ok\";Summon(\"{$summon['img']}\");Go(this.hrefx);Disable();' config='attack'>Summon {$summon['name']}</a></b>";
}else{}}

// battle verified?
if($battle['verify'] == "no"){
$a="<font color='red'><i><b><u>Battle Not Verified</u></b></i></font>";
$b="";
}

// Now we have the moves, where do we place? member or opponent :P
if($battle['turn']=="p1" and $ibforums->member['id']==$battle['p1']){
$Player['attack']=$a;
$Player['spell']=$b;
// Rage
if($Player['rage'] > 99){
$Player['ragelink']="<a hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}&type=rage' href='#' onclick='document.check=\"ok\";Attack(\"{$m['img']}\");Go(this.hrefx);Disable();' config='attack'>Rage</a>";
}
}
if($battle['turn']=="p2" and $ibforums->member['id']==$battle['p2']){
$Opponent['attack']=$a;
$Opponent['spell']=$b;
if($Opponent['rage'] > 99){
$Opponent['ragelink']="<a hrefx='{$ibforums->base_url}act=RPG&CODE=Attack&id={$battle['id']}&type=rage' href='#' onclick='document.check=\"ok\";Attack(\"{$m2['img']}\");Go(this.hrefx);Disable();'>Rage</a>";
}
}


if($Player['rpgav']==""){$Player['rpgav']="<img src='html/Inferno/scene/blank.gif'>";}else{$Player['rpgav']="<img src='{$Player['rpgav']}' width='{$Player['rpaw']}' height='{$Player['rpah']}'>";}
if($Opponent['rpgav']==""){$Opponent['rpgav']="<img src='html/Inferno/scene/blank.gif'>";}else{$Opponent['rpgav']="<img src='{$Opponent['rpgav']}' width='{$Opponent['rpaw']}' height='{$Opponent['rpah']}'>";}
$this->output .= $this->html->Battle($Player,$Opponent,$battle);
// Battle Log - who did what
$this->output .= $this->html->BLogT();
$DB->query("SELECT * FROM ibf_battlelog where bid='{$battle['id']}'");
while($blog=$DB->fetch_row()){
$this->output .= $this->html->BLogR($blog);
}
$this->output .= $this->html->BLogB();
$this->page_title = "Your Battle";
$this->nav        = array( 
"Your Battle",
 );
}
$this->output .= $this->html->Copyright();
}

function MakeBattle(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
$p1=$Member['id'];
$DB->query("SELECT * FROM ibf_members where name='".$ibforums->input['p2']."'");
$Opponent=$DB->fetch_row();
if($Opponent['id']=="" or $ibforums->input['p2']==""){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'member_notfound' ) );
}
$p2=$Opponent['id'];
if($Opponent['id']==$Member['id']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'battle_self' ) );
}
if($Opponent['hp'] < 1){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'dead' ) );
}
if($Opponent['inbattle'] > 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'already_battle' ) );
}
if($Member['hp'] < 1){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'udead' ) );
}

$scene=$ibforums->input['scene'];

// dalalalala - were gonna make a battle!
$DB->query("insert into ibf_infernobattle values('','','{$p1}','{$p2}','p1','{$scene}','no','{$p2}','{$Member['name']} Vs {$Opponent['name']}')");
// now lets grab what we just set 0o
$DB->query("SELECT * FROM ibf_infernobattle where p1='{$p1}'");
$madebattle=$DB->fetch_row();
$DB->query("update ibf_members set inbattle='{$madebattle['id']}' where id='{$p1}'");
$DB->query("update ibf_members set inbattle='{$madebattle['id']}' where id='{$p2}'");
$To=$Opponent['id'];
$Title="Battle Made";
$Message="[Auto Message DO NOT REPLY] - {$Member['name']} has made a battle with you - please verify if you want it or not:\n<a href='?act=RPG&CODE=Verify&ans=yes&id=".$madebattle['id']."'>Yes</a> | <a href='?act=RPG&CODE=Verify&ans=no&id=".$madebattle['id']."'>No</a> :)";
$this->Send_PM($Title,$Message,$To);

$log="{$Member['name']} made a battle with {$Opponent['name']}";
$type="Battle Ground";
$this->AddLog($log,$type);

$print->redirect_screen("Battle Created", "act=RPG&CODE=Battle",0);
}

function AttackP2(){
global $DB, $ibforums, $std, $print;
$type=$ibforums->input['type'];
$id=$ibforums->input['id'];
// Member
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$Member=$DB->fetch_row();
// Battle
$DB->query("SELECT * FROM ibf_infernobattle where id='{$id}'");
$battle=$DB->fetch_row();
// Opponent
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$Opponent=$DB->fetch_row();

$mhpm=$Member['hp'];
$mmpm=$Member['mp'];
$mhp=$Member['hp'];
$mmp=$Member['mp'];
$mstr=$Member['str'];

$ohpm=$Opponent['hp'];
$ompm=$Opponent['mp'];
$ohp=$Opponent['hp'];
$omp=$Opponent['mp'];
$ostr=$Opponent['str'];

// you must be p1, right?
if($ibforums->member['id']==$battle['p1']){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_turn' ) );
}

// Ima kick ur ass
$dmg=rand(1,$mstr);
if($type=="rage"){
if($Member['rage'] > 99){
$dmg=$mstr;
} else {
$dmg=$dmg;
} 
} else {
$dmg=$dmg;
}

if($ibforums->input['type']=="limit"){
$dmg=($mstr*2);
} else {
$dmg=$dmg;
}

if($ibforums->input['type']=="summon"){
// connect to summon
$DB->query("SELECT * FROM ibf_infernosummon where id='{$Member['summon']}'");
$summon=$DB->fetch_row();
$dmg=($summon['mp']*2);
// update members mp
$DB->query("update ibf_members set mp=mp-'{$summon['mp']}' where id='{$Member['id']}'");
} else {
$dmg=$dmg;
}

$dmg2=rand(1,$dmg);
$hp=$ohp-$dmg;
$mp=$omp-$dmg2;
if($mp < 1){
$mp=0;
}


// lets get some exp :D & rage >D
$exp=rand(1,7);
$rage=rand(1,7);
$exp=$exp+$Member['exp'];
$rage=$rage+$Member['rage'];
if($rage > 100){
$rage=100;
}
if($exp > 100){
// Level Up
$DB->query("update ibf_members set level=level+'1' where id='{$battle['p1']}'");
$exp=0;
// Woo lets get you some extra stats !!
$hp=rand(1,15);
$mp=rand(1,15);
$str=rand(1,15);
$def=rand(1,15);
$DB->query("SELECT * FROM ibf_members where id='{$battle['p1']}'");
$winner=$DB->fetch_row();
$leveluphp=$hp+$winner['hpm'];
$levelupmp=$mp+$winner['mpm'];
$levelupstr=$str+$winner['str'];
$levelupdef=$def+$winner['def'];

$DB->query("update ibf_members set hpm='{$leveluphp}',mpm='{$levelupmp}',str='{$levelupstr}',def='{$levelupdef}' where id='{$battle['p1']}'");
}
if($type=="rage"){
$rage=0;
}
// Update Exp & Rage
$DB->query("update ibf_members set rage='{$rage}',exp='{$exp}' where id='{$battle['p1']}'");

// rage check
if($type=="rage"){
$DB->query("update ibf_members set rage='0' where id='{$battle['p1']}'");
}

// Give our friend some pain, [insert evil laugh here]
$DB->query("update ibf_members set hp='{$hp}',mp='{$mp}' where id='{$battle['p2']}'");

// Turn Twist
$DB->query("update ibf_infernobattle set turn='p2' where id='{$id}'");

// Log Move
$DB->query("insert into ibf_battlelog VALUES('','{$battle['id']}','{$Member['name']}','{$Opponent['name']}','','{$hp}','{$mp}','{$dmg}');");

if($hp < 1){
// You win! - Update Your Stats
$this->P1Win($id);
}

// Ok thats out attack over :P
$print->redirect_screen("You attacked your opponent", "act=RPG&CODE=Battle",0);
}

function AttackP1(){
global $DB, $ibforums, $std, $print;
$type=$ibforums->input['type'];
$id=$ibforums->input['id'];
// Battle
$DB->query("SELECT * FROM ibf_infernobattle where id='{$id}'");
$battle=$DB->fetch_row();
// Member
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$Member=$DB->fetch_row();
// Opponent
$DB->query("SELECT * FROM ibf_members where id='{$battle['p1']}'");
$Opponent=$DB->fetch_row();

$mhpm=$Member['hp'];
$mmpm=$Member['mp'];
$mhp=$Member['hp'];
$mmp=$Member['mp'];
$mstr=$Member['str'];

$ohpm=$Opponent['hp'];
$ompm=$Opponent['mp'];
$ohp=$Opponent['hp'];
$omp=$Opponent['mp'];
$ostr=$Opponent['str'];

// you must be p2, right?
if($ibforums->member['id']==$battle['p2']){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_turn' ) );
}

// Ima kick ur ass
$dmg=rand(1,$mstr);
if($type=="rage"){
if($Member['rage'] > 99){
$dmg=$mstr;
} else {
$dmg=$dmg;
} 
} else {
$dmg=$dmg;
}

if($ibforums->input['type']=="limit"){
$dmg=($mstr*2);
} else {
$dmg=$dmg;
}

if($ibforums->input['type']=="summon"){
// connect to summon
$DB->query("SELECT * FROM ibf_infernosummon where id='{$Member['summon']}'");
$summon=$DB->fetch_row();
$dmg=($summon['mp']*2);
// update members mp
$DB->query("update ibf_members set mp=mp-'{$summon['mp']}' where id='{$Member['id']}'");
} else {
$dmg=$dmg;
}

$dmg2=rand(1,$dmg);
$hp=$ohp-$dmg;
$mp=$omp-$dmg2;
if($mp < 1){
$mp=0;
}

// lets get some exp :D & rage >D
$exp=rand(1,7);
$rage=rand(1,7);
$exp=$exp+$Member['exp'];
$rage=$rage+$Member['rage'];
if($rage > 100){
$rage=100;
}
if($exp > 100){
// Level Up
$DB->query("update ibf_members set level=level+'1' where id='{$battle['p2']}'");
$exp=0;
// Woo lets get you some extra stats !!
$hp=rand(1,15);
$mp=rand(1,15);
$str=rand(1,15);
$def=rand(1,15);
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$winner=$DB->fetch_row();
$leveluphp=$hp+$winner['hpm'];
$levelupmp=$mp+$winner['mpm'];
$levelupstr=$str+$winner['str'];
$levelupdef=$def+$winner['def'];

$DB->query("update ibf_members set hpm='{$leveluphp}',mpm='{$levelupmp}',str='{$levelupstr}',def='{$levelupdef}' where id='{$battle['p2']}'");
}
if($type=="rage"){
$rage=0;
}
// Update Exp & Rage
$DB->query("update ibf_members set rage='{$rage}',exp='{$exp}' where id='{$battle['p2']}'");

// rage check
if($type=="rage"){
$DB->query("update ibf_members set rage='0' where id='{$battle['p2']}'");
}

// Give our friend some pain, [insert evil laugh here]
$DB->query("update ibf_members set hp='{$hp}',mp='{$mp}' where id='{$battle['p1']}'");

// Turn Twist
$DB->query("update ibf_infernobattle set turn='p1' where id='{$id}'");

// Log Move
$DB->query("insert into ibf_battlelog VALUES('','{$battle['id']}','{$Member['name']}','{$Opponent['name']}','','{$hp}','{$mp}','{$dmg}');");

if($hp < 1){
// You win! - Update Your Stats
$this->P2Win($id);
}

// Ok thats out attack over :P
$print->redirect_screen("You attacked your opponent", "act=RPG&CODE=Battle",0);
}

function Attack(){
global $DB, $ibforums, $std, $print;
// Ok, who are ya, and who are ya attacking :P
$DB->query("SELECT * FROM ibf_infernobattle where id='{$ibforums->input['id']}'");
$battle=$DB->fetch_row();
// battle verified?
if($battle['verify']=="no"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'not_verified' ) );
}
if($ibforums->member['id']==$battle['p1'] and $battle['turn']=="p1"){
$this->AttackP2();
} else if($ibforums->member['id']==$battle['p2'] and $battle['turn']=="p2"){
$this->AttackP1();
} else {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_battle' ) );
}
}

function Send_PM($Title,$Message,$To){
global $DB, $ibforums, $std, $print;
// Member
$DB->query("SELECT * FROM ibf_members where id='{$To}'");
$member=$DB->fetch_row();
$DB->query("SELECT MAX(msg_id) as message_id FROM ibf_messages");
$auto_pm = $DB->fetch_row();
$auto_pm_messageid = $auto_pm['message_id'] + 1;
$current_time = time();
$pm_message = str_replace("*username*",$in_username,$Message);
$pm_subject = str_replace("*username*",$in_username,$Title);
require "./sources/lib/post_parser.php";
			   
$this->parser = new post_parser();  
$pm_message = $this->parser->convert( array( 'TEXT'    => $pm_message,
'SMILIES' => 1,
'CODE'    => $ibforums->vars['msg_allow_code'],
'HTML'    => $ibforums->vars['msg_allow_html']
)       );
$pm_message = addslashes($pm_message);			
$DB->query("INSERT INTO ibf_messages (msg_id,msg_date,read_state,title,message,from_id,vid,member_id,recipient_id,attach_type,attach_file,cc_users,tracking,read_date) VALUES  ('','{$current_time}','0','{$pm_subject}','{$pm_message}','1','in','{$To}','{$To}', 'NULL', 'NULL', 'NULL', '0', 'NULL')");
$member['msg_msg_id'] = $auto_pm_messageid;
$member['msg_total'] += 1;
$member['show_popup'] = $ibforums->vars['show_pm_popup'];
$DB->query("update ibf_members set msg_from_id='1',msg_msg_id='{$member['msg_msg_id']}',new_msg='1',msg_total=msg_total+'1',show_popup='1' where id='{$To}'");
}

function P1Win($id){
global $DB, $ibforums, $std, $print;
// Battle
$DB->query("SELECT * FROM ibf_infernobattle where id='{$id}'");
$battle=$DB->fetch_row();
// p2 is our loser, so lets send him a loser pm! :P
$To=$battle['p2'];
$DB->query("select * from ibf_members where id='{$battle['p1']}'");
$x=$DB->fetch_row();
$Title="Battle Lost";
$Message="[Auto Message 'DO NOT REPLY'] - I am sorry to inform you, but your battle you have been playing has ended,\nseems ".$x['name']." was too strong for you and you have lost. Please go to the healing center to revive yourself";
$this->Send_PM($Title,$Message,$To);



$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$loser=$DB->fetch_row();
$loss=$loser['loss']+1;

// I feel like some doller tonight, dollar tonight
$take=$loser['money']/10;
$take=(int)$take;
$take=$loser['money']-$take;
$DB->query("SELECT * FROM ibf_members where id='{$battle['p1']}'");
$winner=$DB->fetch_row();

$log="{$loser['name']} lost there battle against {$winner['name']}";
$type="Battle Ground";
$this->AddLog($log,$type);

$vic=$winner['vics']+1;
$give=$winner['money']+$take;
$DB->query("update ibf_members set money='{$give}',vics='{$vic}',exp=exp+'10' where id='{$battle['p1']}'");
$DB->query("update ibf_members set money='{$take}' where id='{$battle['p2']}'");

$DB->query("update ibf_members set loss='{$loss}',inbattle='0',hp='0' where id='{$battle['p2']}'");
$DB->query("update ibf_members set inbattle='0' where id='{$battle['p1']}'");

echo "<script>alert('Congratulations, You won your battle and you found {$take} {$ibforums->lang['money']} on your opponents body');</script>";

// Remove the battle
$DB->query("delete from ibf_infernobattle where id='{$battle['id']}'");
$print->redirect_screen("You Won Your Battle!", "act=RPG&CODE=Battle",0);
}

function P2Win($id){
global $DB, $ibforums, $std, $print;
// Battle
$DB->query("SELECT * FROM ibf_infernobattle where id='{$id}'");
$battle=$DB->fetch_row();
// p1 is our loser, so lets send him a loser pm! :P
$To=$battle['p1'];
$DB->query("select * from ibf_members where id='{$battle['p2']}'");
$x=$DB->fetch_row();
$Title="Battle Lost";
$Message="[Auto Message 'DO NOT REPLY'] - I am sorry to inform you, but your battle you have been playing has ended,\nseems ".$x['name']." was too strong for you and you have lost. Please go to the healing center to revive yourself";
$this->Send_PM($Title,$Message,$To);

$DB->query("SELECT * FROM ibf_members where id='{$battle['p1']}'");
$loser=$DB->fetch_row();
$loss=$loser['loss']+1;

// I feel like some doller tonight, dollar tonight
$take=$loser['money']/10;
$take=(int) $take;
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$winner=$DB->fetch_row();

$log="{$loser['name']} lost there battle against {$winner['name']}";
$type="Battle Ground";
$this->AddLog($log,$type);

$vic=$winner['vics']+1;
$give=$winner['money']+$take;
$DB->query("update ibf_members set money='{$give}',vics='{$vic}',exp=exp+'10' where id='{$battle['p2']}'");
$DB->query("update ibf_members set money='{$take}' where id='{$battle['p1']}'");

$DB->query("update ibf_members set loss='{$loss}',inbattle='0',hp='0' where id='{$battle['p1']}'");
$DB->query("update ibf_members set inbattle='0' where id='{$battle['p2']}'");

echo "<script>alert('Congratulations, You won your battle and you found {$take} {$ibforums->lang['money']} on your opponents body');</script>";

// Remove the battle
$DB->query("delete from ibf_infernobattle where id='{$battle['id']}'");
$print->redirect_screen("You Won Your Battle!", "act=RPG&CODE=Battle",0);
}

function healchar(){
global $DB, $ibforums, $std, $print;
$id=$ibforums->input['id'];
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
if($member['heal1']==$id){
$DB->query("update ibf_members set heal1='' where id='{$member['id']}'");
} else if($member['heal2']==$id){
$DB->query("update ibf_members set heal2='' where id='{$member['id']}'");
} else if($member['heal3']==$id){
$DB->query("update ibf_members set heal3='' where id='{$member['id']}'");
} else {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'noheal' ) );
}

$DB->query("SELECT * FROM ibf_infernoheal where id='{$id}'");
$Data = $DB->fetch_row();
$hp=$Data['hp'];
$mp=$Data['mp'];

$hp=$hp+$member['hp'];
$mp=$mp+$member['mp'];
if($hp > $member['hpm']){
$hp=$member['hpm'];
}
if($mp > $member['mpm']){
$mp=$member['mpm'];
}

$DB->query("update ibf_members set hp='{$hp}',mp='{$mp}' where id='{$member['id']}'");
$log="{$member['name']} used {$Data['name']} and put there hp up to {$hp} and mp up to {$mp}";
$type="Healing Center";
$this->AddLog($log,$type);
$print->redirect_screen("You have been healed", "act=RPG&CODE=Heal",0);
}

function ViewAllBattles(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['battleon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

$this->output .= $this->html->vabt();
$DB->query("SELECT b.*
FROM ibf_infernobattle b
order by `id`
");
$count=0;
while($battles=$DB->fetch_row()){
$count++;
$players=$battles['vs'];
$this->output .= $this->html->vabr($battles,$players);
}

$this->output .= $this->html->vabb();

$this->page_title = "Viewing All Battles";
$this->nav        = array( 
"Viewing All Battles",
 );

$this->output .= $this->html->Copyright();
}

function ViewBattle(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['battleon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

$DB->query("SELECT * FROM ibf_infernobattle where id='{$ibforums->input['id']}'");

$battles=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_members where id='{$battles['p1']}'");
$p1=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_members where id='{$battles['p2']}'");
$p2=$DB->fetch_row();


if($p1['rpgav']==""){$p1['rpgav']="<img src='html/Inferno/scene/blank.gif'>";}else{$p1['rpgav']="<img src='{$p1['rpgav']}' width='{$p1['rpaw']}' height='{$p1['rpah']}'>";}
if($p2['rpgav']==""){$p2['rpgav']="<img src='html/Inferno/scene/blank.gif'>";}else{$p2['rpgav']="<img src='{$p2['rpgav']}' width='{$p2['rpaw']}' height='{$p2['rpah']}'>";}
$this->output .= $this->html->vbr($battles,$p1,$p2);

// Battle Log - who did what
$this->output .= $this->html->BLogT();
$DB->query("SELECT * FROM ibf_battlelog where bid='{$battles['id']}'");
while($blog=$DB->fetch_row()){
$this->output .= $this->html->BLogR($blog);
}
$this->output .= $this->html->BLogB();

$this->page_title = "Viewing Battle {$p1['name']} Vs. {$p2['name']}";
$this->nav        = array( 
"<a href='?act=RPG&CODE=VAB'>View All Battles</a>",
 "Viewing Battle {$p1['name']} Vs. {$p2['name']}",);

$this->output .= $this->html->Copyright();
}

function StatsAll(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['rpgstatson']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// Newest Battle
$DB->query("SELECT MAX(id) as newbattle FROM ibf_infernobattle");
$n = $DB->fetch_row();
$this->output .= $this->html->NewestBattle($n);
// Top Killers
$DB->query("SELECT * FROM ibf_members WHERE `vics` > 0 ORDER BY `vics` DESC LIMIT 0,10");
$this->output .= $this->html->TKT();
$i=0;
while($r = $DB->fetch_row()){
$i++;
$r['rankc']=$i;
$this->output .= $this->html->TKR($r);
}
$this->output .= $this->html->TKB();
// Richest Members
$DB->query("SELECT * FROM ibf_members WHERE `money` > 0 ORDER BY `money` DESC LIMIT 0,10");
$this->output .= $this->html->RMT();
$i=0;
while($r = $DB->fetch_row()){
$i++;
$r['rankc']=$i;
$this->output .= $this->html->RMR($r);
}
$this->output .= $this->html->RMB();
// Popular Items
$DB->query("SELECT * FROM ibf_infernoshop WHERE `sold` > 0 ORDER BY `sold` DESC LIMIT 0,10");
$this->output .= $this->html->PIT();
$i=0;
while($r = $DB->fetch_row()){
$i++;
$r['rankc']=$i;
$this->output .= $this->html->PIR($r);
}
$this->output .= $this->html->PIB();


// Page end
$this->page_title = "RPG Statistics";
$this->nav        = array( 
"RPG Statistics",
 );

$this->output .= $this->html->Copyright();
}

function Lottery(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['lotteryon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// Get current lotterys
$this->output .= $this->html->LT();
$DB->query("SELECT * FROM ibf_lottery");
while($lottery=$DB->fetch_row()){
$this->output .= $this->html->LR($lottery);
}
$this->output .= $this->html->LB();

// Page end
$this->page_title = "Lottery";
$this->nav        = array( 
"Lottery",
 );

$this->output .= $this->html->Copyright();
}

function BuyTick(){
global $DB, $ibforums, $std, $print;

// which lottery are we buying from?
$DB->query("SELECT * FROM ibf_lottery where id='{$ibforums->input['id']}'");
$lottery=$DB->fetch_row();
// open?
if($lottery['state']=="Not Drawn"){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'lot_drawn' ) );
}
// Your tickets
$DB->query("SELECT * FROM ibf_tickets where mid='{$ibforums->member['id']}'");
while($ticket=$DB->fetch_row()){
if($ticket['lname']==$lottery['name']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'tick_bought' ) );
}}
// Enough money?
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
if($member['money'] < $lottery['tcost']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'nemft' ) );
}
// ok, display the shit

$this->output .= $this->html->BuyTicket($lottery);

// Page end
$this->page_title = "Buy Ticket";
$this->nav        = array( 
"<a href='?act=RPG&CODE=Lottery'>Lottery</a>",
"Buy Ticket",
 );

$this->output .= $this->html->Copyright();

// end
}

function do_BuyTick(){
global $DB, $ibforums, $std, $print;

$DB->query("SELECT * FROM ibf_lottery where id='{$ibforums->input['id']}'");
$lottery=$DB->fetch_row();
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$n1=$ibforums->input['n1'];
$n2=$ibforums->input['n2'];
$n3=$ibforums->input['n3'];
$n4=$ibforums->input['n4'];
$n5=$ibforums->input['n5'];

// uhm? where all clear i guess
$cost=$member['money']-$lottery['tcost'];
$DB->query("update ibf_members set money='{$cost}' where id='{$member['id']}'");

// give em the ticket :P
$DB->query("insert into ibf_tickets VALUES ('','{$member['id']}','{$n1}','{$n2}','{$n3}','{$n4}','{$n5}','{$lottery['name']}')");
$log="{$member['name']} bought a lottery ticket for the lottery {$lottery['name']}";
$type="Lottery";
$this->AddLog($log,$type);

// ticket inserted
$print->redirect_screen("Ticket Bought", "act=RPG&CODE=Lottery",0);
}

function Check_Numbers(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_lottery where id='{$ibforums->input['id']}'");
$lottery=$DB->fetch_row();
// the lottery is drawn?
if($lottery['state']=="Not Drawn"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'not_drawn' ) );
}
$t1=$lottery['n1'];
$t2=$lottery['n2'];
$t3=$lottery['n3'];
$t4=$lottery['n4'];
$t5=$lottery['n5'];
$DB->query("SELECT * FROM ibf_members where id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
$i=0;
$DB->query("SELECT * FROM ibf_tickets where mid='{$member['id']}'");
while($ticket=$DB->fetch_row()){
if($ticket['lname']==$lottery['name']){
$TheTicket=$ticket['id'];
// this is our ticket :P
$n1=$ticket['n1'];
$n2=$ticket['n2'];
$n3=$ticket['n3'];
$n4=$ticket['n4'];
$n5=$ticket['n5'];
if($n1==$t1){
$i=$i+1;
} else if($n1==$t2){
$i=$i+1;
} else if($n1==$t3){
$i=$i+1;
} else if($n1==$t4){
$i=$i+1;
} else if($n1==$t5){
$i=$i+1;
}
if($n2==$t1){
$i=$i+1;
} else if($n2==$t2){
$i=$i+1;
} else if($n2==$t3){
$i=$i+1;
} else if($n2==$t4){
$i=$i+1;
} else if($n2==$t5){
$i=$i+1;
}
if($n3==$t1){
$i=$i+1;
} else if($n3==$t2){
$i=$i+1;
} else if($n3==$t3){
$i=$i+1;
} else if($n3==$t4){
$i=$i+1;
} else if($n3==$t5){
$i=$i+1;
}
if($n4==$t1){
$i=$i+1;
} else if($n4==$t2){
$i=$i+1;
} else if($n4==$t3){
$i=$i+1;
} else if($n4==$t4){
$i=$i+1;
} else if($n4==$t5){
$i=$i+1;
}
if($n5==$t1){
$i=$i+1;
} else if($n5==$t2){
$i=$i+1;
} else if($n5==$t3){
$i=$i+1;
} else if($n5==$t4){
$i=$i+1;
} else if($n5==$t5){
$i=$i+1;
}
// ender++
}}
// Total Percent
$i=$i*2;
$i=$i*10;

$log="{$member['name']} checked lottery numbers and scored {$i}%";
$type="Lottery";
$this->AddLog($log,$type);

if($i>=80){
// we win!
// first - remove the ticket
$DB->query("delete FROM ibf_tickets where id='{$TheTicket}'");
// winnings please :P
$DB->query("update ibf_members set money=money+'{$lottery['prize']}' where id='".$ibforums->member['id']."'");
$print->redirect_screen("You Scored ".$i."% And Won!", "act=RPG&CODE=Lottery",0);
} else {
// sorry, you lose
// first - remove the ticket
$DB->query("delete FROM ibf_tickets where id='{$TheTicket}'");
$print->redirect_screen("You Scored ".$i."% And Didn't Win", "act=RPG&CODE=Lottery",0);
}
}

function Verify(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_infernobattle where id='{$ibforums->input['id']}'");
$battle=$DB->fetch_row();
// are you the veriyee 0.o
if($ibforums->member['id']==$battle['verby']){}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_veri' ) );
}
// our answer?
$ans=$ibforums->input['ans'];
if($ans=="yes"){
// lets battle
$DB->query("update ibf_infernobattle set verify='yes' where id='{$ibforums->input['id']}'");
$log="{$ibforums->member['name']} verified a battle";
$type="RPG Battle";
$this->AddLog($log,$type);

$To=$battle['p1'];
$DB->query("select * from ibf_members where id='{$battle['p2']}'");
$x=$DB->fetch_row();
$Title="Battle Verification";
$Message="[Auto Message 'DO NOT REPLY'] - Your battle has been verified by {$x['name']}";
$this->Send_PM($Title,$Message,$To);

$print->redirect_screen("Battle Online", "act=RPG&CODE=Battle",0);
} else {
// lets not... remove battle - and players
$DB->query("update ibf_members set inbattle='' where id='{$battle['p1']}'");
$DB->query("update ibf_members set inbattle='' where id='{$battle['p2']}'");
$DB->query("delete from ibf_infernobattle where id='{$battle['id']}'");

$log="{$ibforums->member['name']} didn\'t accept battle";
$type="RPG Battle";
$this->AddLog($log,$type);

$To=$battle['p1'];
$DB->query("select * from ibf_members where id='{$battle['p2']}'");
$x=$DB->fetch_row();
$Title="Battle Verification";
$Message="[Auto Message 'DO NOT REPLY'] - Your battle has not been accepted by {$x['name']}";
$this->Send_PM($Title,$Message,$To);

$print->redirect_screen("Battle Removed", "act=RPG&CODE=Battle",0);
}
// verify end
}

function Surrender(){
global $DB, $ibforums, $std, $print;
// surrender from which battle?
$DB->query("SELECT * FROM ibf_infernobattle where id='{$ibforums->input['id']}'");
$battle=$DB->fetch_row();
// now lets get you
$DB->query("SELECT * FROM ibf_members where id='{$ibforums->member['id']}'");
$member=$DB->fetch_row();
// p1 or p2?
if($member['id']==$battle['p1']){$s="p1";}else if($member['id']==$battle['p2']){$s="p2";}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'sur_error' ) );
}
// opponent - winner in this case :P
if($s=="p1"){
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$winner=$DB->fetch_row();
} else {
$DB->query("SELECT * FROM ibf_members where id='{$battle['p2']}'");
$winner=$DB->fetch_row();
}
// step 1 - remove battle players
$DB->query("update ibf_members set inbattle='' where id='{$battle['p1']}'");
$DB->query("update ibf_members set inbattle='' where id='{$battle['p2']}'");
// step 2 - remove battle
$DB->query("delete FROM ibf_infernobattle where id='{$ibforums->input['id']}'");
// step 3 - take 5% banked and on hand money from the member
$onhand=($member['money']/100)*5;
$onhand=(int)$onhand;
$inbank=($member['bankmoney']/100)*5;
$inbank=(int)$inbank;
$member['money']=$member['money']-$onhand;
$member['bankmoney']=$member['bankmoney']-$inbank;
$DB->query("update ibf_members set money='{$member['money']}',bankmoney='{$member['bankmoney']}',loss=loss+'1' where id='{$member['id']}'");
// step 4 - give winner money + victory increase :P
$winner['money']=$winner['money']+$onhand;
$winner['bankmoney']=$winner['bankmoney']+$inbank;
$DB->query("update ibf_members set money='{$winner['money']}',bankmoney='{$winner['bankmoney']}',vics=vics+'1' where id='{$winner['id']}'");

$log="{$member['name']} surrendered his/her battle against {$winner['name']}";
$type="RPG Battle";
$this->AddLog($log,$type);

$print->redirect_screen("You Surrendered ".$member['name'], "act=RPG&CODE=Battle",0);
}

function Store(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['storeon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

$this->output .= $this->html->StoreTop();
// get store items
$DB->query("SELECT * FROM ibf_infernostore order by id");
while($sitems=$DB->fetch_row()){
$this->output .= $this->html->StoreRow($sitems);
}

$this->output .= $this->html->StoreBottom();
// Page end
$this->page_title = "RPG Store";
$this->nav        = array( 
"RPG Store",
 );
$this->output .= $this->html->Copyright();
// end
}

function StoreBuy(){
global $DB, $ibforums, $std, $print;
// get store item
$DB->query("SELECT * FROM ibf_infernostore where id='{$ibforums->input['id']}'");
$sitems=$DB->fetch_row();
if($sitems['stock'] == "0"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'sold_out' ) );
}
$file=$ibforums->vars['base_dir']."sources/Store_Items/".$sitems['rfile'];
// check file exists
if(!file_exists($file)) {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_file' ) );
}
require($file);
$item = new item;
$this->output .= $item->display();
// Page end
$this->page_title = "Buying Store Item";
$this->nav        = array( 
"Buying Store Item",
 );
$this->output .= $this->html->Copyright();
// end
}

function StoreBuydo(){
global $DB, $ibforums, $std, $print;
// get store item
$DB->query("SELECT * FROM ibf_infernostore where id='{$ibforums->input['id']}'");
$sitems=$DB->fetch_row();
if($sitems['stock'] == "0"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'sold_out' ) );
}
$file=$ibforums->vars['base_dir']."sources/Store_Items/".$sitems['rfile'];
// check file exists
if(!file_exists($file)) {
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_file' ) );
}
require($file);
$item = new item;
$log="{$ibforums->member['name']} used item {$sitems['name']}";
$type="RPG Store";
$this->AddLog($log,$type);
$item->UseItem($sitems);
}

function Help(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['helpeon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// get help files
$DB->query("SELECT * FROM ibf_infernohelp");
$this->output .= $this->html->HelpTop();
while($help=$DB->fetch_row()){
$this->output .= $this->html->HelpRow($help);
}
$this->output .= $this->html->HelpBottom();

// Page end
$this->page_title = "RPG Help";
$this->nav        = array( 
"RPG Help",
 );
$this->output .= $this->html->Copyright();
// end
}

function ViewHelp(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['helpeon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

// get help files
$DB->query("SELECT * FROM ibf_infernohelp where id='{$ibforums->input['id']}'");
$help=$DB->fetch_row();
$this->output .= $this->html->HelpContent($help);

// Page end
$this->page_title = "RPG Help - ".$help['name'];
$this->nav        = array( 
"<a href='?act=RPG&CODE=Help'>RPG Help</a>",
$help['name'],
 );
$this->output .= $this->html->Copyright();
// end
}


//Job Addon (by CTM)
function ViewJobs(){
global $DB, $ibforums, $std, $print;

// are we offline? v2 //
$DB->query("SELECT * FROM ibf_rpgoptions where id='1'");
$options=$DB->fetch_row();
if($options['rpgjobon']=="Offline"){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'section_offline' ) );
}

//Our Job
$this->output .= $this->html->OurJobTop();
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();


if($member['job']==""){}else{
$DB->query("SELECT * FROM ibf_infernojobs WHERE name='{$member['job']}'");
$job=$DB->fetch_row();

$interval[]=array('3600','Every Hour');
$interval[]=array('86400','Every Day');
$interval[]=array('172800','Every Two Days');
$interval[]=array('604800','Every Week');
$interval[]=array('1209600','Every Two Weeks');
$interval[]=array('2419200','Every Month');

for($i=0;$i<6;$i++){
if($job['sinterval']==$interval[$i][0]){
$job['sinterval']=$interval[$i][1];
}}

$this->output .= $this->html->OurJob($job);
}

$this->output .=$this->html->OurJobBottom();

$this->output .= $this->html->ViewJobTop();
$DB->query("SELECT * FROM ibf_infernojobs");
while ($data = $DB->fetch_row() ) {
$interval[]=array('3600','Every Hour');
$interval[]=array('86400','Every Day');
$interval[]=array('172800','Every Two Days');
$interval[]=array('604800','Every Week');
$interval[]=array('1209600','Every Two Weeks');
$interval[]=array('2419200','Every Month');
for($i=0;$i<6;$i++){
if($data['sinterval']==$interval[$i][0]){
$data['sinterval']=$interval[$i][1];
}}
$this->output .= $this->html->ViewJob($data);
}
$DB->query("SELECT * FROM ibf_infernojobs WHERE name='{$member['job']}'");
$data=$DB->fetch_row();
$this->output .= $this->html->ViewJobBottom($data);

$this->page_title = "Viewing Available Jobs";
$this->nav        = array( "<a href='?act=RPG&CODE=ViewJobs'>View Jobs</a>", "Viewing available jobs");
$this->output .= $this->html->Copyright();
}

function PickJob(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernojobs WHERE id='".$ibforums->input['id']."'");
$data=$DB->fetch_row();

if($member['level'] < $data['lvl']){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'joblvl' ) );
}

if($member['job']==""){
$job=$data['name'];



$DB->query("UPDATE ibf_members SET job='{$job}' WHERE id='".$ibforums->member['id']."'");

}else{

$std->Error( array( 'LEVEL' => 1, 'MSG' => 'aljob' ) );

}
$log="{$member['name']} got a job as a {$job}";
$type="RPG Job";
$this->AddLog($log,$type);
$print->redirect_screen("You are now a {$job}", "act=RPG&CODE=ViewJobs",0);

}

function LeaveJob(){
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernojobs WHERE name='".$ibforums->member['job']."'");
$data=$DB->fetch_row();

$DB->query("UPDATE ibf_members SET job='' WHERE id='".$ibforums->member['id']."'");
$log="{$member['name']} left his job ({$member['job']})";
$type="RPG Job";
$this->AddLog($log,$type);
$print->redirect_screen("you are now a bummer! Long live the non workers!", "act=RPG&CODE=ViewJobs",0);

}

function CollectSalary() {
global $DB, $ibforums, $std, $print;
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$DB->query("SELECT * FROM ibf_infernojobs WHERE id='".$ibforums->input['id']."'");
$data=$DB->fetch_row();

$lastpay=$member['last_jpay'];
$salary=$data['salary'];
$time=time();
$money=$member['money'];
$rate=$data['sinterval'];
$checktime=$time - $lastpay;

//Begin Rates!
if($checktime > $rate) {
$nowmoney= ($money + $salary);
$DB->query("UPDATE ibf_members SET money='{$nowmoney}',last_jpay='".time()."' WHERE id='".$ibforums->member['id']."'");
$print->redirect_screen("your salary has been collected!", "act=RPG&CODE=ViewJobs",0);
}else{
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'noyetpay' ) );
}
//Rate finished! Wohooo!
}

function AddLog($log,$type){
global $DB, $ibforums, $std, $print;
// lets see what people are doing eh
$DB->query("insert into ibf_infernologs values('','{$log}','{$type}','".time()."')");
}

function EquipCharacter(){
global $DB, $ibforums, $std, $print;
// lets see what we have currently equiped and what cats we can equip into
$this->output .= $this->html->EquipTop();
// catagories
$DB->query("select * from ibf_infernocat");
while($r=$DB->fetch_row()){
$this->output .= $this->html->EquipCatRow($r);
}
$this->output .= $this->html->EquipMiddleRow();
// currently equipped items
$DB->query("select e.*,i.* from ibf_infernoequip e
left join ibf_infernoshop i ON (e.eitem=i.id)
where e.eowner='{$ibforums->member['id']}'");
while($r=$DB->fetch_row()){
$this->output .= $this->html->EquipRow($r);
}
$this->output .= $this->html->EquipEnd();

$this->page_title = "Equip Your Character";
$this->nav        = array( "Equip Your Character");
$this->output .= $this->html->Copyright();
}

function EquipType(){
global $DB, $ibforums, $std, $print;
$this->output .= $this->html->EquipTTop();
$DB->query("SELECT i.*,s.*
FROM ibf_infernostock s
LEFT JOIN ibf_infernoshop i ON (s.item=i.id)
where s.owner='{$ibforums->member['id']}' and i.type='{$ibforums->input['type']}'");
while($r=$DB->fetch_row()){
$this->output .= $this->html->EquipTRow($r);
}
$this->output .= $this->html->EquipEnd();

$this->page_title = "Equip";
$this->nav        = array( "<a href='?act=RPG&CODE=Equip'>Equip Your Character</a>",
"Equip");
$this->output .= $this->html->Copyright();
}

function doEquip(){
global $DB, $ibforums, $std, $print;
// member
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();
// check we own this item
$DB->query("select * from ibf_infernostock where item='{$ibforums->input['id']}' and owner='{$ibforums->member['id']}'");
if($DB->get_num_rows() == 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_own_item' ) );
}

// item data
$DB->query("select * from ibf_infernoshop where id='{$ibforums->input['id']}'");
$item=$DB->fetch_row();

// have we already equipped in this catagory?
$DB->query("select * from ibf_infernoequip where eowner='{$ibforums->member['id']}' and ecat='{$item['type']}'");
if($DB->get_num_rows() == 0){
// we are new
$DB->query("insert into ibf_infernoequip values('','{$ibforums->input['id']}','{$ibforums->member['id']}','{$item['type']}')");

// someone made a new method for items giving stats - its cool and i should of though of it >_< *kicks self*


$member['hp']+=$item['hp'];
$member['hpm']+=$item['hp'];
$member['mp']+=$item['mp'];
$member['mpm']+=$item['mp'];
$member['str']+=$item['str'];
$member['def']+=$item['def'];
$DB->query("update ibf_members set hp='{$member['hp']}',hpm='{$member['hpm']}',mp='{$member['mp']}',mpm='{$member['mpm']}',str='{$member['str']}',def='{$member['def']}' where id='".$ibforums->member['id']."'");

}else{


// replace old equip - looks like we will have to remove the old stats before adding the new ones
$DB->query("select * from ibf_infernoequip where eowner='{$ibforums->member['id']}' and ecat='{$item['type']}'");
$olditemx=$DB->fetch_row();
$DB->query("select * from ibf_infernoshop where id='{$olditemx['eitem']}'");
$olditem=$DB->fetch_row();

$member['hp']-=$olditem['hp'];
$member['hpm']-=$olditem['hp'];
$member['mp']-=$olditem['mp'];
$member['mpm']-=$olditem['mp'];
$member['str']-=$olditem['str'];
$member['def']-=$olditem['def'];
$DB->query("update ibf_members set hp='{$member['hp']}',hpm='{$member['hpm']}',mp='{$member['mp']}',mpm='{$member['mpm']}',str='{$member['str']}',def='{$member['def']}' where id='".$ibforums->member['id']."'");


$DB->query("update ibf_infernoequip set eitem='{$ibforums->input['id']}' where eowner='{$ibforums->member['id']}' and ecat='{$item['type']}'");

// someone made a new method for items giving stats - its cool and i should of though of it >_< *kicks self*

// old stats removed - item repalced, increase stats with new item
$member['hp']+=$item['hp'];
$member['hpm']+=$item['hp'];
$member['mp']+=$item['mp'];
$member['mpm']+=$item['mp'];
$member['str']+=$item['str'];
$member['def']+=$item['def'];
$DB->query("update ibf_members set hp='{$member['hp']}',hpm='{$member['hpm']}',mp='{$member['mp']}',mpm='{$member['mpm']}',str='{$member['str']}',def='{$member['def']}' where id='".$ibforums->member['id']."'");

}

$print->redirect_screen("Item Equiped", "act=RPG&CODE=Equip",0);
}

function doUnEquip(){
global $DB, $ibforums, $std, $print;
// grab item
$DB->query("select * from ibf_infernoshop where id='{$ibforums->input['id']}'");
$item=$DB->fetch_row();
// check we own this item
$DB->query("select * from ibf_infernostock where item='{$ibforums->input['id']}' and owner='{$ibforums->member['id']}'");
if($DB->get_num_rows() == 0){
$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_own_item' ) );
}
// have we already equipped in this catagory?
$DB->query("delete from ibf_infernoequip where eitem='{$ibforums->input['id']}' and eowner='{$ibforums->member['id']}'");

// someone made a new method for items giving stats - its cool and i should of though of it >_< *kicks self*
$DB->query("SELECT * FROM ibf_members WHERE id='".$ibforums->member['id']."'");
$member=$DB->fetch_row();

$member['hp']-=$item['hp'];
if($member['hp'] < 0){$member['hp']=0;}
$member['hpm']-=$item['hp'];
$member['mp']-=$item['mp'];
if($member['mp'] < 0){$member['mp']=0;}
$member['mpm']-=$item['mp'];
$member['str']-=$item['str'];
$member['def']-=$item['def'];
$DB->query("update ibf_members set hp='{$member['hp']}',hpm='{$member['hpm']}',mp='{$member['mp']}',mpm='{$member['mpm']}',str='{$member['str']}',def='{$member['def']}' where id='".$ibforums->member['id']."'");

$print->redirect_screen("Item Unequiped", "act=RPG&CODE=Equip",0);
}


// End Class
}
?>