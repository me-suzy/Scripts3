#
# --Confirmation e-mail file
# 
# 			This file contains the message your customers
# 			will receive as a confirmation after the registration
#			process.
#			Lines starting with # will be skipped.
#			Blank lines will be maintained.
#
#			Change the message below as needed considering the 
#			following tags to reflect your customer's personal data:
#
#        --------------------------------------------------------
#			TAG SYNTAX				EFFECT
#        --------------------------------------------------------
#
#			<#c_id#>							customer ID
#			<#c_name#>						name
#			<#c_nick#>						nick
#			<#c_address#> 					address
#			<#c_city#> 			   		city
#			<#c_prov#>  					province
#			<#c_country#> 					country
#			<#c_zip#> 						zip
#			<#c_phone#> 					phone number
#			<#c_email#> 					e-mail address
#			<#c_confirmation_page #>	e-mail address
#
#        --------------------------------------------------------
#
#			USAGE:
#			Insert the above tags in the text of your message			
#			where you want each value to appear.			
#			Mofidy the message to reflect your needs.
#
# 
# 
#  

Dear <#c_name#>,

Welcome to <#c_sitename#>. 

Thank you for registering and becoming our latest member!

In order to begin selling and/or buying at <#c_sitename#>, you will need to confirm your registration. 
 
Please follow the link below to reach the confirmation page:
<#c_confirmation_page#>


Your user information appears below.


Your Profile
--------------------
# ID: <#c_id#>					
Username: <#c_nick#>
Password: <#c_password#>
# Full Name: <#c_name#>
Address: <#c_address#>
City: <#c_city#>
Province/Region: <#c_prov#>  
Country: <#c_country#>
ZIP Code: <#c_zip#>
Telephone: <#c_phone#>
E-mail: <#c_email#>


Please visit us often at <#c_siteurl#>.

If you have received this message in error, please reply to this email or
write to <#c_adminemail#>.  




