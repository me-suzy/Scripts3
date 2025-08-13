#
# --Send Auction to a Friend e-mail file
# 
# 			This file contains the message your customers
# 			will receive when someone sends them an auction.
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
#			<#s_name#>              sendername 
#			<#s_email#>             sender email 
#			<#s_comment#>           sender comment 
#			<#f_name#>              friend name 
#			<#f_email#>             friend email 
#			<#i_title#>             auction item title 
#			<#i_description#>       auction item description 
#			<#i_url#>               URL to view auction 
#           <#c_sitename#>          Auction Site Name
#           <#c_siteurl#>           main URL of auction site
#           <#c_adminemail#>        email address of Auction site webmaster
#        --------------------------------------------------------
#
#			USAGE:
#			Insert the above tags in the text of your message			
#			where you want each value to appear.			
#			Modify the message to reflect your needs.
#			Change [...] with to your correct data.
#
# 
#

Dear <#f_name#>,

<#s_name#> at <#s_email#> has forwarded an auction at
<#c_sitename#> for you to see.

<#s_name#> comments: <#s_comment#>

Title: <#i_title#>
Item: <#i_description#>  

You may visit the auction here: <#c_siteurl#><#i_url#>

If you have received this message in error, please reply to this email,
write to <#c_adminemail#>, or visit <#c_sitename#> at <#c_siteurl#>.


