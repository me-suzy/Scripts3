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
#			<#o_name#>              Old Winner Name (email goes to this person) 
#			<#o_nick#>              Old Winner Nickname
#			<#o_email#>             Old Winner email
#			<#o_bid#>               Old Winner bid
#			<#n_bid#>               New Winning Bid
#			<#i_title#>             auction item title 
#			<#i_description#>       auction item description 
#			<#i_url#>               URL to view auction 
#			<#i_ends#>              Auction End date/time
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

Dear <#o_name#>,

Your bid of <#o_bid#> is no longer the leading bid for the auction at <#c_sitename#>:

Title: <#i_title#>
Item: <#i_description#>  
End Date: <#i_ends#>
New leading bid: <#n_bid#>

You may visit the auction here: <#c_siteurl#><#i_url#>

If you have received this message in error, please reply to this email,
write to <#c_adminemail#>, or visit <#c_sitename#> at <#c_siteurl#>.


