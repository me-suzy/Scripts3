<? require("admheader.php"); ?>
<!-- Table menu -->

<!-- Open table #1 -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 &nbsp; Categoryadmin 
</td>
</tr>

<tr bgcolor="white">
<td width="100%">


<?

if ($mode <> 'new' AND !$add)
{
?>

<!-- Open table #2 -->
<table width="100%" border="0" cellpadding="0" cellspacing="1"><tr><td>
<b> Change categoryname/description </b>
</td></tr><tr><td>
 
If you want to give a new name, image or description to a category, select the category below and push Modify. You will then see the category name and other details registered on this ad in the Add form below. To save the changes, push <b>UPDATE</b> button below that large form. <br />

<form method="post" action="add_cat.php">
<?php include("list_admin2.php"); ?>
<input type="submit" value="<? echo $mod ?>" />
</form>
</td></tr></table>


<?
}
?>


<? 
if (!$demom)
{
?>
<form method="post" action="add_cat.php">
<?
}
?>

<input type="hidden" name="updateid" value="<?php echo $category_wanted ?>" />
<?
$companyregistered = date('d.m.Y');
$sql_select = "select catid,catfatherid,catname,catdescription,catimage,cattpl,allowads from $cat_tbl where catid = '$category_wanted'";

if (!$catimage)
{
	$catimage = 'default.gif';
}

         if ($catfatherid <> 0)
         {
               $string = "select catfullname from $cat_tbl where catid = '$catfatherid'";
               $result_new = mysql_query ($string);
               $row = mysql_fetch_array($result_new);
               $catname1 = $row["catname"];
               $catfullname = $row["catfullname"];

                }

                if (!$catfullname)
                {
                          $catfullname = $catname_new;
                }
                 else
                {
                         $catfullname = $catfullname . "/" . $catname_new;
                }

                if (!$catfatherid)
                {
                          $catfatherid = 0;
                }

$sql_update = "update $cat_tbl set catfatherid='$catfatherid',catname='$catname_new',catdescription='$catdescription',catimage = '$catimage',cattpl = '$cattpl', allowads = '$allowads', catfullname = '$catfullname' where catid = '$updateid'";
//$sql_updatetpl = "update $cat_tbl set cattpl = '$cattpl' where catfatherid = $catfatherid";
$sql_insert = "insert into $cat_tbl (catfatherid,catname,catdescription,catimage,cattpl,allowads,catfullname) values ($catfatherid,'$catname_new','$catdescription','$catimage','$cattpl','$allowads','$catfullname')";



// }

if ($category_wanted)
{
        $result = mysql_query ($sql_select);


        while ($row = mysql_fetch_array($result))
        {
        $catid = $row["catid"];
        $catfatherid = $row["catfatherid"];
        $catname1 = $row["catname"];
        $catimage = $row["catimage"];
        $cattpl = $row["cattpl"];
        $allowads = $row["allowads"];
        $catdescription = $row["catdescription"];
        $catfullname = $row["catfullname"];
        }

}

print " <b>$status</b> ";

if ($mode == 'new' OR $category_wanted)
{
?>




<b>


<?
if ($category_wanted)
{
        print("<p /> Modify earlier registered category (edit mode) <p />");
}
else
{
        print("<p /> Add new category <p />");
}
?>
</b>


<p />&nbsp;

<!-- Open table #3 -->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="50%" valign="top"> Categoryname </td>
    <td width="50%" valign="top"> 
<input type="text" name="catname_new" size="38" value="<?php echo $catname1 ?>" />

    </td>
  </tr>
  <tr>
    <td width="50%" valign="top"> this is a category below:  </td>
    <td width="50%" valign="top">
    <?
    include("drop.php");
    if ($catfatherid == 0)
    {
     print("<small>   Top level cat </small>");
    }
    ?>
 
    </td>
  </tr>


    <tr>
    <td width="50%" valign="top"> <? echo $add_cat_description ?> </td>
    <td width="50%" valign="top"><textarea  rows="4" name="catdescription" cols="28"><?php echo $catdescription ?></textarea>

    </td>
  </tr>

           <tr>
    <td width="50%" valign="top"> Allow ads ? </td>
    <td width="50%" valign="top">
                <?
                print "<input type='checkbox' name='allowads' ";
                if ($allowads OR !$category_wanted)
                {
                          print "checked";
                }
                print " />";
                ?>


    </td>
  </tr>

  <tr>
    <td width="50%" valign="top"> Category image </td>
    <td width="50%" valign="top"><input type="text" name="catimage" size="38" value="<?php echo $catimage ?>" />
 

    </td>
  </tr>

          <tr>
    <td width="50%" valign="top"> Category template </td>
    <td width="50%" valign="top">
                <?
                print "<select name='cattpl'>";
                $sql = "select * from template order by tplid";
                if ($cattpl <> "")
                {
                print "<option selected='selected'>$cattpl</option><option value=''>None</option>";
                }
                else
                {
                print "<option value='' selected='selected'>None</option>";
                }
                $result = mysql_query ($sql);
                while ($row = mysql_fetch_array($result)) {
                 $name = $row[name];
                print "<option>$name</option>";
                }
                print "</select> <a href='extra.php'> (optional) </a>";
                ?>
  
    </td>
  </tr>


<tr><td colspan="2">
<?
if ($category_wanted)
{
print("<input type=\"submit\" class=\"txt\" name=\"add\" value=\"Update $catname1\" />");
print("&nbsp;&nbsp;<input type=\"submit\" name=\"delete\" class=\"txt\" value=\"Delete $catname1\" />");
}
else
{
print("<input type=\"submit\" name=\"add\" class=\"txt\" value=\"Create category\" />");
}
?>
</td></tr>


  <tr>
    <td width="100%" valign="top" colspan="2" bgcolor="#FFFFFF">
    </td>
  </tr>
</table>
<!-- Close table #3 -->



<?
}
?>
</form>

<?
if ($mode == 'new' OR $category_wanted)
{
?>

 
Name = Name of the category<br />
this is a category below = Under what category should this category be in?<br />
Description = Either a description of the category, OR a Yahoo style subcategory list made up by hyperlinks to
the sub-dirs. If you have a category Cars, and have subdirs Ford, Volvo, you type in a html hyperlink to the Ford and Volvo categories.<br />
 


<?
}
print " <a href='add_cat.php?mode=new'>Add new category</a> ";

if ($delete)
{

            $sql_delete = "delete from $cat_tbl where catid = $updateid";
            $result = mysql_query ($sql_delete);
            $status = "Deleted!";
}

if ($add)
{

         if ($updateid)
         {
          if ($catname_new)
          {


            $result = mysql_query ($sql_update);
            $result2 = mysql_query ($sql_updatetpl);
 
            $status = $updated_site;
            print("<p />Updated category $add_cat_title: $catname_new<p /><a href='add_cat.php'>Go back</a><p />");
            }
            else
            {
            print(" <b>Error</b><br />You must type in the NEW categoryname. Since you are i modify mode, the category will get renamed. It makes no sense to add a blank category. ");
            }
         }
   else
   {

        if ($catname_new)
        {

         $result = mysql_query ($sql_insert);
         $status = $registered_site;
         print("<p />Created $add_cat_title: $catname_new<p /><a href='add_cat.php'>Go back</a><p />");
         $catid = mysql_insert_id();
         $result2 = mysql_query ($sql_updatetpl);
        }
        else
        {
         print(" <b>Error</b><br />You must type in a categoryname to add. It makes no sense to add a blank category. ");
        }
   }

}


?>
<p />
</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
