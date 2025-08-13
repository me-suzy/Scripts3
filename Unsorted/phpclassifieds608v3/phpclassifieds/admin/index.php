<? require("admheader.php"); ?>
  <!-- Table menu -->
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
  
	<tr>
			<td bgcolor="lightgrey"> &nbsp; Welcome </td>
  </tr>
   
	<tr bgcolor="white">
	<td width="100%">
	<h2>PHP Classifieds</h2>		 

			 
 
Welcome to PHP Classifieds ADMIN section. From here, most of your
admin tasks can be done. Here is a short summary about what the different
parts of this admin area can do.
 

<p />
<p />

 
<b>User admin</b><br />
Here you get a list of all users on your system. You get a list of their name, when they
was registered, how many ads they have, number of credits etc. From the user admin, 
you can also grant/remove credits (if option activated from admin area), deactivate/activate users
and of course delete users.
  


<p />
 
<b>Category admin</b><br />
From here, all your categories are set-up. You can create unlimited sub-dirs, and attach
different template-fields to each category you create
 
<p />

 
<b>Ads admin</b><br />
In order to delete ads manually, this script is included. As default,
it list the 10 last ads, but by using the select button, you can
choose to view all ads in the selected category. You can also edit
all ads.
 

<p />

 
<b>Email members</b><br />
This is a newsletter-script. It only sends out mail to
every member that have signed up, and that has agreed to recieve
newsletter. If you have large user-base, you should adjust php.ini
to not time out at 30.
 


<p />

 
<b>Settings</b><br />
Here is the option for the hole program set. This script generates a config
file that is global to all scripts. It generates both config.inc.php, and header
and footer files. Remember to set path and your Reply-emailaddress.
 

<p />


 
<b>Extra fields</b><br />
If you want to record details like Yearmodel in your car-category,
and feet in your boat-category, this is the place to make these templates.
One template for each different category. Each of these templates get
attached to a cat by using the Category admin script.
 

<p />
 
<b>Check update</b><br />
Here you can get the latest, updated language files.
 

<p /><p />

 
PHP Classifieds is Copyright &copy; Haugsdal Webtjenester (DeltaScripts) 2001-2003.
 
	   
	 </td>
   </tr>
   </table>
   <!-- END Table menu -->
<? require("admfooter.php"); ?>
