<?
include("../admin/funcs.inc");
include("../admin/include.inc");
$link = mysql_connect ($sql_host, $sql_user , $sql_pass);
mysql_select_db($sql_db);

    if ($fp = fopen("/htdocs/3xtrem/3xtrem.com/cgi-bin/elitetgp/data/post.dat","r"))
      {
     while ($data = fgetcsv ($fp, 1000, ";")) {
    list ($in, $ip, $category, $desc, $pics, $url, $email, $val, $id, $days ) = $data; 
    $date = time() - $days*86400;
    $query = "insert into mytgp_post values ('','$ip','','$category','$desc',$pics,'$url','$email','NO', $date, '', '$sql_uin')";
    $result = mysql_query ($query) or die ("Insert query failed. Query: ".$query."  ".$result);
    $num = count ($data);
    print "<p> $num fields in line $row: <br>";
    $row++;
    for ($c=0; $c<$num; $c++) {
        print $data[$c];
    }
   print "<br>";
}
      } else show_error_msg ("Can't Open File");

	mysql_close ($link);
?>