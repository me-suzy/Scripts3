<?
 
 /*
 
 	Database: subastas  Table: categories  Rows: 215
 	+-------------+----------+------+-----+---------+-------+
 	| Field       | Type     | Null | Key | Default | Extra |
 	+-------------+----------+------+-----+---------+-------+
 	| cat_id      | int(4)   | YES  |     |         |       |
 	| parent_id   | int(4)   | YES  |     |         |       |
 	| cat_name    | tinytext | YES  |     |         |       |
 	| deleted     | int(1)   | YES  |     |         |       |
 	| sub_counter | int(11)  | YES  |     |         |       |
 	| counter     | int(11)  | YES  |     |         |       |
 	+-------------+----------+------+-----+---------+-------+

*/
   include "./config.inc.php";


	
?>
<HTML>
<HEAD></HEAD>
<BODY>
<? 

//require "./header.html"; 


//--Delete existing tables content

$query = "delete from ".$dbfix."_categories";
$result = mysql_query($query);
if(!$result){
	print $ERR_00001;
	exit;
}

//--
$buffer = file("./categories.txt");
$count_cat  = 0;
$counter    = 0;
$id		    = 0;
$actuals[0] = 0;

//-- Skip comments and blank lines

while(!ereg("^1@(.)*$",$buffer[$counter])){
  $counter++;
}


while($counter < count($buffer)){
	
	//-- Process only significatives lines 
	
	//if(!ereg("^1@(.)*$",$buffer[$counter])){
		$category    = explode("@", $buffer[$counter]);
		$category[1] = ereg_replace(10,"",$category[1]);
  		$category[1] = ereg_replace(13,"",$category[1]);
	
		$id++;;
	
		if($category[0] != $actual){
			$actual 			= $category[0];
		}
		$actuals[$actual]	= $id;
	
  
  
		//-- Insert Category into CATEGORIES table
  	
    	$father = $actuals[$actual - 1];
    	print "F: $father - $category[1]<BR>";
		$query = "insert into ".$dbfix."_categories values($id,$father,\"$category[1]\",0,0,0)";
  		$result = mysql_query($query);
  		if(!$result){
  				print $ERR_00001;
  				print "<BR>$query - $actual";
  				exit;
  		}
  
	//}
	$counter++;
	$count_cat++;
  }
  

?>
<CENTER>
<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
<B>
	<? print $MSG_00082; ?>
</B>
</FONT>
<BR>
<BR>
<BR>
<CENTER>
<TABLE WIDTH=400 CELLPADDING=2>
<TR>
<TD WIDTH=50></TD>
<TD>
<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
<? 
print "$MSG_00086: $count_cat $MSG_00087<BR><BR>";
?>
</TD>
</TABLE>
<BR><BR>


<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
<A HREF="admin.php"><? print $MSG_00185; ?></A>
</FONT>
</CENTER>
<? require "./footer.html"; ?>
</BODY>
</HTML>
