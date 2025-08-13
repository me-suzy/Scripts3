#
# --Confirmation e-mail file
# 
# 			This file contains the message your customers
# 			will receive as a confirmation for the posted
#			auction.
#			Lines starting with # will be skipped.
#			Blank lines will be maintained.
#
#			Change the message below as needed using the 
#			following tags to reflect your customer's personal data:
#
#        --------------------------------------------------------
#			TAG SYNTAX				EFFECT
#        --------------------------------------------------------
#
#			<#c_name#>				customer name
#			<#c_nick#>				nick
#			<#c_email#> 			e-mail address
#			<#c_address#> 			street address
#			<#c_city#>   			city
#			<#c_country#> 			country
#			<#c_zip#> 			    zip
#			<#a_title#>				auction title
#			<#a_id#>				auction ID
#			<#a_description#>		description
#			<#a_picturl#>			picture url
#			<#a_minbid#>   			minimum bid
#			<#a_resprice#>			reserve price (if set)
#			<#a_duration#>			duration (in days)
#			<#a_location#>			item location
#			<#a_zip#>				item location zip
#			<#a_shipping#>			shipping terms
#			<#c_type#>   			auction type
#			<#c_qty#>   			auction type
#			<#a_intern#>			international shipping terms
#										. will ship internationally
#										. will NOT ship internationally
#			<#a_payment#>			selected payment methods (one per line)
#			<#a_ends#>				closing date and time
#			<#a_url#>				the URL of the page
#           <#c_sitename#>          site name
#           <#c_siteurl#>           site URL
#           <#c_adminemail#>        site administrator email address
#        --------------------------------------------------------
#
#			USAGE:
#			Insert the above tags in the text of your message			
#			where you want each value to appear.			
#			Modify the message to reflect your needs.
#		
# 
#

Dear <#c_name#>,

This is a confirmation message to notify you that the set up fee for your auction
"<#a_title#>" has been properly processed and the auction has been activated.

Auction URL: <#a_url#>

If you have received this message in error, please reply to this email or
write to <#c_adminemail#>.

Thank you for visiting <#c_sitename#> at <#c_siteurl#>.