<?php
	# User trying to login
	if($member_login)
	{
		$tmp_q = mysql_query("select * from st_admin where admin_username='$admin_username' AND admin_password='$admin_password'");
		if(@mysql_num_rows($tmp_q)>0)
		{
			$tmp_d = mysql_fetch_array($tmp_q);
			# Set cookie to log them into the site
			setcookie("logged_in",$tmp_d[id], time()+31536000);
			$logged_in=$tmp_d[id];
		}
	}

	# If Logged in check out their details
	if($logged_in)
	{
		$query = mysql_query("SELECT * FROM st_admin WHERE id='$logged_in'");
		$my_info = mysql_fetch_array($query);


$sql = mysql_query("SELECT * FROM st_admin_group WHERE usergroupid='$my_info[admin_groupid]'");
$access = mysql_fetch_array($sql);
}
?>