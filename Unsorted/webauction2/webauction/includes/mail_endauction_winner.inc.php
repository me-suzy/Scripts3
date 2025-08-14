#
# --Send winner email address to seller
#
#                         This file contains the message your customers
#                         will receive when someone sends them an auction.
#                        Lines starting with # will be skipped.
#                        Blank lines will be maintained.
#
#                        Change the message below as needed considering the
#                        following tags to reflect your customer's personal data:
#
#        --------------------------------------------------------
#                        TAG SYNTAX                                EFFECT
#        --------------------------------------------------------
#
#                        <#s_name#>              Seller Name
#                        <#s_nick#>              Seller Nickname
#                        <#s_email#>             Seller email
#                        <#s_address#>           Seller Address
#                        <#s_city#>              Seller City
#                        <#s_prov#>              Seller State/Province
#                        <#s_country#>           Seller Country
#                        <#s_zip#>               Seller Zip Code
#                        <#s_phone#>             Seller Phone
#                        <#i_title#>             auction item title
#                        <#i_description#>       auction item description
#                        <#i_url#>               URL to view auction
#                        <#i_ends#>              Auction End date/time
#           <#w_report#>            Winner report/list
#           <#c_sitename#>          Auction Site Name
#           <#c_siteurl#>           main URL of auction site
#           <#c_adminemail#>        email address of Auction site webmaster
#        --------------------------------------------------------
#
#                        USAGE:
#                        Insert the above tags in the text of your message
#                        where you want each value to appear.
#                        Modify the message to reflect your needs.
#                        Change [...] with to your correct data.
#
#
#

Liebe/r <#s_name#>,

Folgende Auktion die Sie erstellt haben,ist beendet:

Titel: <#i_title#>
End Datum: <#i_ends#>
Höstgebot: <#o_bid#>
Höstbieter: <#b_nick#>

Bitte kontaktieren Sie den Gewinner unter:

<#b_name#>
<#b_address#>
<#b_zip#>
<#b_city#>
<#b_prov#>

<#b_phone#>

<#b_email#>

Bitte hinterlassen Sie nach dem Ende Ihrer Transaktion eine Bewertung unter: 
<#w_feedurl#> 

Eine E-Mail mit Ihren Daten wurde auch dem Gewinner zugeschickt.


<#c_siteurl#>