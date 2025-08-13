<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

// Require the library
   require ("../main.inc.php");

// -----------------
// Get the user info

   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// ----------------------------------------------------------------------
// If we are not logged in, then present a log on form, otherwise present
// a menu of options.

   if( ($user['U_Status'] != 'Administrator') && ($user['U_Status'] != 'Moderator') ) {
      $html -> send_header("You must login first.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/login.php?Cat=$Cat\">",0,0,0);

      $html -> admin_table_header("You must login first.");
      echo <<<EOF
        <TABLE BORDER=0 WIDTH="95%" ALIGN="center">
        <TR class="cleartable"><TD><span class="onbody">
         To access this section you must first be logged in.  You will be taken to the login screen shortly.  
         </span>
        </TD></TR>
        </TABLE>
EOF;
      $html -> send_admin_footer();
   }

// ------------------------------------------
// They are an admin so give them the options!
   elseif ($user['U_Status'] == 'Administrator') {

      $html -> send_header ("Welcome, {$user['U_Username']}",$Cat,0,$user);

      $html -> open_admin_table();
      echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Settings</b>
           </td></tr>
           </td><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/editconfig.php?Cat=$Cat" target="mainFrame">
           Edit config settings
           <br>
           <a href="{$config['phpurl']}/admin/edittheme.php?Cat=$Cat" target="mainFrame">
           Edit theme settings
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader target="mainFrame">
           <b>User Management</b> 
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/selectusers.php?Cat=$Cat" target="mainFrame">
           Show / Edit Users 
           </a>
           <br> 
           <a href="{$config['phpurl']}/admin/approveuser.php?Cat=$Cat" target="mainFrame">
           Approve new users 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/purgeusers.php?Cat=$Cat" target="mainFrame">
           Delete inactive users 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/purgemessages.php?Cat=$Cat" target="mainFrame">
           Delete old messages 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/assignmod.php?Cat=$Cat" target="mainFrame">
           Assign a moderator
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/banuser.php?Cat=$Cat" target="mainFrame">
           Ban a User / IP 
           </a>
	   <br>
           <a href="{$config['phpurl']}/admin/unbanuser.php?Cat=$Cat" target="mainFrame">
           Unban User / IP 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/sendemail.php?Cat=$Cat" target="mainFrame">
           Send email to users 
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Forum Management</b> 
           </td></tr> 
           <tr><td width=30% valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/createboard.php?Cat=$Cat" target="mainFrame">
           Create a forum 
           </a>  
           <br>
           <a href="{$config['phpurl']}/admin/editboard.php?Cat=$Cat" target="mainFrame">
           Edit or delete a forum 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/changeorder.php?Cat=$Cat" target="mainFrame">
           Change Forum order 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/createcat.php?Cat=$Cat" target="mainFrame">
           Create a category 
           </a>  
           <br>
           <a href="{$config['phpurl']}/admin/editcat.php?Cat=$Cat" target="mainFrame">
           Edit or delete a category 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/changecatorder.php?Cat=$Cat" target="mainFrame">
           Sort the category order. 
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Post Management</b> 
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewunapproved" target="mainFrame">
           Approve posts
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewexpthreads" target="mainFrame">
           Expire threads 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewothreads" target="mainFrame">
           Close threads 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewcthreads" target="mainFrame">
           Open threads 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewunkeptthreads" target="mainFrame">
           Keep threads 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewkeptthreads" target="mainFrame">
           Unkeep threads 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/whichuser.php?Cat=$Cat" target="mainFrame">
           Delete posts from a user 
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Group Management</b> 
           </td></tr> 
           <tr><td width=30% valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/creategroup.php?Cat=$Cat" target="mainFrame">
           Create a new group 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showgroups.php?Cat=$Cat" target="mainFrame">
           Rename a group
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/selectgroup.php?Cat=$Cat" target="mainFrame">
           Mass group change
           </a>
EOF;
       $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>DB Management</b> 
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/dbcommand.php?Cat=$Cat" target="mainFrame">
           SQL command
           </a>
           <br>
			  <a href="{$config['phpurl']}/admin/dbbackup.php?Cat=$Cat" target="mainFrame">
			  DB Backup
			  </a>
			  <br>
			  <a href="{$config['phpurl']}/admin/dbrestore.php?Cat=$Cat" target="mainFrame">
			  DB Restore
			  </a>
			  <br>
           <a href="{$config['phpurl']}/admin/dboptimize.php?Cat=$Cat" target="mainFrame">
           Optimize tables
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Information</b>
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=users" target="mainFrame">
           User stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=messages" target="mainFrame">
           Message stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=posts" target="mainFrame">
           Post stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=boards" target="mainFrame">
           Forum info 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/ubbtinfo.php?Cat=$Cat" target="mainFrame">
           UBB.threads Info 
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign="top" class="tdheader">
           <b>Templates</b>
           </td></tr>
           <tr><td valign="top" class="lighttable">
           <a href="{$config['phpurl']}/admin/selecttemplate.php?Cat=$Cat" target="mainFrame">
           Edit a template
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Styles</b>
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/newskin.php?Cat=$Cat" target="mainFrame">
           Create stylesheet
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/editskin.php?Cat=$Cat" target="mainFrame">
           Edit a stylesheet
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Filters</b> 
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/editfilters.php?Cat=$Cat&filter=bademail" target="mainFrame">
           Edit bad emails 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/editfilters.php?Cat=$Cat&filter=badnames" target="mainFrame">
           Edit reserved names 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/editfilters.php?Cat=$Cat&filter=badwords" target="mainFrame">
           Edit bad words 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/edittitles.php?Cat=$Cat" target="mainFrame">
           Edit titles
           </a>

EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Icons/Graemlins</b>
           </td></tr> 
           <tr><td valign=top class=lighttable>
				<a href="{$config['phpurl']}/admin/addicon.php?Cat=$Cat" target="mainFrame">Add a post icon</a>
				<br>
				<a href="{$config['phpurl']}/admin/removeicon.php?Cat=$Cat" target="mainFrame">Remove a post icon</a>
				<br>
				<a href="{$config['phpurl']}/admin/addgraemlin.php?Cat=$Cat" target="mainFrame">Add a graemlin</a>
				<br>
				<a href="{$config['phpurl']}/admin/editgraemlin.php?Cat=$Cat" target="mainFrame">Edit a graemlin</a>
				<br>
				<a href="{$config['phpurl']}/admin/removegraemlin.php?Cat=$Cat" target="mainFrame">Remove a graemlin</a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Includes</b>
           </td></tr> 
           <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=header" target="mainFrame">
           Edit Generic Header
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/selectfheader.php?Cat=$Cat" target="mainFrame">
           Edit Forum Headers
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=footer" target="mainFrame">
           Edit Generic Footer 
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/selectffooter.php?Cat=$Cat" target="mainFrame">
           Edit Forum Footers 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=closedforums" target="mainFrame">
           Edit closed message 
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=header-insert" target="mainFrame">
           Edit header-insert 
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=privacy" target="mainFrame">
           Edit Privacy statement 
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=boardrules" target="mainFrame">
           Edit Board Rules 
           </a> 
           <br>
           <a href="{$config['phpurl']}/admin/editincludes.php?Cat=$Cat&include=coppainsert" target="mainFrame">
           Edit COPPA insert 
           </a> 
EOF;
      $html -> close_table();
       echo "</BODY></HTML>";
   }

// --------------------------------------------
// They are a moderator so give them the options
   elseif ($user['U_Status'] == 'Moderator') {

      $html -> send_header ("Welcome, {$user['U_Username']}",$Cat,0,$user);
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>User Management</b> 
           </td></tr> 
           <tr><td valign=top class=lighttable width=30%>
EOF;
      if ($config['modedit']) {
        echo <<<EOF
          <a href="{$config['phpurl']}/admin/selectusers.php?Cat=$Cat" target="mainFrame">
          Show / Edit Users 
          </a>
          <br>
EOF;
      }
      echo <<<EOF
          <a href="{$config['phpurl']}/admin/banuser.php?Cat=$Cat" target="mainFrame">
          Ban a User / Host 
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/unbanuser.php?Cat=$Cat" target="mainFrame">
          Unban User / IP         
          </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Forum Management</b> 
           </td></tr> 
          <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/editboard.php?Cat=$Cat" target="mainFrame">
           Edit a forum 
           </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Post Management</b> 
           </td></tr> 
          <tr><td valign=top class=lighttable>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewunapproved" target="mainFrame">
          Approve posts
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewexpthreads" target="mainFrame">
          Expire threads
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewothreads" target="mainFrame">
          Close threads    
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewcthreads" target="mainFrame">
          Open threads
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewunkeptthreads" target="mainFrame">
          Keep threads 
          </a>
          <br>
          <a href="{$config['phpurl']}/admin/chooseforum.php?Cat=$Cat&choice=viewkeptthreads" target="mainFrame">
          Unkeep threads 
          </a>
EOF;
      $html -> close_table();
      echo "<p>";
      $html -> open_admin_table();
echo <<<EOF
           <tr><td valign=top class=tdheader>
           <b>Information</b> 
           </td></tr> 
          <tr><td valign=top class=lighttable>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=users" target="mainFrame">
           User stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=messages" target="mainFrame">
           Message stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=posts" target="mainFrame">
           Post stats 
           </a>
           <br>
           <a href="{$config['phpurl']}/admin/showstats.php?Cat=$Cat&action=boards" target="mainFrame">
           Forum info 
           </a>
EOF;
      $html -> close_table();
      echo "</BODY></HTML>";

   }

?>
