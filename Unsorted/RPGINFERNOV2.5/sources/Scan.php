<?
// RPG Inferno Version 1
// Created By Zero Tolerance

// Some Global Crap

$idx = new Scan;

class Scan {

var $output     = "";
var $page_title = "";
var $nav        = array();
var $html       = "";
function Scan(){
global $ibforums, $std, $print, $DB;

// Lang - Then Skin - Then Thing :P

$ibforums->lang = $std->load_words($ibforums->lang, 'lang_rpg', $ibforums->lang_id );

$this->html = $std->load_template('skin_scan');

$this->base_url = $ibforums->base_url;

// Data for member we are scanning
$DB->query("SELECT * from ibf_members where id='{$ibforums->input['id']}'");
$scan=$DB->fetch_row();
// What items do we have?
$DB->query("SELECT * from ibf_infernoshop");
$item=$DB->fetch_row();

// html :P
$this->output .= $this->html->ScanTop($scan);

if($scan['rpgav']){
$RPav="<img src='{$scan['rpgav']}' width='{$scan['rpaw']}' height='{$scan['rpah']}'>";
}else{
$RPav="<i>No Avatar</i>";
}

if($scan['rpgrace']){}else{$scan['rpgrace']="<i>None</i>";}
if($scan['rpgname']){}else{$scan['rpgname']="<i>No Name</i>";}

if($scan['inclan']==""){
$scan['inclan']="<i>Not In A Clan</i>";
}else{
$scan['inclan']="<a href='?act=RPG&CODE=ViewClan&clan={$scan['inclan']}'>{$scan['inclan']}</a>";
}
if($scan['job']){}else{$scan['job']="<i>Non working bummer</i>"; }
$this->output .= $this->html->MiniProf($scan,$RPav);

$this->output .= $this->html->ScanMain1($scan);


$DB->query("SELECT i.*,s.*
FROM ibf_infernostock s
LEFT JOIN ibf_infernoshop i ON (s.item=i.id)
where s.owner='{$ibforums->input['id']}'");
while($Item=$DB->fetch_row()){
$this->output .= $this->html->InvRow($Item);
}


$this->output .= $this->html->HealTop($scan);
if($scan['heal1']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$scan['heal1']}'");
$Item=$DB->fetch_row();
$this->output .= $this->html->InvRowH($Item);
}
if($scan['heal2']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$scan['heal2']}'");
$Item=$DB->fetch_row();
$this->output .= $this->html->InvRowH($Item);
}
if($scan['heal3']==""){}else{
$DB->query("SELECT * FROM ibf_infernoheal where id='{$scan['heal3']}'");
$Item=$DB->fetch_row();
$this->output .= $this->html->InvRowH($Item);
}
$this->output .= $this->html->EquipTop($scan);


// currently equipped items
$DB->query("select e.*,i.*
from ibf_infernoequip e
left join ibf_infernoshop i ON (e.eitem=i.id)
where e.eowner='{$scan['id']}'");
while($r=$DB->fetch_row()){
$this->output .= $this->html->InvRowE($r);
}


if($scan['hpm']<1){
$DB->query("update ibf_members SET hp='100',hpm='100',mp='50',mpm='50',def='30',str='30'where id='{$ibforums->input['id']}'");
$print->redirect_screen("Member Updated - Returning", "act=Scan&id={$ibforums->input['id']}",0);
}



// Player Stats
// HP Bar
$hp = $scan['hp'];
$hpm= $scan['hpm'];
$hpa= $hp/$hpm;
$hpa= $hpa*100;
$scan['hpd']=$hpa;
// MP Bar
$mp = $scan['mp'];
$mpm= $scan['mpm'];
$mpa= $mp/$mpm;
$mpa= $mpa*100;
$scan['mpd']=$mpa;
// DEF Bar
$def= $scan['def'];
$defm=$hpm;
$defa=$def/$defm;
$defa=$defa*100;
$scan['defd']=$defa;
// STR Bar
$str= $scan['str'];
$strm=$hpm;
$stra=$str/$strm;
$stra=$stra*100;
$scan['strd']=$stra;

if($scan['strd'] > 100){
$scan['strd']=100;
}

if($scan['defd'] > 100){
$scan['defd']=100;
}

$this->output .= $this->html->InvEnd($scan);
$this->output .= $this->html->ScanMain2();
$this->output .= $this->html->ScanBottom($scan);
$this->output .= $this->html->Copyright();



$this->nav        = array( 
"Scanning {$scan['name']}",
 );

// print data
$print->add_output("$this->output");
$print->do_output( array( 'TITLE' => $ibforums->vars['board_name']." - Scanning {$scan['name']}", 'JS' => 0, 'NAV' => $this->nav ) );
}






// End Class
}
?>