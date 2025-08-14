<?
  require("../settings.php");
  require("../lib/mysql.lib");
  require("_header.php");

$db = c();
if (!$id) {require("_footer.php");exit;}

if (!$rank) $rank=0;

if ($rank)
{
$r=q("UPDATE profiles set type='$rank' where id='$id'");
};
$mem=f(q("select * from members where id='$id'"));
$pr=f(q("select * from profiles where id='$id'"));

?>
<form action="editrank.php" method="post" enctype="multipart/form-data" name="form">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width=50>Member </td>
      <td> <?php echo $mem[login];?></td>
    </tr>
    <tr> 
      <td>Rank </td>
      <td> <input name="rank" type="text" id="rank" value="<?php echo $pr[type]; ?>"></td>
    </tr>
    <tr> 
      <td colspan="2"><div align="center">
          <input type="reset" name="Submit2" value="Reset">
          <input type="submit" name="Submit" value="Save">
        </div></td>
    </tr>
  </table>
</form>
<?php
  d($db);
  require("_footer.php");
?>