#
# --Confirmation e-mail file
#
# 			This file contains the message your customers
# 			will receive as a confirmation for the posted
#			auction.
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
#			<#c_name#>				customer name
#			<#c_nick#>				nick
#			<#c_email#> 			e-mail address
#
#			<#a_title#>				auction title
#			<#a_id#>					auction ID
#			<#a_description#>		description
#			<#a_picturl#>			picture url
#			<#a_minbid#>   		minimum bid
#			<#a_resprice#>			reserve price (if set)
#			<#a_duration#>			duration (in days)
#			<#a_location#>			item location
#			<#a_zip#>				item location zip
#			<#a_shipping#>			shipping terms
#			<#a_intern#>			international shipping terms
#											. will ship internationally
#											. will NOT ship internationally
#			<#a_payment#>			selected payment methods (one per line)
#			<#a_category#>			item's category choosen
#			<#a_subcategory#>		item's subcategory choosen
#			<#a_ends#>				closing date and time
#			<#a_url#>				the URL of the page
#        --------------------------------------------------------
#
#			USAGE:
#			Insert the above tags in the text of your message
#			where you want each value to appear.
#			Mofidy the message to reflect your needs.
#			Change [...] with to your correect data.
#
#
#
#

Liebe/r <#c_name#>,

Ihre Auktion wurde erfolgreich gestartet. Zum Vergleichen nochmal
alle wichtigen Daten im Überblick:

Artikel: <#a_title#>
Auktions ID: <#a_id#>
Verkäufer: <#c_nick#>
Startgebot: <#a_minbid#>
Höchstgebot: <#a_resprice#>

Auktion URL: <#a_url#>
Auktionsende: <#a_ends#>

Vielen Dank, dass Sie unseren kostenlosen Service in Anspruch genommen haben
und weiterhin viel Spass.