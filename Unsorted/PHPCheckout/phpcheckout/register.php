<?php require_once("configure.php");?>

<!DOCTYPE html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

<script language="Javascript" type="text/javascript" src="phpcheckout.js"></script>
<script language="Javascript" type="text/javascript">loadCSSFile();</script>

<!-- unique page heading -->
<!-- the page name, function or purpose | IMPLEMENTATIONNAME | BENEFIT | ORGANIZATION | MYKEYWORDS | Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows -->

<TITLE>Register -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

<META NAME="keywords" CONTENT="shopping cart, checkout, download, digital, products, php, php3, php4, software, freeware, download, code, source, freeware, downlaod, yellow page, directory, php, lookup, whois">
<META NAME="description" CONTENT="Enter your description here.">
<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com">
<META NAME="GENERATOR" CONTENT="Hard work and years of programming">
<META name="ROBOTS" content="INDEX,FOLLOW">
</HEAD>
<BODY>
<TABLE width="100%" align="center">
	<TR>
		<TD>
			<?php include_once("header.php");?>
			<?php if(FPSTATUS == 'Online' ):?>
				<!-- start of primary content FOR PAGE -->

<table width="100%">
<tr>
	<td width="28%" valign="top" class="dotted" bordercolor="silver">

		<table>
			<tr>
				<td>
					<h3>Why Register?</h3>
					<p style="font-size:x-small;">
						Register to:
						<ul style="font-size:x-small;">
							<li>access new or updated products or services
							<li>set your privacy level
							<li>subscribe to the newsletter
							<?php if (OFFERNEWSLETTER == "yes"):?><li>obtain technical support<?php endif;?>
						</ul>

					<h3>Already Registered?</h3>					
					<div align="center"><a href="login.php">Click here to Login</a></div>
					</p>
				</td>
			</tr>
		</table>

	</td>

	<td valign="top">
		<h1>Register</h1>
		<p>
			Submit this form to register. 
		</p>
	<?php include("registerForm.php");?></td>
</tr>
</table>



		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>
<?php else:include('offline.php');endif; // on or offline ?>