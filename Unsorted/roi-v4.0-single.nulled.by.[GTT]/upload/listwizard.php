<?php
include("init.php");
include('smalltemplater.php');
Assign('image_label','label-campaigns.gif');
$myerror = array();

if (isset($_POST['id'])) {$campaign_id = $_POST['id'];} else {$campaign_id = $_GET['id'];}
if (empty($campaign_id)) pError("Error Campaign Identeficator","Not found ROI Campaign with this Identeficator");

if (isset($_POST['step'])) {$step = $_POST['step'];} else {$campaign_id = $_GET['step'];}
if (empty($step)) $step = 0;

if(isset($HTTP_POST_VARS['finish_x']) || strlen($HTTP_POST_VARS['finish_x'])>0 )
{
Finish();
Header('Location: campaigns.php?pid=editcampaign&id='.$_POST['id']);
}

if(isset($HTTP_POST_VARS['addlisting_x']) || strlen($HTTP_POST_VARS['addlisting_x'])>0 )
{
Finish();
$campaign_id = $_POST['id'];
foreach ($_POST as $key)
{
unset($_POST[$key]);
}
Header('Location: listwizard.php?id='.$campaign_id);
}

if(isset($HTTP_POST_VARS['prev1_x']) || strlen($HTTP_POST_VARS['prev1_x'])>0 )
{
unset($_POST['step']);
unset($_POST['prev1_x']);
unset($_POST['prev1_y']);
unset($_POST['prev1']);
unset($_POST['all']);
unset($_POST['average_price']);
unset($_POST['price_type']);
unset($_POST['al']);
unset($_POST['newprice']);
 foreach ($_POST['price'] as $key=>$value)
 {
  unset($_POST['price'][$key]);
 }
 foreach ($_POST['key'] as $k=>$v)
 {
  unset($_POST['key'][$k]);
 }
ShowTemplate(Template('templates/list1.htm',''));
}


if(isset($HTTP_POST_VARS['prev2_x']) || strlen($HTTP_POST_VARS['prev2_x'])>0 )
{
unset($_POST['step']);
unset($_POST['prev1_x']);
unset($_POST['prev1_y']);
unset($_POST['prev1']);
unset($_POST['prev2_x']);
unset($_POST['prev2_y']);
unset($_POST['prev2']);
unset($_POST['all']);
unset($_POST['average_price']);
unset($_POST['al']);
unset($_POST['newprice']);

if ($_POST['price_type']==1)
{
 foreach ($_POST['price'] as $key=>$value)
 {
  unset($_POST['price'][$key]);
 }
 foreach ($_POST['key'] as $k=>$v)
 {
  unset($_POST['key'][$k]);
 }
ShowTemplate(Template('templates/list1.htm',''));
}
else
{
Step2();
}
}


function Hiddenfields(){
global $donthide;
  $rv = '';

  foreach( $_GET as $key => $val ){
    $rv .= "<input type=hidden name=$key value=\"$val\">\n";
  }
  foreach($_POST as $key => $val ){
  if (($key != 'key')&&($key != 'price'))
    $rv .= "<input type=hidden name=$key value=\"$val\">\n";
  }

if (!$donthide)
{
  foreach($_POST['key'] as $key){
    $rv .= "<input type=hidden name='key[]' value=\"$key\">\n";
  }

  foreach($_POST['price'] as $key){
    $rv .= "<input type=hidden name='price[]' value=\"$key\">\n";
  }
}
 return $rv;
}


function addError($val){ global $myerror; $myerror[sizeof($myerror)] = $val; }
function outError()
{
global $myerror;
$rv = '<table border=0><tr><td>';
         foreach ( $myerror as $key => $val ){
         $rv .= "<img src=\"images/bullet.gif\" width=16 height=10 border=0 align=absmiddle><span class=title><font color=#A70000>$val</font></span><br>";}
         return $rv.'</td></tr></table>';
}



function Template($template,$add){
  global $HTTP_POST_VARS;
  $file = File( $template );
  $file = implode( '', $file );
  $file = str_replace( "%hiddenfields%", Hiddenfields(), $file);

    foreach( $_POST as $key => $val)
    {
     $file = str_replace("%$key%",$val,$file);
    }

  return "<table border=0 width=100% height=100%><tr><td align=center valign=center>".outError().$file.$add."</td></tr></table>";
}

function Step0()
{
   $_POST['listing_name'] = '';
   $_POST['keyword_price'] = '';
   $_POST['type1'] = 'checked';
   $_POST['type2'] = '';
   $_POST['keywords'] = '';

   ShowTemplate(Template('templates/list1.htm',''));
}

function Step1()
{
global $step;
$error = false;
   if ($_POST['price_type']==1)
    {   $_POST['type1'] = 'checked';
        $_POST['type2'] = '';
    }
   else
    {   $_POST['type1'] = '';
        $_POST['type2'] = 'checked';
    }

   if (empty($_POST['listing_name'])) {addError('Enter listing name'); $error = true;}

if ($error)
{
   ShowTemplate(Template('templates/list1.htm',''));
}
else
{
  if ($_POST['price_type']==1)
  {
   $price = floatval($_POST['keyword_price']);
   $keyarr = explode("\n",$_POST['keywords']);

   $i = 0;
    foreach ($keyarr as $key)
    {
    if (strlen(trim($key))>0)
    {
     $_POST['key'][$i] = trim($key);
     $_POST['price'][$i] = $price;
     $i++;
    }
    }

   Step3();
  }
  else
  {
  $price = floatval($_POST['keyword_price']);
   $keyarr = explode("\n",$_POST['keywords']);

   $i = 0;
    foreach ($keyarr as $key)
    {
    if (strlen(trim($key))>0)
    {
     $_POST['key'][$i] = $key;
     $_POST['price'][$i] = 0;
     $i++;
    }
    }
   Step2();
  }
}

}

function Step2()
{
global $donthide;
$donthide = true;

$i = 0;
$summ = 0;

$add = <<<EOT
<p align=right>
<b>Price:</b>&nbsp;&nbsp;<input type="text" class="txt" size="10" name="newprice">&nbsp;&nbsp;
<a href="#" onclick="ChangePrice();"><img src="images/button-apply-selected.gif" border=0></a><br><br>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="2%" class="cellheader"><input type=checkbox name=al onClick="SelectAllKws();"></td>
<td width="78%" class="cellheader"><a href="#">Keyword</a></td>
<td width="20%" class="cellheader"><a href="#">Price</a></td>
</tr>
EOT;

foreach ($_POST['key'] as $key)
{
 if (($i/2)==intval($i/2)) {$cell_type = 'cell1';} else {$cell_type='cell2';}
 $add .="<tr class=$cell_type>
        <td><input type=checkbox id=kr></td>
        <td><input type=hidden name='key[]' value='$key'>$key</td>
        <td>$<input name='price[]' id='price' type=text class=txt size=3 value='".$_POST['price'][$i]."'></td></tr>";
 $i++;
}
$_POST['all'] = $i;

$add .= <<<EOT
</table>
<br>
<p align="right">
<input type=image src="images/button-previous.gif" name=prev1 value=prev title=Previous>
&nbsp;&nbsp;&nbsp;
<input type=image src="images/button-next.gif" name=next value=new title="Add action">
</p>
</td></tr></table></td></tr><tr><td align=right>

</td></tr></table></td></tr></table></form>
EOT;

 ShowTemplate(Template('templates/list2.htm',$add));
}

function Step3()
{

$i = 0;
$summ = 0;

$add = <<<EOT
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="80%" class="cellheader"><a href="#">Keyword</a></td>
<td width="20%" class="cellheader"><a href="#">Price</a></td>
</tr>
EOT;

foreach ($_POST['key'] as $key)
{
 if (($i/2)==intval($i/2)) {$cell_type = 'cell1';} else {$cell_type='cell2';}
 $add .="<tr class=$cell_type>
        <td>$key</td>
        <td>$".$_POST['price'][$i]."</td></tr>";
 $summ += $_POST['price'][$i];
 $i++;
}

$_POST['average_price'] = '$'.round($summ/$i,2);
$add .= <<<EOT
</table>
<br>
Please check if the information above is correct. If you would like to make any changes, please click Previous to move back to the certain step of this wizard.<br><br>
Please click Add listing to add one more listing to this campaign.<br><br>
Click Finish to add the current listing to campaign and get back to campaign edit panel.
<p align="right">
<input type=image src="images/button-previous.gif" name=prev2 value=prev title=Previous>
&nbsp;&nbsp;&nbsp;
<input type=image src="images/button-add-listing.gif" name=addlisting value=new title="Add action">
&nbsp;&nbsp;&nbsp;
<input type=image src="images/button-finish.gif" name=finish value=finish title="Finish">
</p>
</td></tr></table></td></tr><tr><td align=right>

</td></tr></table></td></tr></table></form>
EOT;

 ShowTemplate(Template('templates/list3.htm',$add));
}





function Finish()
{
$campaign_id = intval($_POST['id']);
$listing_name = mysql_escape_string($_POST['listing_name']);

$res = mysql_query("INSERT INTO ROIlistings (listing_name,campaign_id) VALUES ('$listing_name', $campaign_id);");
$listing_id = mysql_insert_id();

$i = 0;
foreach ($_POST['key'] as $key)
{
 $keyword = mysql_escape_string($key);
 $price = floatval($_POST['price'][$i]);
  mysql_query("INSERT INTO ROIkeywords (campaign_id, keyword_name, keyword_price, listing_id) VALUES ($campaign_id,'$keyword',$price,$listing_id)");
 $i++;
}

}



switch ($step)
{
   case 0:
    Step0();
    break;
   case 1:
    Step1();
    break;
   case 2:
    Step3();
    break;
   default:
    Step0();
    break;
}


?>