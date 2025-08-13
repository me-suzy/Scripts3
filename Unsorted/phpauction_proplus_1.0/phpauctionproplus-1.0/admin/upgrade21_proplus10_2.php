<?#//v.1.0.1
session_start();
	
if($HTTP_POST_VARS[prev] == "<< PREV")
{
		Header("Location: upgrade21_proplus10_1.php");
		exit;
}
elseif($HTTP_POST_VARS[next] == "NEXT >>")
{
		#//
		if($HTTP_SESSION_VARS[DB] == "new")
		{
			  if(empty($HTTP_POST_VARS[DB_HOST]))
			  {
				   $ERR = "*ERROR*: <FONT FACE=Courier>Database Host</FONT> missing";
			  }
			  elseif(empty($HTTP_POST_VARS[DB_NAME]))
			  {
				   $ERR = "*ERROR*: <FONT FACE=Courier>Database Name</FONT> missing";
			  }
			  elseif(empty($HTTP_POST_VARS[DB_USER]))
			  {
				   $ERR = "*ERROR*: <FONT FACE=Courier>Database User</FONT> missing";
			  }
			  else
			  {
				   #// Check database connection
				   if(!$res = @mysql_connect($DB_HOST,$DB_USER,$DB_PASSWORD))
				   {
					    $ERR = "*ERROR*: Database connection to <FONT FACE=Courier>$DB_HOST</FONT> failed.";
				   }
				   else
				   {
					    #// Create new database
					    if($HTTP_SESSION_VARS[DB] == "new")
					    {
						     $EXISTS = FALSE;
						     #// Does the database already exists?
						     $DBlist = mysql_list_dbs($res);
						     while($row = mysql_fetch_object($DBlist))
						     {
							       if($row->Database == $DB_NAME)
							       {
								        $ERR = "*ERROR*: Database <FONT FACE=Courier>$DB_NAME</FONT> already exists";
								        break 1;
								      }
						     }
						     if(!$ERR)
						     {
							       if(!@mysql_create_db($DB_NAME))
							       {
								         "*ERROR*: Attempt to create database <FONT FACE=Courier>$DB_NAME</FONT> failed.";
							       }
							       else
							       {
								         session_register(DB_NAME);
								         session_register(DB_HOST);
								         session_register(DB_USER);
								         session_register(DB_PASSWORD);
								         #// Next Step
								         Header("Location: upgrade21_proplus10_3.php?L=$DB_NAME");
								         exit;
							      }
						     }
					    }
					    else // $DB == current
					    {
						      if(!@mysql_select_db($DB_NAME))
						      {
							        $ERR = "*ERROR*: Faild to connect to database <FONT FACE=Courier>$DB_NAME</FONT>";
						      }
					    }
				   }
			  }
		}
		else
		{
			  session_register(DB_NAME);
					session_register(DB_HOST);
					session_register(DB_USER);
					session_register(DB_PASSWORD);
		  	#// Next Step
			  Header("Location: upgrade21_proplus10_3.php");
		  	exit;
		}					
}
	
if(empty($HTTP_POST_VARS[action]))
{
 		#// Retrieve information from the current installation
		
	 	@include $HTTP_SESSION_VARS[CURRENT_PATH]."/includes/passwd.inc.php";
		 @include $HTTP_SESSION_VARS[CURRENT_PATH]."/includes/config.inc.php";
		
 		$DB_HOST     = $DbHost;
	 	$DB_NAME     = $DbDatabase;
		 $DB_USER     = $DbUser;
 		$DB_PASSWORD = $DbPassword;
}
?>	
<html>
<head>
 <title>Phpauction Upgrade Script</title>
</head>
<body BGCOLOR="brown1" TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
  <TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>
  <FORM ACTION="<?=basename($PHP_SELF)?>" METHOD=post>
    <TR>
	     <TD>
	      	<TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		        <TR>
			         <TD>
			           <IMG SRC=images/logo.gif>
			           &nbsp;&nbsp;
			           <H1>Phpauction Pro Plus Upgrade Script - STEP 2
			           </H1>
			           <BR>
			           <BR>
			           <?
				            if(isset($ERR))
				            {
			           ?>
				              <FONT COLOR=RED><B><?=$ERR?></B></FONT><BR><BR>
			           <?
				            }
			           ?>
			           <FONT FACE=Helvetica SIZE=3>
			           <B>Current Phpauction 2.1 installation information:</B>
			           <UL>
			            <LI>Root directory: <FONT FACE=Courier COLOR=brown1><?=$HTTP_SESSION_VARS[CURRENT_PATH]?></FONT>
           			 <LI>Database Host: <FONT FACE=Courier COLOR=brown1><?=$DB_HOST?></FONT>
           			 <LI>Database Name: <FONT FACE=Courier COLOR=brown1><?=$DB_NAME?></FONT>
			            <LI>Database User: <FONT FACE=Courier COLOR=brown1><?=$DB_USER?></FONT>
			            <LI>Database Password: <FONT FACE=Courier COLOR=brown1><?=$DB_PASSWORD?></FONT>
			           </UL>
			           <B>New Phpauction Pro Plus 1.0 installation information:</B>
			           <UL>
			            <LI>Root directory: <FONT FACE=Courier COLOR=brown1><?=$HTTP_SESSION_VARS[NEW_PATH]?></FONT>
			            <?
				              if($HTTP_SESSION_VARS[DB] == "current")
				              {
			            ?>
				           <LI>Database Host: <FONT FACE=Courier COLOR=brown1><?=$DB_HOST?></FONT>
				           <LI>Database Name: <FONT FACE=Courier COLOR=brown1><?=$DB_NAME?></FONT>
				           <LI>Database User: <FONT FACE=Courier COLOR=brown1><?=$DB_USER?></FONT>
				           <LI>Database Password: <FONT FACE=Courier COLOR=brown1><?=$DB_PASSWORD?></FONT>
				           <INPUT TYPE=HIDDEN SIZE=20 NAME=DB_HOST VALUE="<?=$DB_HOST?>">
				           <INPUT TYPE=HIDDEN SIZE=20 NAME=DB_NAME VALUE="<?=$DB_NAME?>">
				           <INPUT TYPE=HIDDEN SIZE=20 NAME=DB_USER VALUE="<?=$DB_USER?>">
				           <INPUT TYPE=HIDDEN SIZE=20 NAME=DB_PASSWORD VALUE="<?=$DB_PASSWORD?>">
			            <?
				              }
				              else
				              {
			            ?>
      				           <TABLE WIDTH=500 CELLPADDING=2 CELLSPACING=0 BORDER=0>
      				             <TR>
					                    <TD WIDTH=25%>
						                     <FONT FACE=Helvetica SIZE=3>Database Host:
      					              </TD>
					                    <TD>
						                      <INPUT TYPE=text SIZE=20 NAME=DB_HOST VALUE="<?=$DB_HOST?>">
					                    </TD>
      				             </TR>
				                   <TR>
					                    <TD WIDTH=25%>
						                     <FONT FACE=Helvetica SIZE=3>Database Name:
					                    </TD>
					                    <TD>
						                     <INPUT TYPE=text SIZE=20 NAME=DB_NAME VALUE="<?=$DB_NAME?>">
					                    </TD>
				                   </TR>
				                   <TR>
					                    <TD WIDTH=25%>
						                     <FONT FACE=Helvetica SIZE=3>Database User:
					                    </TD>
					                    <TD>
						                     <INPUT TYPE=text SIZE=20 NAME=DB_USER VALUE="<?=$DB_USER?>">
					                    </TD>
				                   </TR>
				                   <TR>
					                    <TD WIDTH=25%>
						                     <FONT FACE=Helvetica SIZE=3>Database Password:
					                    </TD>
					                    <TD>
						                     <INPUT TYPE=password SIZE=20 NAME=DB_PASSWORD VALUE="<?=$DB_PASSWORD?>">
					                    </TD>
				                   </TR>
				                 </TABLE>
			            <?
				              }
			            ?>
				           </UL>
			            <BR><BR>
			            <FONT FACE=Helvetica SIZE=3>
			            If the above information is correct press the <font face=COURIER>NEXT >></FONT> button below to proceed,
			            otherwise press <font face=COURIER><< PREV</FONT> and make the necessary corrections.
			            <BR><BR>
			            <CENTER>
		             	<INPUT TYPE=hidden NAME=action VALUE=process>
			             <INPUT TYPE=submit NAME=prev VALUE="<< PREV">
			             <INPUT TYPE=submit NAME=next VALUE="NEXT >>">
			             <BR><BR><BR>
			             Copyright &copy; 2002, Phpauction.org
               </CENTER>
			         </TD>
		        </TR>
		      </TABLE>
	     </TD>
    </TR>
  </FORM>
</TABLE>
</body>
</html>
