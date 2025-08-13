<? 
include "admin/db.php";
$query = "select bin_data,filetype from binary_data where id=$id";
$result = @MYSQL_QUERY($query);
$data = @MYSQL_RESULT($result,0,"bin_data");
$type = @MYSQL_RESULT($result,0,"filetype");
//Header( "Content-type: $type");
echo $data;
  
?>   
